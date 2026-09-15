<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            if (Schema::hasColumn('karyawans', 'no_whatsapp')) {
                $table->string('no_whatsapp', 20)->nullable()->change();
            }
            if (Schema::hasColumn('karyawans', 'email')) {
                $table->string('email', 100)->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            if (Schema::hasColumn('karyawans', 'no_whatsapp')) {
                $table->string('no_whatsapp', 20)->nullable(false)->change();
            }
            if (Schema::hasColumn('karyawans', 'email')) {
                $table->string('email', 100)->nullable(false)->change();
            }
        });
    }
};