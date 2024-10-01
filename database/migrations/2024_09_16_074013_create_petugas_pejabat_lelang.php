<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permohonan.tbl_petugas_perbantuan_pejabat_lelang', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('petugas_id')->references('id')->on('permohonan.tbl_petugas')->onDelete('cascade')->constrained();
            $table->foreignUuid('file_nd_id')->nullable()->references('id')->on('core.core_media')->onDelete('cascade')->constrained();
            $table->foreignUuid('file_kesediaan_id')->nullable()->references('id')->on('core.core_media')->onDelete('cascade')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan.tbl_petugas_perbantuan_pejabat_lelang');
    }
};
