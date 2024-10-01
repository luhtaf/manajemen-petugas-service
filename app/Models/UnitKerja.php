<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UnitKerja extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'permohonan.ref_unit_kerja';

    protected $fillable = [
        'nama',
        'jenis',
        'parent_id',
        'bank_id',
        'nomor_rekening',
        'alamat',
        'provinsi_id',
        'kota_id',
        'telepon',
        'kode_satker',
        'kode_risalah',
        'kode_unit',
        'additional_information',
        'created_by',
        'updated_by'
    ];

    public function kpknl()
    {
        return $this->hasMany(UnitKerja::class, 'parent_id');
    }


}
