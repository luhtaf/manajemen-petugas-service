<?php

namespace App\Http\Controllers\Api\V1\RoleKanwil\GantiPelelang;

use App\Http\Controllers\Controller;
use App\Repositories\GantiPelelangRepository;
use App\Http\Resources\GantiPelelangResource;
use Illuminate\Http\Request;
use Exception;

class GetById extends Controller {

    protected $gantiPelelangRepository;

    public function __construct(GantiPelelangRepository $gantiPelelangRepository) {
        $this->gantiPelelangRepository = $gantiPelelangRepository;
    }

    public function getHistoryPelelang(Request $request, $id) {
        try {
            // Attempt to find the Petugas by ID
            $gantiPelelang = $this->gantiPelelangRepository->getById($id);

            // If Petugas not found, throw an exception
            if (!$gantiPelelang) {
                throw new Exception("Petugas with ID {$id} not found.");
            }

            // Return success response with Petugas data as a resource and additional message
            return (new GantiPelelangResource($gantiPelelang))->additional([
                'message' => 'History of Ganti Pelelang retrieved successfully.'
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
