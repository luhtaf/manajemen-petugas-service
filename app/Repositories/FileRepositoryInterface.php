<?php

namespace App\Repositories;

use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface FileRepositoryInterface
{
    /**
     * Upload a file to the object storage.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string
     */
    public function uploadFile(?string $id, UploadedFile $file, string $directory = 'lelang'): string;

    /**
     * Download a file from the object storage.
     *
     * @param string $filename
     * @param string $directory
     * @return StreamedResponse
     */
    public function downloadFile(string $fileId): StreamedResponse;

    public function deleteFile(string $fileId): bool;
}

