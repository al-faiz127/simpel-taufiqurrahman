<?php

use App\Models\instansi;
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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignUlid('instansi_id')->after('id')->nullable()->index();
            $table->string('phone')->after('instansi_id')->nullable();
            $table->string('satuan')->after('phone')->nullable();
            $table->string('username')->after('satuan')->unique()->nullable();
            $table->timestamp('verified_at')->nullable()->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['instansi_id', 'phone', 'satuan', 'username', 'verified_at']);
        });
    }
};
