<?php

namespace App\Http\Controllers\Api\V1\RoleKanwil\Petugas\RolePlh;

use App\Http\Controllers\Controller;
use App\Repositories\PetugasRolePlhRepository;
use App\Http\Resources\PetugasRolePlhResource;
use Illuminate\Http\Request;

class Get extends Controller {

    protected $petugasRepository;

    public function __construct(PetugasRolePlhRepository $petugasRepository) {
        $this->petugasRepository= $petugasRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/role_kanwil/petugas/role_plh",
     *     summary="Get all petugas with role PLH",
     *     tags={"role_kanwil"},
     *     security={{"Bearer": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/PetugasRolePlhResource")
     *     ),
     *     @OA\Parameter(
     *         name="size",
     *         in="query",
     *         required=false,
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", enum={5, 10, 25, 50, 100})
     *     )
     * )
     */
    public function getPetugas(Request $request) {
        // Allowed per-page options
        $allowedPerPageOptions = [5, 10, 25, 50, 100];

        // Get the per_page value from the request, defaulting to 10 if not provided
        $perPage = $request->input('size');

        // Check if the per_page value is allowed, otherwise default to 10
        if (!in_array($perPage, $allowedPerPageOptions)) {
            $perPage = 10;
        }

        $petugas = $this->petugasRepository->getAll($perPage);
        return PetugasRolePlhResource::collection($petugas);
    }
}
