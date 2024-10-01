<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {name}';
    protected $description = 'Create a new repository class';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $repositoryName = Str::studly($name) . 'Repository';
        $path = app_path("Repositories/{$repositoryName}.php");

        if (file_exists($path)) {
            $this->error("Repository already exists!");
            return;
        }

        $stub = file_get_contents(__DIR__ . '/stubs/repository.stub');
        $stub = str_replace('{{ repositoryName }}', $repositoryName, $stub);
        $stub = str_replace('{{ modelName }}', Str::studly($name), $stub);

        file_put_contents($path, $stub);
        $this->info("Repository created successfully.");
    }
}
