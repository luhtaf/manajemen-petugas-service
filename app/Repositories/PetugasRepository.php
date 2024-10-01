<?php

namespace App\Repositories;
use Illuminate\Http\UploadedFile;
use App\Models\Petugas;
use Illuminate\Support\Facades\DB;

class PetugasRepository implements PetugasRepositoryInterface {

    protected $fileRepository;
    protected $petugasModel;

    public function __construct(FileRepository $fileRepository, Petugas $petugasModel) {
        $this->fileRepository = $fileRepository;
        $this->petugasModel = $petugasModel;
    }

    public function getById($id) {
        return $this->petugasModel::with(['unit_kerja:id,nama', 'group:id,name','file'])->find($id);
    }

    public function getAll($perPage) {
        return $this->petugasModel::select('id','nama', 'nip','unit_kerja_id','group_id') // Select only nama and nip fields
        ->with(['unit_kerja:id,nama', 'group:id,name']) // Ensure to select only the necessary fields from related models
        ->paginate($perPage);
    }

    public function create(array $data) {
        $fileId=null; if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
            $filename = $this->fileRepository->uploadFile($fileId,$data['file']);
            $fileId = $filename; // Store the filename or file ID
        }

        $fileNdId=null;
        if (isset($data['file_nd']) && $data['file_nd'] instanceof UploadedFile) {
            $filename = $this->fileRepository->uploadFile($fileNdId,$data['file_nd']);
            $fileNdId = $filename; // Store the filename or file ID
        }

        $fileKesediaanId=null;
        if (isset($data['file_kesediaan']) && $data['file_kesediaan'] instanceof UploadedFile) {
            $filename = $this->fileRepository->uploadFile($fileKesediaanId,$data['file_kesediaan']);
            $fileKesediaanId = $filename; // Store the filename or file ID
        }

        // Create a new GantiPelelang record
        return $this->petugasModel::create([
            'nip' => $data['nip'],
            'nama' => $data['nama'],
            'unit_kerja_id' => $data['unit_kerja_id'],
            'group_id' => $data['group_id'],
            'no_sk' => $data['no_sk'],
            'tgl_sk' => $data['tgl_sk'],
            'exp_sk' => $data['exp_sk'],
            'file_id' => $fileId, // Use the filename or file ID here
            'file_nd_id' => $fileNdId,
            'file_kesediaan_id'=> $fileKesediaanId
        ]);
    }


    public function update(array $data, string $id) {
        DB::beginTransaction();

        try {
            // Find the petugas record or throw a 404 error
            $petugasRecord = $this->petugasModel::findOrFail($id);

            // Handle file upload if a new file is provided
            $fileId = $petugasRecord->file_id;
            if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
                // Upload the new file and store the file ID
                $fileId = $this->fileRepository->uploadFile($data['file'], $petugasRecord->file_id);
            }

            $fileNdId=$petugasRecord->file_nd_id;
            if (isset($data['file_nd']) && $data['file_nd'] instanceof UploadedFile) {
                $filename = $this->fileRepository->uploadFile($fileNdId,$data['file_nd']);
                $fileNdId = $filename; // Store the filename or file ID
            }

            $fileKesediaanId=$petugasRecord->file_kesediaan_id;
            if (isset($data['file_kesediaan']) && $data['file_kesediaan'] instanceof UploadedFile) {
                $filename = $this->fileRepository->uploadFile($fileKesediaanId,$data['file_kesediaan']);
                $fileKesediaanId = $filename; // Store the filename or file ID
            }

            // Perform the update on the petugas record
            $petugasRecord->update([
                'nip' => $data['nip'],
                'nama' => $data['nama'],
                'unit_kerja_id' => $data['unit_kerja_id'],
                'group_id' => $data['group_id'],
                'no_sk' => $data['no_sk'],
                'tgl_sk' => $data['tgl_sk'],
                'exp_sk' => $data['exp_sk'],
                'file_id' => $fileId, // Updated or existing file ID
                'file_nd_id' => $fileNdId,
                'file_kesediaan_id'=> $fileKesediaanId
            ]);

            DB::commit();

            return $petugasRecord;
        } catch (\PDOException $e) {
            DB::rollBack();

            // Check if the error is a foreign key violation (SQLSTATE code for FK violation is 23503)
            if ($e->getCode() === '23503') {
                throw new Exception('Foreign key constraint violation: ' . $e->getMessage());
            }

            // Rethrow any other database-related exceptions
            throw new Exception('Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            // Handle any other exceptions
            throw new Exception('Update failed: ' . $e->getMessage());
        }
    }

    public function delete(string $id): bool {
        DB::beginTransaction();

        try {
            // Find the petugas record by ID
            $petugasRecord = $this->petugasModel::findOrFail($id);

            // Get the file ID associated with the petugas record
            $fileId = $petugasRecord->file_id;
            $fileNdId=$petugasRecord->file_nd_id;
            $fileKesediaanId=$petugasRecord->file_kesediaan_id;


            // Delete the petugas record first to remove the foreign key constraint
            $petugasRecord->delete();

            // If there's an associated file, delete it using the FileRepository
            if ($fileId) {
                $this->fileRepository->deleteFile($fileId);
            }


            if ($fileNdId) {
                $this->fileRepository->deleteFile($fileNdId);
            }


            if ($fileKesediaanId) {
                $this->fileRepository->deleteFile($fileKesediaanId);
            }

            // Commit the transaction if everything is successful
            DB::commit();

            return true;
        } catch (Exception $e) {
            // Rollback the transaction on failure
            DB::rollBack();
            throw new Exception('Petugas delete failed, transaction rolled back.');
        }
    }
}
