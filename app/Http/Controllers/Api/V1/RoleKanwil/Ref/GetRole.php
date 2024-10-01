<?php

namespace App\Http\Controllers\Api\V1\RoleKanwil\Ref;

use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;

class GetRole extends Controller {

    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository) {
        $this->roleRepository= $roleRepository;
    }

    public function getRole() {
        $role= $this->roleRepository->getAll();
        return RoleResource::collection($role);
    }
}
