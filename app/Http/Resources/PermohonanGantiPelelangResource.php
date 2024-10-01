<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermohonanGantiPelelangResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="PermohonanResponse",
     *     type="object",
     *     @OA\Property(property="id", type="string", format="uuid", example="2a8a1b2f-d231-4539-8e6b-5c8a3b54a73c"),
     *     @OA\Property(property="nama_pemohon", type="string", example="John Doe"),
     *     @OA\Property(property="nomor_registrasi", type="string", example="REG-12345"),
     *     @OA\Property(property="tanggal_kirim", type="string", format="date", example="2024-09-21"),
     *     @OA\Property(property="jenis_lelang", type="object",
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="nama_jenis", type="string", example="Lelang Umum")
     *     ),
     *     @OA\Property(property="jumlah_lot_lelang", type="integer", example=5),
     *     @OA\Property(property="jumlah_hari_kalender", type="integer", example=30),
     *     @OA\Property(property="pelelang", type="object",
     *         @OA\Property(property="id", type="integer", example=10),
     *         @OA\Property(property="nama", type="string", example="PT Lelang Indonesia")
     *     ),
     *     @OA\Property(property="total_nilai_limit", type="number", format="float", example=50000000.00)
     * )
     */

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
