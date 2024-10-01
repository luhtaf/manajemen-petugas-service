<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KehadiranLelang extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'auction_id',
        'reason',
        'letter_path',
        'status'
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
}
