<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model
{
    use HasFactory;

    protected $fillable = ['jabatan', 'status', 'deskripsi'];

    public function divisi()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan', 'id');
    }
}
