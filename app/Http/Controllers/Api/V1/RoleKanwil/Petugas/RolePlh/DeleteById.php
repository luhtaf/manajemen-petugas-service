<?php

namespace App\Http\Controllers\Api\V1\RoleKanwil\Petugas\RolePlh;

use App\Http\Controllers\Controller;
use App\Repositories\PetugasRolePlhRepository;
use App\Http\Resources\PetugasRolePlhResource;
use Illuminate\Http\Request;

class DeleteById extends Controller {

    protected $petugasRepository;

    public function __construct(PetugasRolePlhRepository $petugasRepository) {
        $this->petugasRepository= $petugasRepository;
    }


    public function deletePetugas($id) {
        try {
            // Call the delete method from the repository
            $this->petugasRepository->delete($id);

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Petugas and associated file deleted successfully.',
            ], 200);

        } catch (Exception $e) {
            // Return error response if the deletion fails
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Petugas: ' . $e->getMessage(),
            ], 500);
        }
    }

}
