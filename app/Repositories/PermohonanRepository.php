<?php

namespace App\Repositories;

use App\Models\Permohonan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PermohonanRepository implements PermohonanRepositoryInterface {

	public User | null $user;
    protected $model;

	public function __construct(Permohonan $model) {
        $this->model = $model;
        $this->user = Auth::guard()->user();
    }

	private function generateIndonesianPhoneNumber() {
        // Prefix 08 untuk nomor Indonesia
        $prefix = '08';

        // Membuat nomor acak sepanjang 10 digit
        $randomNumber = mt_rand(100000000, 999999999);

        // Menggabungkan prefix dan nomor acak
        return $prefix . $randomNumber;
    }

    private function generateIndonesianCompanyName() {
        // Array of random company name parts
        $prefixes = ['PT', 'CV', 'UD', 'Koperasi', 'Perusahaan Dagang'];
        $words = ['Surya', 'Mitra', 'Karya', 'Indo', 'Jaya', 'Global', 'Sentosa', 'Makmur', 'Sejahtera', 'Nusantara', 'Mega', 'Utama'];

        // Generate random company name
        $prefix = $prefixes[array_rand($prefixes)];
        $word1 = $words[array_rand($words)];
        $word2 = $words[array_rand($words)];

        // Ensure different words are selected
        while ($word1 === $word2) {
            $word2 = $words[array_rand($words)];
        }

        return $prefix . ' ' . $word1 . ' ' . $word2;
    }

	public function getAll() {
        return $this->model->all();
    }

    public function getsitapajak() {
        return $this->model->where('jenis_lelang_id', 'f918046c-c749-42de-ae0a-c37e1921ce6c')
                           ->where('kategori_lelang_id', 'f2e2a1fe-f86a-11ed-b3e2-5620a0c2ec5a')
                           ->get();
    }

    public function getuuht() {
        return $this->model->where(function($query) {
            $query->where('jenis_lelang_id', 'c4640f86-c96d-42ad-bd52-6f4535d7d500')
                  ->where('kategori_lelang_id', 'f2e2a1fe-f86a-11ed-b3e2-5620a0c2ec5b');
        })->orWhere(function($query) {
            $query->where('jenis_lelang_id', 'daa74622-f86e-11ed-b3e2-5620a0c2ec5a')
                  ->where('kategori_lelang_id', 'f2e2a1fe-f86a-11ed-b3e2-5620a0c2ec5a');
        })->get();
    }

	public function getById($id) {
        return $this->model::find($id);
    }

    // public function getPaginated($perPage) {
    //     return $this->model::latest()->paginate($perPage);
    // }

    public function getPaginated($perPage) {
        return $this->model::whereHas('pelelang') // Filters to only Permohonan with related Pelelang
                          ->latest()
                          ->paginate($perPage);
    }



   public function getTotalNilaiLimit($permohonan) {
        return $permohonan->lotLelang()->sum('nilai_limit');
    }

    public function getJumlahLotLelang($permohonan) {
        return $permohonan->lotLelang()->count();
    }

    public function getJumlahHariKalender($permohonan) {
        if ($permohonan->tanggal_kirim) {
            $tanggalKirim = Carbon::parse($permohonan->tanggal_kirim);
            return Carbon::now()->diffInDays($tanggalKirim);
        }

        return null;
    }

    public function getAllData($permohonan) {
        return array_merge($permohonan->attributesToArray(), [
            'jenis_lelang' => $permohonan->jenisLelang,
            'jumlah_lot_lelang' => $this->getJumlahLotLelang($permohonan),
            'jumlah_hari_kalender' => $this->getJumlahHariKalender($permohonan),
            'pelelang' => $permohonan->pelelang,
            'total_nilai_limit' => $this->getTotalNilaiLimit($permohonan),
            'no_hp'=> $this->generateIndonesianPhoneNumber(),
            'nama_entitas'=>$this->generateIndonesianCompanyName()
        ]);
    }

    public function getDetailData($id) {
        $permohonan = $this->model::with('pelelang')->findOrFail($id);
        return array_merge($permohonan->attributesToArray(), [
            'jenis_lelang' => $permohonan->jenisLelang,
            'jumlah_lot_lelang' => $this->getJumlahLotLelang($permohonan),
            'jumlah_hari_kalender' => $this->getJumlahHariKalender($permohonan),
            'total_nilai_limit' => $this->getTotalNilaiLimit($permohonan),
            'pelelang' => $permohonan->pelelang,
        ]);
    }

}
