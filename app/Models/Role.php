<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Role extends Model
{
    protected $table = 'core.core_group';
    use HasFactory;
    use HasUuids;
    protected $fillable = [
        'code',
        'name',
    ];
}
