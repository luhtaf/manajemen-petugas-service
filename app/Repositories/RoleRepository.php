<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository
{
    protected $roleModel;
    protected $userRepository;

    public function __construct(Role $roleModel, UserRepository $userRepository) {
        $this->roleModel = $roleModel;
    }

    public function getAll()
    {
        // return $this->userRepository->current_user()->profil;
        return $this->roleModel::all();
    }



    // Add more methods as needed
}
