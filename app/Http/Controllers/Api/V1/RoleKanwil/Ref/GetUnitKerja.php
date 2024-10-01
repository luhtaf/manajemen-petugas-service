<?php

namespace App\Http\Controllers\Api\V1\RoleKanwil\Ref;

use App\Http\Controllers\Controller;
use App\Repositories\UnitKerjaRepository;
use App\Http\Resources\UnitKerjaResource;
use Illuminate\Http\Request;

class GetUnitKerja extends Controller {

    protected $unitKerjaRepository;

    public function __construct(UnitKerjaRepository $unitKerjaRepository) {
        $this->unitKerjaRepository = $unitKerjaRepository;
    }

    public function getUnitKerja() {

        $unitKerja = $this->unitKerjaRepository->getAll();
        return UnitKerjaResource::collection($unitKerja);
    }
}
