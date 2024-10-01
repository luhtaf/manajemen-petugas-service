<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Petugas extends Model {
    protected $table = 'permohonan.tbl_petugas';
    use HasFactory, HasUuids;
    protected $fillable = [
        'nip',
        'nama',
        'unit_kerja_id',
        'group_id',
        'no_sk',
        'tgl_sk',
        'file_id',
    ];

    public function group() {
        return $this->belongsTo(Role::class, 'group_id');
    }

    public function unit_kerja() {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function file() {
        return $this->belongsTo(File::class,'file_id');
    }

    public function perbantuan_pejabat_lelang() {
        return $this->hasOne(PetugasPerbantuanPejabatLelang::class,'petugas_id');
    }

    public function role_plh() {
        return $this->hasOne(PetugasRolePlh::class,'petugas_id');
    }
}
