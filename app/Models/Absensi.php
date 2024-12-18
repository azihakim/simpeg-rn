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
        'user_id',
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
