<?php

namespace App\Repositories;

use Illuminate\Http\UploadedFile;

interface GantiPelelangRepositoryInterface
{

    public function handleChangePelelang(array $data);

    public function getById($id);
}
