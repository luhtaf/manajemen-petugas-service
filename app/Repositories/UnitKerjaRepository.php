<?php

namespace App\Repositories;

use App\Models\UnitKerja;
class UnitKerjaRepository
{
    protected $unitKerjaModel, $user;

    public function __construct(UnitKerja $unitKerjaModel, UserRepository $user) {
        $this->user = $user;
        $this->unitKerjaModel = $unitKerjaModel;

    }

    public function getAll() {
        $user=$this->user->current_user();
        return $this->unitKerjaModel::select('id','nama')
                // ->where('id', $user->unit_kerja_id)
                ->where('parent_id',$user->unit_kerja_id)
                ->get();
    }

    public function getAllUnitIdsUnderKanwil($kanwilId) {
        // Find the kanwil unit_kerja
        $kanwil = $this->unitKerjaModel->with('kpknl')->findOrFail($kanwilId);

        // // Start by collecting the current kanwil ID
        $unitKerjaIds = collect([$kanwil->id]);

        // // Recursively get all child units
        $unitKerjaIds = $this->collectChildren($kanwil, $unitKerjaIds);

        return $unitKerjaIds;
    }

    protected function collectChildren($unitKerja, &$unitKerjaIds) {
        // Check if children exists and is iterable
        $children = $unitKerja->kpknl ?? []; // Default to an empty array if null

        foreach ($children as $kpknl) {
            $unitKerjaIds->push($kpknl->id); // Add the child ID
            $this->collectChildren($kpknl, $unitKerjaIds); // Recursively add its children
        }
        return $unitKerjaIds;
    }
}
