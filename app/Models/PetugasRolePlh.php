<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasRolePlh extends Model {
    protected $table = 'permohonan.tbl_petugas_role_plh';
    use HasFactory, HasUuids;
    protected $fillable = [
        'exp_sk',
        'petugas_id'
    ];
}
