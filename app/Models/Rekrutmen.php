<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekrutmen extends Model
{
    use HasFactory;
    protected $fillable = ['id_pelamar', 'id_lowongan', 'status', 'file'];

    function user()
    {
        return $this->belongsTo(User::class, 'id_pelamar');
    }

    function lowongan()
    {
        return $this->belongsTo(Lowongan::class, 'id_lowongan');
    }
}
