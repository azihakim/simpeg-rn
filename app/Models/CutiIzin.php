<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiIzin extends Model
{
    use HasFactory;
    protected $table = 'cuti_izins';
    protected $fillable = [
        'id_karyawan',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'status',
    ];
    function user()
    {
        return $this->belongsTo(User::class, 'id_karyawan');
    }
}
