<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $fillable = [
        'nama',
        'jabatan_id',
        'alamat',
        'no_telp',
        'nik',
        'user_id',
    ];

    function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
