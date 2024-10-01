<?php

namespace App\Repositories;

use App\Models\Alasan;
use App\Models\Permohonan;
use App\Models\Petugas;
use App\Models\Pelelang;
use App\Models\GantiPelelang;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use App\Repositories\FileRepository;

class GantiPelelangRepository implements GantiPelelangRepositoryInterface
{
    protected $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    /**
     * Handle the process of changing the pelelang and creating a record.
     *
     * @param array $data
     * @return GantiPelelang
     */
    public function handleChangePelelang(array $data)
    {
        // Retrieve the existing permohonan
        $permohonan = Permohonan::findOrFail($data['permohonan_id']);

        // Lookup the old pelelang
        $pelelangLama = $permohonan->pelelang()->first(); // Assuming only one pelelang per permohonan
        if (!$pelelangLama) {
            throw new \Exception('No pelelang found for the given permohonan.');
        }

        $nipLama = $pelelangLama->nip;
        $petugasLama=$pelelangLama->petugas_id;
        $petugasBaru = Petugas::findOrFail($data['petugas_baru_id']);
        $nipBaru=$petugasBaru->nip;
        // Handle file upload using FileRepository
        $suratPergantianId = null;
        if (isset($data['surat_pergantian']) && $data['surat_pergantian'] instanceof UploadedFile) {
            $filename = $this->fileRepository->uploadFile(null,$data['surat_pergantian']);
            $suratPergantianId = $filename; // Store the filename or file ID
        }

        // Update the pelelang with the new petugas_id
        $pelelangLama->update([
            'petugas_id' => $data['petugas_baru_id'], // Update to new petugas
            'nama_pelelang' => $petugasBaru->nama,
            'nip' => $nipBaru
        ]);

        $alasanRecord = Alasan::findOrFail($data['id_alasan']);
        $alasan = $alasanRecord->alasan;

        // Create a new GantiPelelang record
        return GantiPelelang::create([
            'permohonan_id' => $data['permohonan_id'],
            'petugas_lama_id' => $petugasLama,
            'petugas_baru_id' => $data['petugas_baru_id'],
            'nip_lama' => $nipLama,
            'nip_baru' => $nipBaru,
            'alasan' => $alasan,
            'surat_pergantian_id' => $suratPergantianId, // Use the filename or file ID here
            'tgl_dibuat' => now(),
            'status' => true,
        ]);
    }
    public function getById($id)
    {
        return GantiPelelang::with(['petugasLama', 'petugasBaru', 'suratPergantian'])
                            ->where('permohonan_id', $id)
                            ->get();
    }
}
