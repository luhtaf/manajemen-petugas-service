<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class LotLelang extends Model
{
    protected $table = 'permohonan.tbl_lot_lelang';
    use HasFactory;
    use HasUuids;
    protected $fillable = [
        'id',
        'nilai_limit'
    ];
}
