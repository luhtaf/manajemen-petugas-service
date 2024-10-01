<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class GantiPelelang extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'permohonan.tbl_ganti_pelelang';

    protected $fillable = [
        'permohonan_id',
        'petugas_lama_id',
        'petugas_baru_id',
        'nip_lama',
        'nip_baru',
        'alasan',
        'surat_pergantian_id',
        'tgl_dibuat',
        'status',
        'created_by',
        'updated_by',
    ];

    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class, 'permohonan_id');
    }

    public function petugasLama()
    {
        return $this->belongsTo(Petugas::class, 'petugas_lama_id');
    }

    public function petugasBaru()
    {
        return $this->belongsTo(Petugas::class, 'petugas_baru_id');
    }

    public function suratPergantian()
    {
        return $this->belongsTo(File::class, 'surat_pergantian_id');
    }
}
