<?php

namespace App\Http\Controllers\Api\V1\RoleKanwil\Petugas\RolePlh;

use App\Http\Controllers\Controller;
use App\Http\Requests\PetugasPlhRequest;
use App\Http\Resources\PetugasRolePlhResource;
use App\Repositories\PetugasRolePlhRepository;



class Post extends Controller {

    protected $petugasRepository;

    public function __construct(PetugasRolePlhRepository $petugasRepository) {
        $this->petugasRepository= $petugasRepository;
    }

    public function addPetugas(PetugasPlhRequest $request) {
        try
        {
            // Validasi input dari request
            $validated = $request->validated();

            // Ciptakan record Petugas baru
            $petugas = $this->petugasRepository->create($validated);

            // Kembalikan resource Petugas dengan pesan sukses
            return (new PetugasRolePlhResource($petugas))->additional([
                'message' => 'Petugas baru berhasil dibuat.'
            ]);

        }
        catch (\Illuminate\Validation\ValidationException $e)
        {
            // Tangani kesalahan validasi
            return response()->json([
                'error' => 'Validasi gagal',
                'messages' => $e->errors() // Menampilkan pesan kesalahan validasi
            ], 422);
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            // Tangani kesalahan yang berkaitan dengan query database (misalnya, pelanggaran constraint)
            return response()->json([
                'error' => 'Database error: ' . $e->getMessage()
            ], 500);
        }
        catch (\Exception $e)
        {
            // Tangani semua kesalahan lainnya
            return response()->json([
                'error' => 'Gagal membuat Petugas: ' . $e->getMessage()
            ], 500);
        }
    }

}
