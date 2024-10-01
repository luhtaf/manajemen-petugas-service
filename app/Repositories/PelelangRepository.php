<?php

namespace App\Repositories;

use App\Models\Pelelang;
use App\Models\Petugas;

class PelelangRepository
{
    protected $pelelangModel, $petugasModel, $user, $unitKerjaRepository;

    public function __construct(Pelelang $pelelangModel,Petugas $petugasModel, UserRepository $user, UnitKerjaRepository $unitKerjaRepository) {
        $this->user = $user;
        $this->pelelangModel = $pelelangModel;
        $this->petugasModel = $petugasModel;
        $this->unitKerjaRepository= $unitKerjaRepository;
    }

    // public function getPetugasLelangAll($size)
    // {
    //     $user=$this->user->current_user();
    //     $unitKerjaIds = $this->unitKerjaRepository->getAllUnitIdsUnderKanwil($this->user->unit_kerja_id);

    //     // Check if any unit kerja IDs were found
    //     if (empty($unitKerjaIds)) {
    //         // Return empty pagination if no unit kerja IDs found
    //         return $this->petugasModel::whereRaw('1 = 0')->paginate($size); // Return empty pagination
    //     }

    //     // Start the query
    //     $query = $this->petugasModel::query();

    //     // Add the whereIn clause to filter by unit kerja IDs
    //     $query->whereIn('unit_kerja_id', $unitKerjaIds);

    //     // Search condition
    //     $search = '985f1bdb-361f-44fa-989f-fda21099ea7e';
    //     $query->where(function($q) use ($search) {
    //         $q->where('group_id', 'like', "%$search%");
    //     });

    //     // Handle pagination size
    //     $allowedPageSizes = [5, 10, 25, 50, 100];
    //     $perPage = in_array($size, $allowedPageSizes) ? $size : 5; // Default to 5 if invalid

    //     // Execute the query and paginate results
    //     $pelelang = $query->latest()->paginate($perPage);
    //     return $pelelang;
    // }

    public function getPetugasLelangAll($size = null) {
        $user = $this->user->current_user();
        $unitKerjaIds = $this->unitKerjaRepository->getAllUnitIdsUnderKanwil($user->unit_kerja_id);

        // Check if any unit kerja IDs were found
        if (empty($unitKerjaIds)) {
            // Return empty collection if no unit kerja IDs found
            return $this->petugasModel::whereRaw('1 = 0')->get(); // Return empty collection
        }

        // Start the query
        $query = $this->petugasModel::query();

        // Add the whereIn clause to filter by unit kerja IDs
        $query->whereIn('unit_kerja_id', $unitKerjaIds);

        // Search condition
        $search = '985f1bdb-361f-44fa-989f-fda21099ea7e';
        $query->where(function($q) use ($search) {
            $q->where('group_id', 'like', "%$search%");
        });

        // Check if pagination size is provided, otherwise get all
        if ($size) {
            // Handle pagination size
            $allowedPageSizes = [5, 10, 25, 50, 100];
            $perPage = in_array($size, $allowedPageSizes) ? $size : 5; // Default to 5 if invalid
            return $query->latest()->paginate($perPage);
        } else {
            // Get all records without pagination
            return $query->latest()->get();
        }
    }



}
