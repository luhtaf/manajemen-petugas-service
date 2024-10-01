<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface
{

    protected $user, $userModel, $request;

    public function __construct(Request $request, User $userModel) {
        $this->userModel = $userModel;
    }

    public function current_user() {
        $user=Auth::user();
        return $user->profil;
    }


    // Add more methods as needed
}
