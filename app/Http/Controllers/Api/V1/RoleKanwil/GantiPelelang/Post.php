<?php

namespace App\Http\Controllers\Api\V1\RoleKanwil\GantiPelelang;

use App\Http\Controllers\Controller;
use App\Http\Requests\GantiPelelangRequest;
use App\Http\Resources\GantiPelelangResource;
use App\Repositories\GantiPelelangRepository;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class Post extends Controller {

    protected $gantiPelelangRepository;

    public function __construct(GantiPelelangRepository $gantiPelelangRepository) {
        $this->gantiPelelangRepository = $gantiPelelangRepository;
    }

    public function changePelelang(GantiPelelangRequest $request) {

        try {
            // Validate the incoming request data
            $validated = $request->validated();
            // Use the repository to handle the process
            $gantiPelelang = $this->gantiPelelangRepository->handleChangePelelang($validated);

            return (new GantiPelelangResource($gantiPelelang))->additional([
                'message' => 'Petugas berhasil diperbaharui.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

}
