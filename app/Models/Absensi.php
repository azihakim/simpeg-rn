<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';

    protected $fillable = [
        'tanggal',
        'waktu',
        'latitude',
        'longitude',
        'status',
        'id_karyawan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_karyawan', 'id');
    }
}
