<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resign extends Model
{
    use HasFactory;
    protected $fillable = ['id_karyawan', 'surat', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_karyawan');
    }
}
