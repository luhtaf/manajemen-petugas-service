<?php

namespace App\Http\Controllers\Api\V1\RoleKanwil\Ref;

use App\Http\Controllers\Controller;
use App\Repositories\AlasanRepository;
use App\Http\Resources\AlasanResource;
use Illuminate\Http\Request;

class GetAlasan extends Controller {

    protected $alasanRepository;

    public function __construct(AlasanRepository $alasanRepository) {
        $this->alasanRepository = $alasanRepository;
    }

    public function getAlasan() {
        $alasan = $this->alasanRepository->getAll();
        return AlasanResource::collection($alasan);
    }
}
