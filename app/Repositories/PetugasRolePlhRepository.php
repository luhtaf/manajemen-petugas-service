<?php

namespace App\Repositories;
use Illuminate\Http\UploadedFile;
use App\Models\Petugas;
use Illuminate\Support\Facades\DB;

class PetugasRolePlhRepository implements PetugasRolePlhRepositoryInterface {
    protected $fileRepository, $petugasModel, $unitKerjaRepository, $user;

    public function __construct(FileRepository $fileRepository, Petugas $petugasModel, UserRepository $user, UnitKerjaRepository $unitKerjaRepository) {
        $this->fileRepository = $fileRepository;
        $this->unitKerjaRepository = $unitKerjaRepository;
        $this->petugasModel = $petugasModel;
        $this->user = $user;
    }

    public function getById($id) {
        return $this->petugasModel::with(['unit_kerja:id,nama', 'group:id,name','file'])->find($id);
    }

    public function getAll($perPage) {
        $user = $this->user->current_user();
        $unitKerjaIds = $this->unitKerjaRepository->getAllUnitIdsUnderKanwil($user->unit_kerja_id);

        // Cek apakah ada unit kerja ID yang ditemukan
        if (empty($unitKerjaIds)) {
            // Jika tidak ada unit kerja ID ditemukan, kembalikan hasil pagination kosong
            return $this->petugasModel::whereRaw('1 = 0')->paginate($perPage);
        }

        // Mendapatkan tanggal hari ini
        $today = now()->toDateString();

        return $this->petugasModel::select('id', 'nama', 'nip', 'unit_kerja_id', 'group_id')
            ->with(['unit_kerja:id,nama', 'group:id,name', 'role_plh'])
            ->whereIn('unit_kerja_id', $unitKerjaIds)
            ->where(function($query) use ($today) {
                // Mengambil semua yang tidak punya role_plh atau yang role_plh masih aktif
                $query->whereDoesntHave('role_plh') // Tidak ada role_plh
                      ->orWhere(function($subQuery) use ($today) {
                          // Jika ada role_plh, cek apakah tgl_sk <= hari ini dan exp_sk >= hari ini
                          $subQuery->where('tgl_sk', '<=', $today)
                                   ->whereHas('role_plh', function($rolePlhQuery) use ($today) {
                                       $rolePlhQuery->where('exp_sk', '>=', $today);
                                   });
                      });
            })
            ->paginate($perPage);
    }



