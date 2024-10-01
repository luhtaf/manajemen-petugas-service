<?php

namespace App\Http\Controllers\Api\V1\RoleKanwil\Petugas\PerbantuanPejabatLelang;

use App\Http\Controllers\Controller;
use App\Http\Requests\PetugasPerbantuanPejabatLelangRequest;
use App\Http\Resources\PetugasPerbantuanPejabatLelangResource;
use App\Repositories\PetugasPerbantuanPejabatLelangRepository;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class Post extends Controller {

    protected $petugasRepository;

    public function __construct(PetugasPerbantuanPejabatLelangRepository $petugasRepository) {
        $this->petugasRepository = $petugasRepository;
    }

    public function perbantuanPelelang(PetugasPerbantuanPejabatLelangRequest $request) {
        try {
            // Validasi input dari request
            $validated = $request->validated();

            // Ciptakan record Petugas baru
            $petugas = $this->petugasRepository->create($validated);

            // Kembalikan resource Petugas dengan pesan sukses
            return (new PetugasPerbantuanPejabatLelangResource($petugas))->additional([
                'message' => 'Pelelang Perbantuan berhasil ditambah.'
            ]);

        } catch (ValidationException $e) {
            // Tangani kesalahan validasi
            return response()->json([
                'error' => 'Validasi gagal',
                'messages' => $e->errors() // Menampilkan pesan kesalahan validasi
            ], 422);
        } catch (QueryException $e) {
            // Tangani kesalahan yang berkaitan dengan query database (misalnya, pelanggaran constraint)
            return response()->json([
                'error' => 'Database error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            // Tangani semua kesalahan lainnya
            return response()->json([
                'error' => 'Gagal memperbarui Petugas: ' . $e->getMessage()
            ], 500);
        }
    }
}
