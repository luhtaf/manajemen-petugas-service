<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    /**
     * @OA\Schema(
     *     schema="GroupResource",
     *     type="object",
     *     required={"code", "name"},
     *     @OA\Property(property="id", type="integer", format="int64", description="Unique identifier for the group"),
     *     @OA\Property(property="code", type="string", description="Code of the group"),
     *     @OA\Property(property="name", type="string", description="Name of the group"),
     *     @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the record was created"),
     *     @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the record was last updated")
     * )
     */

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
