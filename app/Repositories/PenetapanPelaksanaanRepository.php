<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Models\PenetapanPelaksanaan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class PenetapanPelaksanaanRepository
{
    /**
     * Authenticated User Instance.
     *
     * @var User
     */
    public User | null $user;

    /**
     * Constructor.
     */
	 
	protected $model;
    public function __construct(PenetapanPelaksanaan $model)
    {
		$this->model = $model;
        $this->user = Auth::guard()->user();
    }
  
   public function getAll()
    {
       return $this->model->all();
    }
}
