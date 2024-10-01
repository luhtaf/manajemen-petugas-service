<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class File extends Model
{
    protected $table = 'core.core_media';
    use HasFactory, HasUuids;

    protected $fillable = [
        'file_name',
        'folder',
        'public',
        'hidden',
        'version',
    ];
}

