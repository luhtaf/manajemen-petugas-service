<?php

namespace App\Repositories;

use App\Models\Alasan;

class AlasanRepository
{
    protected $alasanModel;

    public function __construct(Alasan $alasanModel) {
        $this->alasanModel = $alasanModel;
    }

    public function getAll()
    {
        $query=$this->alasanModel::query();
        $search = 'PENGGANTI PELELANG';
        $query->where(function($q) use ($search) {
            $q->where('tipe', 'like', "%$search%");
        });
        $alasan = $query->get();
        return $alasan;
    }

}
