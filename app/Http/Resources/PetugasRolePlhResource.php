<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetugasRolePlhResource extends JsonResource
{
    /**
     * Memberikan return, tipe json atau array sesuai format
     *
     * @return array atau json
     */
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof Collection) {
            // Logic for when it's a collection (findAll)
            return $this->collectionToArray($this->resource);
        } else {
            // Logic for a single item (findOne)
            return $this->singleItemToArray();
        }
    }

    // Custom format untuk single item
    private function singleItemToArray()
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'nip' => $this->nip,
            'unit_kerja_id' => $this->unit_kerja_id,
            'unit_kerja' => $this->unit_kerja ?: null, // Concise check
            'group_id' => $this->group_id,
            'group' => $this->group ?: null, // Concise check
            'exp_sk' => $this->role_plh ? $this->role_plh->exp_sk : null,
            'no_sk' => $this->no_sk,
            'tgl_sk' => $this->tgl_sk,
            'file_id' => $this->file_id,
            'file' => $this->file ?: null, // Concise check
        ];
    }


    /**
     * @OA\Schema(
     *     schema="PetugasRolePlhResource",
     *     type="object",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="nama", type="string"),
     *     @OA\Property(property="nip", type="string"),
     *     @OA\Property(property="unit_kerja_id", type="integer"),
     *     @OA\Property(property="unit_kerja", type="object", ref="#/components/schemas/UnitKerjaResource"),
     *     @OA\Property(property="group_id", type="integer"),
     *     @OA\Property(property="group", type="object", ref="#/components/schemas/GroupResource"),
     *     @OA\Property(property="exp_sk", type="string", format="date-time", nullable=true)
     * )
     */
    private function collectionToArray(Collection $collection)
    {
        return $collection->map(function ($item) {
            return [
                'id' => $this->id, // Only the 'id' from Petugas,
                'nama'=> $this->nama,
                'nip'=> $this->nip,
                'unit_kerja_id' => $this->unit_kerja_id,
                'unit_kerja' => $this->unit_kerja ?: null, // Concise check
                'group_id' => $this->group_id,
                'group' => $this->group ?: null, // Concise check
                'exp_sk'=> $this->role_plh? $this->role_plh->exp_sk : null
            ];
        })->toArray();
    }

}
