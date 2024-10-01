<?php

namespace App\Repositories;

interface PetugasRepositoryInterface
{
    public function getById($id);
    public function getAll($perPage);
    public function create(array $data);
    public function update(array $data, string $id);
    public function delete(string $id);
}