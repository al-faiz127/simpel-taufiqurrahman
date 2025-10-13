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
        Schema::create('bangkom', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->foreignUlid('instansi_id')->nullable()->index();
            $table->string('unit')->nullable();
            $table->string('kegiatan')->nullable();
            $table->foreignUlid('jenis_pelatihan_id')->nullable()->index();
            $table->foreignUlid('bentuk_pelatihan_id')->nullable()->index();
            $table->foreignUlid('sasaran_id')->nullable()->index();
            $table->date('mulai');
            $table->date('selesai');
            $table->string('tempat')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kuota')->nullable();
            $table->string('panitia')->nullable();
            $table->string('tlpnpanitia')->nullable();
            $table->string('narasumber')->nullable();
            $table->string('materi')->nullable();
            $table->string('jam')->nullable();
            $table->string('deskripsi')->nullable();
            $table->string('persyaratan')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bangkom');
    }
};
