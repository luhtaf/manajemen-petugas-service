<?php

namespace App\Http\Controllers\Api\V1\RoleKanwil\Petugas\RolePlh;

use App\Http\Controllers\Controller;
use App\Repositories\PetugasRolePlhRepository;
use App\Http\Resources\PetugasRolePlhResource;
use Illuminate\Http\Request;

class GetById extends Controller {

    protected $petugasRepository;

    public function __construct(PetugasRolePlhRepository $petugasRepository) {
        $this->petugasRepository= $petugasRepository;
    }


    public function findPetugas(String $id) {
        try {
            // Attempt to find the Petugas by ID
            $petugas = $this->petugasRepository->getById($id);

            // Jika petugas tidak ditemukan, menambahkan field message
            if (!$petugas) {
                return response()->json([
                    'message' => 'Petugas tidak ditemukan.'
                ], 404);
            }

            // Return tanpa ada exception melalui ini
            return new PetugasRolePlhResource($petugas);

        } catch (Exception $e) {
            // Return error response if something fails
            return response()->json([
                'success' => false,
                'message' => 'Error fetching Petugas: ' . $e->getMessage(),
            ], 404); // You can return 404 for "not found" or 500 depending on the error
        }
    }
}
