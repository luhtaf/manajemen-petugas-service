<?php

namespace App\Repositories;

interface PetugasRolePlhRepositoryInterface
{
    public function getById($id);
    public function getAll($perPage);
    public function create(array $data);
    public function update(array $data, string $id);
    public function delete(string $id);
}