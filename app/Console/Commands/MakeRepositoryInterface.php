<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use ReflectionClass;

class MakeRepositoryInterface extends Command
{
    protected $signature = 'make:repository-interface {name}';
    protected $description = 'Create a new repository interface for an existing repository class';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $repositoryName = Str::studly($name) . 'Repository';
        $interfaceName = Str::studly($name) . 'RepositoryInterface';
        $repositoryPath = app_path("Repositories/{$repositoryName}.php");
        $interfacePath = app_path("Repositories/{$interfaceName}.php");  // Same folder as repository

        if (!file_exists($repositoryPath)) {
            $this->error("Repository does not exist!");
            return;
        }

        // Check if the interface already exists
        if (file_exists($interfacePath)) {
            $this->error("Repository interface already exists!");
            return;
        }

        // Reflect on the repository class to get its public methods
        require_once $repositoryPath;
        $repositoryClass = "App\\Repositories\\{$repositoryName}";
        $reflection = new ReflectionClass($repositoryClass);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

        $interfaceMethods = '';
        foreach ($methods as $method) {
            if ($method->class === $repositoryClass && $method->name !== '__construct') {
                $params = [];
                foreach ($method->getParameters() as $param) {
                    $paramStr = ($param->getType() ? $param->getType() . ' ' : '') . '$' . $param->name;
                    if ($param->isOptional()) {
                        $paramStr .= ' = ' . var_export($param->getDefaultValue(), true);
                    }
                    $params[] = $paramStr;
                }
                $paramString = implode(', ', $params);
                $interfaceMethods .= "    public function {$method->name}({$paramString});\n";
            }
        }

        // Generate the interface file content
        $interfaceStub = <<<EOT
<?php

namespace App\Repositories;

interface {$interfaceName}
{
{$interfaceMethods}}
EOT;

        // Write the interface file
        file_put_contents($interfacePath, $interfaceStub);
        $this->info("Repository interface created successfully.");

        // Add interface implementation to the repository if not already added
        $repositoryContent = file_get_contents($repositoryPath);
        if (strpos($repositoryContent, "implements {$interfaceName}") === false) {
            $repositoryContent = str_replace(
                "class {$repositoryName}",
                "class {$repositoryName} implements {$interfaceName}",
                $repositoryContent
            );
            file_put_contents($repositoryPath, $repositoryContent);
            $this->info("Repository now implements the interface.");
        }
    }
}
