<?php

namespace App\Http\Controllers\Api\V1\RoleKanwil\Ref;

use App\Http\Controllers\Controller;
use App\Repositories\PelelangRepository;
use App\Http\Resources\PelelangResource;
use Illuminate\Http\Request;

class GetPelelang extends Controller {

    protected $pelelangRepository;

    public function __construct(PelelangRepository $pelelangRepository) {
        $this->pelelangRepository = $pelelangRepository;
    }

    public function getPelelang() {
        $pelelang = $this->pelelangRepository->getPetugasLelangAll();
        return PelelangResource::collection($pelelang);
    }
}
