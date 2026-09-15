<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawans';

    /**
     * Izinkan semua kolom dimasukkan ke database tanpa halangan MassAssignmentException
     */
    protected $guarded = [];
}