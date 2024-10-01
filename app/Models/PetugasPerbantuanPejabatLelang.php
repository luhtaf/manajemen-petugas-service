<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasPerbantuanPejabatLelang extends Model {
    protected $table = 'permohonan.tbl_petugas_perbantuan_pejabat_lelang';
    use HasFactory, HasUuids;
    protected $fillable = [
        'file_nd_id',
        'file_kesediaan_id',
        'petugas_id'
    ];

    public function file_nd() {
        return $this->belongsTo(File::class,'file_nd_id');
    }

    public function file_kesediaan() {
        return $this->belongsTo(File::class,'file_kesediaan_id');
    }
}
