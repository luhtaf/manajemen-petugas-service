<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetugasPerbantuanPejabatLelangResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id, // Only the 'id' from Petugas,
            'nama' => $this->nama,
            'nip' => $this->nip,
            'unit_kerja_id' => $this->unit_kerja_id,
            'unit_kerja' => $this->unit_kerja,
            'group_id' => $this->group_id,
            'group' => $this->group,
            'no_sk' => $this->no_sk,
            'tgl_sk' => $this->tgl_sk,
            'file_nd_id' => $this->perbantuan_pejabat_lelang ? $this->perbantuan_pejabat_lelang->file_nd_id : null,
            'file_nd' => $this->perbantuan_pejabat_lelang ? $this->perbantuan_pejabat_lelang->file_nd : null,
            'file_kesediaan_id' => $this->perbantuan_pejabat_lelang ? $this->perbantuan_pejabat_lelang->file_kesediaan_id : null,
            'file_kesediaan' => $this->perbantuan_pejabat_lelang ? $this->perbantuan_pejabat_lelang->file_kesediaan : null,
        ];
    }

}
