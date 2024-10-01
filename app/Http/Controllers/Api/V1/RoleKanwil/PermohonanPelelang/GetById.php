<?php

namespace App\Http\Controllers\Api\V1\RoleKanwil\PermohonanPelelang;

use App\Http\Controllers\Controller;
use App\Repositories\PermohonanRepository;
use App\Http\Resources\PermohonanGantiPelelangResource;
use Illuminate\Http\Request;

class GetById extends Controller {

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

    public function permohonanDetail(Request $request,$id)
    {
        try {
            // Attempt to find the Petugas by ID
            $permohonan = $this->permohonanRepository->getDetailData($id);

            // If Petugas not found, throw an exception
            if (!$permohonan) {
                throw new Exception("Petugas with ID {$id} not found.");
            }

            // Return success response with Petugas data
            return (new PermohonanGantiPelelangResource($permohonan))->additional([
                'message' => 'Permohonan retrieved successfully.'
            ]);

        } catch (Exception $e) {
            // Return error response if something fails
            return response()->json([
                'success' => false,
                'message' => 'Error fetching Petugas: ' . $e->getMessage(),
            ], 404); // You can return 404 for "not found" or 500 depending on the error
        }
    }


}
