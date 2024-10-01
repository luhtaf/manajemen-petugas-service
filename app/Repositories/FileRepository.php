<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;
use App\Models\Alasan;
use App\Models\File;
use Illuminate\Support\Facades\DB;
use Exception;


class FileRepository implements FileRepositoryInterface {
    /**
     * Upload a file to the object storage.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string
     */

    protected $fileModel;
    public function __construct(File $fileModel) {
        $this->fileModel = $fileModel;
    }

    public function uploadFile(?string $id, UploadedFile $file, string $directory = 'lelang'): string {
        // Start a database transaction
        DB::beginTransaction();

        try {
            if ($id) {
                // Update existing file record
                $fileRecord = $this->fileModel::findOrFail($id);

                if ($fileRecord->file_name) {
                    Storage::disk('s3')->delete($directory . '/' . $fileRecord->file_name);
                }

                $fileRecord->update([
                    'file_name' => time().'_'.$file->getClientOriginalName(),
                    'folder' => $directory,
                    'public' => true,
                    'hidden' => false,
                    'version' => $fileRecord->version + 1,
                ]);
            } else {
                // Save new file record
                $fileRecord = $this->fileModel::create([
                    'file_name' => time().'_'.$file->getClientOriginalName(),
                    'folder' => $directory,
                    'public' => true,
                    'hidden' => false,
                    'version' => 0,
                ]);
            }

            // Use the file ID as the name for storage in Minio
            $filename = $fileRecord->id;

            // Attempt to upload the file to Minio (S3)
            Storage::disk('s3')->putFileAs($directory, $file, $filename);

            // If everything is successful, commit the transaction
            DB::commit();

            // Return the ID of the newly created or updated file record
            return $fileRecord->id;

        } catch (Exception $e) {
            // If anything fails, roll back the transaction
            DB::rollBack();

            // Optionally log the error
            Log::error('File upload failed: ' . $e->getMessage());

            // Rethrow the exception or handle it according to your application logic
            throw new Exception('File upload failed, transaction rolled back. Original error: ' . $e->getMessage(), 0, $e);
        }
    }


    /**
     * Download a file from the object storage.
     *
     * @param string $fileId
     * @return StreamedResponse
     */
    public function downloadFile(string $fileId): StreamedResponse {
        // Find the file record by ID
        $fileRecord = $this->fileModel::findOrFail($fileId);

        // Generate the file path from the folder and the file's ID (stored in Minio)
        $filePath = $fileRecord->folder . '/' . $fileRecord->id;

        // Get the file's MIME type (assuming PDF in this case)
        $mimeType = 'application/pdf';  // Or dynamically determine the MIME type if needed

        // Return a streamed response to display the file inline
        return Storage::disk('s3')->response($filePath, $fileRecord->file_name, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileRecord->file_name . '"'
        ]);
    }


    /**
     * Delete a file from the object storage and database.
     *
     * @param string $fileId
     * @return bool
     * @throws Exception
     */
    public function deleteFile(string $fileId): bool{
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Find the file record by ID
            $fileRecord = $this->fileModel::findOrFail($fileId);

            // Generate the file path from the folder and the file's ID (stored in Minio)
            $filePath = $fileRecord->folder . '/' . $fileRecord->id;

            // Attempt to delete the file from Minio (S3)
            Storage::disk('s3')->delete($filePath);

            // Delete the file record from the database
            $fileRecord->delete();

            // If everything is successful, commit the transaction
            DB::commit();

            return true;

        } catch (Exception $e) {
            // If anything fails, roll back the transaction
            DB::rollBack();

            // Optionally log the error
            // Log::error('File delete failed: ' . $e->getMessage());

            // Rethrow the exception or handle it according to your application logic
            throw new Exception('File delete failed, transaction rolled back. Original error: ' . $e->getMessage(), 0, $e);
        }
    }

}
