<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            if (! Schema::hasColumn('karyawans', 'nama')) {
                $table->string('nama', 100)->nullable();
            }
            if (! Schema::hasColumn('karyawans', 'jabatan')) {
                $table->string('jabatan', 50)->nullable();
            }
            if (! Schema::hasColumn('karyawans', 'gaji_pokok')) {
                $table->decimal('gaji_pokok', 12, 2)->default(0);
            }
            if (! Schema::hasColumn('karyawans', 'lembur')) {
                $table->decimal('lembur', 12, 2)->default(0);
            }
            if (! Schema::hasColumn('karyawans', 'pinjaman')) {
                $table->decimal('pinjaman', 12, 2)->default(0);
            }
            if (! Schema::hasColumn('karyawans', 'gaji_bersih')) {
                $table->decimal('gaji_bersih', 12, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $columns = collect([
                'nama', 'jabatan', 'gaji_pokok', 'lembur', 'pinjaman', 'gaji_bersih',
            ])->filter(fn (string $column) => Schema::hasColumn('karyawans', $column))->all();

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};