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
        Schema::create('permohonan.tbl_petugas_role_plh', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('petugas_id')->references('id')->on('permohonan.tbl_petugas')->onDelete('cascade')->constrained();
            $table->timestamp('exp_sk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan.tbl_petugas_role_plh');
    }
};
