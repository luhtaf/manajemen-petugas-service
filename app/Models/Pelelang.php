<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pelelang extends Model
{
    protected $table = 'permohonan.tbl_pelelang';
    use HasFactory;
    use HasUuids;
    protected $fillable = [
        'permohonan_id',
        'petugas_id',
        'nip',
        'nama_pelelang',
        'tgl_dibuat',
        'status'
    ];
}
