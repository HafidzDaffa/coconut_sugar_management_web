<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cabangs', function (Blueprint $table) {
            $table->string('no_badan_hukum', 100)->nullable()->after('nama_cabang');
            $table->date('tanggal_berdiri')->nullable()->after('no_badan_hukum');
        });
    }

    public function down(): void
    {
        Schema::table('cabangs', function (Blueprint $table) {
            $table->dropColumn(['no_badan_hukum', 'tanggal_berdiri']);
        });
    }
};
