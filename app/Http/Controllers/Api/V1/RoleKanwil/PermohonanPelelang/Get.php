<?php

namespace App\Http\Controllers\Api\V1\RoleKanwil\PermohonanPelelang;

use App\Http\Controllers\Controller;
use App\Repositories\PermohonanRepository;
use App\Http\Resources\PermohonanGantiPelelangResource;
use Illuminate\Http\Request;

class Get extends Controller {

    protected $permohonanRepository;

    public function __construct(PermohonanRepository $permohonanRepository) {
        $this->permohonanRepository= $permohonanRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/role_kanwil/permohonan_ganti_pelelang",
     *     summary="Get Permohonan with related data",
     *     tags={"Permohonan"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Permohonan",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/PermohonanResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Permohonan not found"
     *     )
     * )
     */
    public function getPermohonanLelang(Request $request)
    {
        $allowedPageSizes = [5, 10, 25, 50, 100];
        $perPage = $request->has('size')
            ? (in_array($request->input('size'), $allowedPageSizes) ? $request->input('size') : 5)
            : 5;

        $paginatedPermohonan = $this->permohonanRepository->getPaginated($perPage);

        $paginatedPermohonan->getCollection()->transform(function ($permohonan) {
            return $this->permohonanRepository->getAllData($permohonan);
        });

        return PermohonanGantiPelelangResource::collection($paginatedPermohonan);
    }

}
