<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Alasan extends Model
{
    protected $connection = 'pelaksanaan';
    protected $table = 'pelaksanaan.ref_alasan';
    use HasFactory;
    use HasUuids;
    protected $fillable = [
        'tipe',
        'alasan'
    ];

    // public static function getAlasan()
    // {
    //     return self::query()
    //         ->select(DB::raw('tema AS lower_tema'))
    //         ->distinct()
    //         ->pluck('lower_tema');
    //         // ->map(function ($tema) {
    //         //     return Str::title($tema); // Capitalize each word
    //         // });
    // }
}
