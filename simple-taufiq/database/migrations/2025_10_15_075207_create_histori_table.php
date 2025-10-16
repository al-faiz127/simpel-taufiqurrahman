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
        Schema::create('histori', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->nullable()->index();
            $table->foreignUlid('bangkom_id')->nullable()->index();
            $table->string('oleh')->nullable();
            $table->string('sebelum')->nullable();
            $table->string('sesudah')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('waktu')->useCurrent()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histori');
    }
};
