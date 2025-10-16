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
        Schema::create('widyaiswara', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nip')->unique()->nullable();
            $table->string('nama')->nullable();
            $table->string('satker')->nullable();
            $table->string('telpon')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('alamat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('widyaiswara');
    }
};
