<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Permohonan extends Model
{
    protected $table = 'permohonan.tbl_permohonan';
    use HasFactory, HasUuids;
	 
    protected $fillable=[
        'nama_pemohon',
        'nomor_registrasi',
        'tanggal_kirim'
    ];

    public function jenisLelang():BelongsTo
    {
        return $this->belongsTo(RefJenisLelang::class, 'jenis_lelang_id');
    }

    public function lotLelang(): HasMany
    {
        return $this->hasMany(LotLelang::class, 'permohonan_id');
    }

    public function pelelang():HasOne
    {
        return $this->hasOne(Pelelang::class, 'permohonan_id');
    }


   // public function getTotalNilaiLimitAttribute()
    // {
    //     return $this->lotLelang()->sum('nilai_limit');
    // }

    // public function getJumlahLotLelangAttribute()
    // {
    //     return $this->lotLelang()->count();
    // }

    // public function getJumlahHariKalender(){
    //     if ($this->tanggal_kirim) {
    //         // Parse tanggal_kirim as a Carbon instance
    //         $tanggalKirim = Carbon::parse($this->tanggal_kirim);
    //         // Calculate the difference in days between today and tanggal_kirim
    //         return Carbon::now()->diffInDays($tanggalKirim);
    //     }

    //     // If tanggal_kirim is null, return 0 or null depending on your preference
    //     return null;
    // }

    // public function getAllDataAttribute()
    // {
    //     return array_merge($this->attributes, [
    //         'jenis_lelang' => $this->jenisLelang->toArray(),
    //         'jumlah_lot_lelang' => $this->jumlah_lot_lelang,
    //         'jumlah_hari_kalender' => $this->jumlah_hari_kalender,
    //         'pelelang' => $this->pelelang->toArray(),
    //         'total_nilai_limit' => $this->total_nilai_limit,
    //     ]);
    // }

}
