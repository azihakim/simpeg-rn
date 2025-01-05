<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromosiDemosi extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_karyawan',
        'surat_rekomendasi',
        'divisi_lama_id',
        'divisi_baru_id',
        'status',
        'jenis'
    ];

    function karyawan()
    {
        return $this->belongsTo(User::class, 'id_karyawan');
    }
    public function divisiLama()
    {
        return $this->belongsTo(Jabatan::class, 'divisi_lama_id');
    }

    // Relasi untuk divisi baru
    public function divisiBaru()
    {
        return $this->belongsTo(Jabatan::class, 'divisi_baru_id');
    }
}
