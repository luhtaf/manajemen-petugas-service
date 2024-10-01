<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitKerjaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    /**
     * @OA\Schema(
     *     schema="UnitKerjaResource",
     *     type="object",
     *     required={"nama", "jenis"},
     *     @OA\Property(property="id", type="integer", format="int64", description="Unique identifier for the unit kerja"),
     *     @OA\Property(property="nama", type="string", description="Name of the unit kerja"),
     *     @OA\Property(property="jenis", type="string", description="Type of the unit kerja"),
     *     @OA\Property(property="parent_id", type="integer", format="int64", description="Parent ID for hierarchical structure"),
     *     @OA\Property(property="bank_id", type="integer", format="int64", description="Associated bank ID"),
     *     @OA\Property(property="nomor_rekening", type="string", description="Bank account number"),
     *     @OA\Property(property="alamat", type="string", description="Address of the unit kerja"),
     *     @OA\Property(property="provinsi_id", type="integer", format="int64", description="Province ID"),
     *     @OA\Property(property="kota_id", type="integer", format="int64", description="City ID"),
     *     @OA\Property(property="telepon", type="string", description="Phone number"),
     *     @OA\Property(property="kode_satker", type="string", description="Satker code"),
     *     @OA\Property(property="kode_risalah", type="string", description="Risalah code"),
     *     @OA\Property(property="kode_unit", type="string", description="Unit code"),
     *     @OA\Property(property="additional_information", type="string", description="Any additional information"),
     *     @OA\Property(property="created_by", type="integer", format="int64", description="ID of the user who created this record"),
     *     @OA\Property(property="updated_by", type="integer", format="int64", description="ID of the user who last updated this record"),
     *     @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the record was created"),
     *     @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the record was last updated")
     * )
     */

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
