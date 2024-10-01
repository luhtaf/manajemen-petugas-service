<?php

namespace App\Http\Controllers\Api\V1\RoleKanwil\Media;

use App\Http\Controllers\Controller;
use App\Repositories\FileRepository;
use App\Http\Resources\PetugasRolePlhResource;
use App\Http\Requests\DownloadMediaRequest;

class DownloadMedia extends Controller {

    protected $fileRepository;

    public function __construct(FileRepository $fileRepository) {
        $this->fileRepository= $fileRepository;
    }

    public function download(DownloadMediaRequest $request)
    {
        try {
            // Since validation is handled in the request, we can just retrieve the validated data
            $filename = $request->validated()['file_id'];
            // return $filename;
            // Attempt to download the file using the repository
            $file = $this->fileRepository->downloadFile($filename, 'lelang');

            if (!$file) {
                throw new \Exception('File not found.', 404);
            }

            return $file;

        } catch (\Exception $e) {
            // Catch and return custom error responses
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'code' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }

}
