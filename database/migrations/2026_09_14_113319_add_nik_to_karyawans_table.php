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
        if (! Schema::hasColumn('karyawans', 'nik')) {
            Schema::table('karyawans', function (Blueprint $table) {
                $table->string('nik')->nullable()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('karyawans', 'nik')) {
            Schema::table('karyawans', function (Blueprint $table) {
                $table->dropColumn('nik');
            });
        }
    }
};