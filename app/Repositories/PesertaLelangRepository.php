<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Models\PesertaLelang;
use App\Models\User;
use Illuminate\Support\Facades\Auth;



class PesertaLelangRepository
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
    public function __construct(PesertaLelang $model)
    {
		$this->model = $model;
        $this->user = Auth::guard()->user();
    }
   
	
	 public function getpemenang()
    {
      return $this->model->where('pemenang_lelang', 'true')->get();
    }
	
}
