<?php

namespace App\Repositories;

use App\Models\Petugas;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use App\Models\PetugasPerbantuanPejabatLelang;

class PetugasPerbantuanPejabatLelangRepository
{
    protected $fileRepository, $petugasModel, $unitKerjaRepository, $user;

    public function __construct(FileRepository $fileRepository, Petugas $petugasModel, UserRepository $user, UnitKerjaRepository $unitKerjaRepository) {
        $this->fileRepository = $fileRepository;
        $this->unitKerjaRepository = $unitKerjaRepository;
        $this->petugasModel = $petugasModel;
        $this->user = $user;
    }

    public function getById($id) {
        return $this->petugasModel::with(['unit_kerja:id,nama', 'group:id,name','perbantuan_pejabat_lelang.file_nd','perbantuan_pejabat_lelang.file_kesediaan'])->find($id);
    }

    public function update(array $data, string $id) {
        DB::beginTransaction();

        try {

            // Cari record petugas berdasarkan ID, atau gagal jika tidak ditemukan
            $petugasRecord = $this->petugasModel::with('perbantuan_pejabat_lelang')->findOrFail($id);

            // Update data Petugas
            $petugasRecord->update([
                'nip' => $data['nip'],
                'nama' => $data['nama'],
                'unit_kerja_id' => $data['unit_kerja_id'],
                'group_id' => $data['group_id'],
            ]);

            // Check if perbantuan_pejabat_lelang exists
            $perbantuanRecord = $petugasRecord->perbantuan_pejabat_lelang;

            // If it doesn't exist, create a new one
            if (!$perbantuanRecord) {
                $perbantuanRecord = new PetugasPerbantuanPejabatLelang(); // Assuming you have the model
                $perbantuanRecord->petugas_id = $petugasRecord->id;
            }


            // Update or create the related records for perbantuan_pejabat_lelang
            if (isset($data['file_nd']) && $data['file_nd'] instanceof UploadedFile) {
                $perbantuanRecord->file_nd_id = $this->fileRepository->uploadFile($perbantuanRecord->file_nd_id ?? null, $data['file_nd']);
            }

            if (isset($data['file_kesediaan']) && $data['file_kesediaan'] instanceof UploadedFile) {
                $perbantuanRecord->file_kesediaan_id = $this->fileRepository->uploadFile($perbantuanRecord->file_kesediaan_id ?? null, $data['file_kesediaan']);
            }
            // Save the perbantuan_pejabat_lelang record
            $perbantuanRecord->save();

            DB::commit();

            // Kembalikan object petugas yang telah diperbarui
            return $petugasRecord;

        } catch (\PDOException $e) {
            DB::rollBack();
            // Log the error before throwing it for better debugging
            \Log::error('PDO Exception: ' . $e->getMessage());

            if ($e->getCode() === '23503') {
                throw new \Exception('Pelanggaran foreign key: ' . $e->getMessage());
            }

            throw new \Exception('Error database: ' . $e->getMessage());

        } catch (\Exception $e) {
            DB::rollBack();
            // Log general exceptions for debugging
            \Log::error('General Exception: ' . $e->getMessage());

            throw new \Exception('Update gagal: ' . $e->getMessage());
        }
    }

    public function create(array $data) {
        DB::beginTransaction();

        try {

            // Buat record Petugas baru
            $petugasRecord = $this->petugasModel::create([
                'nip' => $data['nip'],
                'nama' => $data['nama'],
                'unit_kerja_id' => $data['unit_kerja_id'],
                'group_id' => $data['group_id'],
            ]);

            // Buat record baru untuk perbantuan_pejabat_lelang
            $perbantuanRecord = new PetugasPerbantuanPejabatLelang(); // Model untuk perbantuan pejabat lelang
            $perbantuanRecord->petugas_id = $petugasRecord->id;

            // Upload file nd jika ada
            if (isset($data['file_nd']) && $data['file_nd'] instanceof UploadedFile) {
                $perbantuanRecord->file_nd_id = $this->fileRepository->uploadFile(null, $data['file_nd']);
            }

            // Upload file kesediaan jika ada
            if (isset($data['file_kesediaan']) && $data['file_kesediaan'] instanceof UploadedFile) {
                $perbantuanRecord->file_kesediaan_id = $this->fileRepository->uploadFile(null, $data['file_kesediaan']);
            }

            // Simpan record perbantuan_pejabat_lelang
            $perbantuanRecord->save();

            DB::commit();

            // Kembalikan object petugas yang baru dibuat
            return $petugasRecord;

        } catch (\PDOException $e) {
            DB::rollBack();
            // Log error untuk debugging lebih baik
            \Log::error('PDO Exception: ' . $e->getMessage());

            if ($e->getCode() === '23503') {
                throw new \Exception('Pelanggaran foreign key: ' . $e->getMessage());
            }

            throw new \Exception('Error database: ' . $e->getMessage());

        } catch (\Exception $e) {
            DB::rollBack();
            // Log general exceptions untuk debugging
            \Log::error('General Exception: ' . $e->getMessage());

            throw new \Exception('Pembuatan gagal: ' . $e->getMessage());
        }
    }





    // Add more methods as needed
}
