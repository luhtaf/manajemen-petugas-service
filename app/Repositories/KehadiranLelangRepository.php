<?php

namespace App\Repositories;

use App\Models\VirtualAttendanceRequest;

class KehadiranLelangRepository
{
    public function createRequest($data)
    {
        returnKehadiranLelang::create($data);
    }

    public function getRequestsBySeller($sellerId)
    {
        returnKehadiranLelang::where('seller_id', $sellerId)->get();
    }

    public function approveRequest($requestId)
    {
        $request =KehadiranLelang::findOrFail($requestId);
        $request->update(['status' => 'approved']);
        return $request;
    }

    public function rejectRequest($requestId)
    {
        $request =KehadiranLelang::findOrFail($requestId);
        $request->update(['status' => 'rejected']);
        return $request;
    }
}