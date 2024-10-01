<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\RoleKanwil\Media;
use App\Http\Controllers\Api\V1\RoleKanwil\GantiPelelang;
use App\Http\Controllers\Api\V1\RoleKanwil\Petugas\RolePlh;
use App\Http\Controllers\Api\V1\RoleKanwil\Petugas\PerbantuanPejabatLelang;
use App\Http\Controllers\Api\V1\RoleKanwil\Ref;
use App\Http\Controllers\Api\V1\RoleKanwil\PermohonanPelelang;


Route::prefix('v1')->group(function () {
    Route::middleware('jwt.auth')->group(function () {
        Route::prefix('manajemen_petugas')->group(function () {
            //PLI-42
            Route::get('petugas/role_plh',[RolePlh\Get::class,'getPetugas']);
            Route::get('petugas/role_plh/{petugas_id}',[RolePlh\GetById::class,'findPetugas']);
            Route::post('petugas/role_plh',[RolePlh\Post::class,'addPetugas']);
            Route::put('petugas/role_plh/{petugas_id}',[RolePlh\Put::class,'updatePetugas']);
            Route::delete('petugas/role_plh/{petugas_id}',[RolePlh\DeleteById::class,'deletePetugas']);

            //PLI-43
            Route::get('petugas/pejabat_perbantuan/{petugas_id}',[PerbantuanPejabatLelang\GetById::class,'findPetugas']);
            Route::post('petugas/pejabat_perbantuan',[PerbantuanPejabatLelang\Post::class,'perbantuanPelelang']);


            //PLI-44
            Route::get('ganti_pelelang/{permohonan_id}',[GantiPelelang\GetById::class,'getHistoryPelelang']);
            Route::post('ganti_pelelang',[GantiPelelang\Post::class,'changePelelang']);

            Route::get('permohonan_ganti_pelelang',[PermohonanPelelang\Get::class,'getPermohonanLelang']);
            Route::get('permohonan_ganti_pelelang/{permohonan_id}',[PermohonanPelelang\GetById::class,'permohonanDetail']);
            //Ref
            Route::get('ref_kanwil',[Ref\GetUnitKerja::class,'getUnitKerja']);
            Route::get('ref_role',[Ref\GetRole::class,'getRole']);
            Route::get('ref_alasan',[Ref\GetAlasan::class,'getAlasan']);
            Route::get('ref_pelelang',[Ref\GetPelelang::class,'getPelelang']);


            Route::get('download',[Media\DownloadMedia::class,'download']);
        });
    });
});
