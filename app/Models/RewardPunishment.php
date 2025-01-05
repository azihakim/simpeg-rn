<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardPunishment extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_karyawan',
        'jenis',
        'keterangan',
        'tanggal',
        'surat_punishment',
        'reward',
        'status'
    ];

    function karyawan()
    {
        return $this->belongsTo(User::class, 'id_karyawan');
    }
}
