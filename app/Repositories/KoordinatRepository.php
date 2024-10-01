<?php

namespace App\Repositories;

use App\Models\Koordinat;

class KoordinatRepository
{
    protected $koordinatModel;

    public function __construct(Koordinat $koordinatModel) {
        $this->koordinatModel = $koordinatModel;
    }

    public function createRequest($data)
    {
        returnKehadiranLelang::create($data);
    }

    public function getByBarangId(string $id)
    {
        $query=$this->koordinatModel::query();
        $query->where(function($q) {
            $q->where('barang_id', $id);
        });
        $koordinat = $query->get();
        return $koordinat;
    }

}
