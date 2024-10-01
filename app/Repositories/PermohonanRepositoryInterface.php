<?php

namespace App\Repositories;

interface PermohonanRepositoryInterface
{
    public function getById($id);

  //  public function getPaginated($perPage);

    public function getTotalNilaiLimit($permohonan);

    public function getJumlahLotLelang($permohonan);

    public function getJumlahHariKalender($permohonan);

    public function getAllData($permohonan);

    public function getDetailData($id);
}