    /**
     * Fungsi ini digunakan untuk membuat record baru di tabel Petugas dan, jika ada,
     * menambahkan data terkait di tabel PetugasRolePlh serta menangani upload file.
     *
     * @param array $data - Data yang dibutuhkan untuk membuat record Petugas.
     * @return Petugas - Mengembalikan instance Petugas yang baru dibuat.
     *
     * Exception handling:
     * - Menangani error saat upload file.
     * - Menangani error saat menyimpan data ke database.
     */
    public function create(array $data) {
        try {
            // Validasi tgl_sk tidak boleh di masa lalu
            if (strtotime($data['tgl_sk']) < time()) {
                throw new \Exception('Tanggal SK tidak boleh di masa lalu.');
            }

            // Validasi exp_sk tidak boleh kurang dari tgl_sk
            if (strtotime($data['exp_sk']) < strtotime($data['tgl_sk'])) {
                throw new \Exception('Tanggal Exp SK tidak boleh kurang dari Tanggal SK.');
            }

            // Cek benturan dengan data yang sudah ada
            $conflict = $this->petugasModel::where('group_id', $data['group_id'])
                ->where('unit_kerja_id', $data['unit_kerja_id'])
                ->where(function($query) use ($data) {
                    $query->whereBetween('tgl_sk', [$data['tgl_sk'], $data['exp_sk']])
                          ->orWhereHas('role_plh', function($subQuery) use ($data) {
                              $subQuery->whereBetween('exp_sk', [$data['tgl_sk'], $data['exp_sk']]);
                          });
                })
                ->exists();

            if ($conflict) {
                throw new \Exception('Benturan dengan data yang sudah ada. Harap periksa rentang tanggal tgl_sk dan exp_sk.');
            }

            // Inisialisasi fileId menjadi null
            $fileId = null;

            // Cek apakah ada file yang diupload dan apakah file tersebut adalah instance dari UploadedFile
            if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
                // Coba upload file menggunakan fileRepository
                $fileId = $this->fileRepository->uploadFile($fileId, $data['file']);
            }

            // Membuat record baru di tabel Petugas dengan data yang diberikan
            $petugas = $this->petugasModel::create([
                'nip' => $data['nip'],
                'nama' => $data['nama'],
                'unit_kerja_id' => $data['unit_kerja_id'],
                'group_id' => $data['group_id'],
                'no_sk' => $data['no_sk'],
                'tgl_sk' => $data['tgl_sk'],
                'file_id' => $fileId, // Gunakan fileId jika ada file yang diupload
            ]);

            // Cek apakah exp_sk tersedia dalam data yang diberikan, jika ya, buat record PetugasRolePlh
            if (isset($data['exp_sk'])) {
                $petugas->role_plh()->create([
                    'exp_sk' => $data['exp_sk'],
                ]);
            }

            // Mengembalikan object Petugas yang baru saja dibuat
            return $petugas;

        } catch (\Exception $e) {
            // Tangani exception dan berikan respons error atau log jika diperlukan
            \Log::error('Error saat membuat Petugas: ' . $e->getMessage());
            throw new \Exception('Terjadi kesalahan saat membuat petugas.'); // Exception yang dilempar untuk menangani error
        }
    }

    // public function create(array $data) {
    //     try {
    //         // Inisialisasi fileId menjadi null
    //         $fileId = null;

    //         // Cek apakah ada file yang diupload dan apakah file tersebut adalah instance dari UploadedFile
    //         if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
    //             // Coba upload file menggunakan fileRepository
    //             $fileId = $this->fileRepository->uploadFile($fileId, $data['file']);
    //         }

    //         // Membuat record baru di tabel Petugas dengan data yang diberikan
    //         $petugas = $this->petugasModel::create([
    //             'nip' => $data['nip'],
    //             'nama' => $data['nama'],
    //             'unit_kerja_id' => $data['unit_kerja_id'],
    //             'group_id' => $data['group_id'],
    //             'no_sk' => $data['no_sk'],
    //             'tgl_sk' => $data['tgl_sk'],
    //             'file_id' => $fileId, // Gunakan fileId jika ada file yang diupload
    //         ]);

    //         // Cek apakah exp_sk tersedia dalam data yang diberikan, jika ya, buat record PetugasRolePlh
    //         if (isset($data['exp_sk'])) {
    //             $petugas->role_plh()->create([
    //                 'exp_sk' => $data['exp_sk'],
    //             ]);
    //         }

    //         // Mengembalikan object Petugas yang baru saja dibuat
    //         return $petugas;

    //     } catch (\Exception $e) {
    //         // Tangani exception dan berikan respons error atau log jika diperlukan
    //         \Log::error('Error saat membuat Petugas: ' . $e->getMessage());
    //         throw new \Exception('Terjadi kesalahan saat membuat petugas.'); // Exception yang dilempar untuk menangani error
    //     }
    // }

    /**
     * Fungsi ini digunakan untuk mengupdate record Petugas berdasarkan ID yang diberikan.
     * Juga menangani update relasi exp_sk di model PetugasRolePlh dan upload file jika diperlukan.
     *
     * @param array $data - Data yang dibutuhkan untuk mengupdate Petugas.
     * @param string $id - ID dari Petugas yang akan diupdate.
     * @return Petugas - Mengembalikan instance Petugas yang telah diperbarui.
     *
     * Exception handling:
     * - Menangani error saat upload file.
     * - Menangani error saat menyimpan data ke database.
     * - Menangani foreign key constraint violation.
     */
    public function update(array $data, string $id) {
        DB::beginTransaction();

        try {
            // Fetch the existing petugas record or fail if not found
            $petugasRecord = $this->petugasModel::with('role_plh')->findOrFail($id);

            // Update basic Petugas fields
            $petugasRecord->update([
                'nip' => $data['nip'],
                'nama' => $data['nama'],
                'unit_kerja_id' => $data['unit_kerja_id'],
                'group_id' => $data['group_id'],
                'no_sk' => $data['no_sk'],
                'tgl_sk' => $data['tgl_sk'],
            ]);

            // Handle file upload (if a new file is uploaded)
            if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
                $fileId = $petugasRecord->file_id;
                $fileId = $this->fileRepository->uploadFile($fileId, $data['file']); // Upload file and update file_id
                $petugasRecord->update(['file_id' => $fileId]); // Update the file_id in the Petugas record
            }

            // Handle PetugasRolePlh relationship update or create if exp_sk is provided
            if (isset($data['exp_sk'])) {
                $petugasRecord->role_plh()->updateOrCreate(
                    ['petugas_id' => $petugasRecord->id], // Condition for update or creation
                    ['exp_sk' => $data['exp_sk']] // Data to update or insert
                );
            }

            // Commit the transaction
            DB::commit();

            // Return the updated Petugas record
            return $petugasRecord;

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of any errors
            \Log::error('Error updating Petugas: ' . $e->getMessage());
            throw new \Exception('Failed to update Petugas.'); // Custom error message
        }
    }



    /**
     * Menghapus record Petugas beserta relasinya.
     *
     * @param string $id ID dari record Petugas yang akan dihapus
     * @return bool Mengembalikan true jika penghapusan berhasil, false jika gagal
     * @throws \Exception Jika terjadi kesalahan saat penghapusan
     */
    public function delete(string $id) {
        DB::beginTransaction();

        try {
            // Cari record Petugas berdasarkan ID atau lempar exception jika tidak ditemukan
            $petugasRecord = $this->petugasModel::with(['role_plh'])->findOrFail($id);

            //cek file id
            $fileId = $petugasRecord->file_id;

            // Hapus relasi di tabel PetugasRolePlh
            $petugasRecord->role_plh()->delete();

            // Hapus record Petugas
            $deleted = $petugasRecord->delete();

            // Hapus jika ada file
            if ($fileId) {
                $this->fileRepository->deleteFile($fileId);
            }

            // Komit transaksi jika berhasil
            DB::commit();

            return $deleted;
        }
        catch (ModelNotFoundException $e) {
            DB::rollBack();
            // Tangani kasus jika record tidak ditemukan
            throw new \Exception('Petugas tidak ditemukan: ' . $e->getMessage());
        }
        catch (QueryException $e) {
            DB::rollBack();
            // Tangani kesalahan terkait query database
            throw new \Exception('Kesalahan database: ' . $e->getMessage());
        }
        catch (\Exception $e) {
            DB::rollBack();
            // Tangani semua kesalahan lainnya
            throw new \Exception('Gagal menghapus Petugas: ' . $e->getMessage());
        }
    }


}
