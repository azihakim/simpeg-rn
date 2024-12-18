<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelamar extends Model
{
    protected $table = 'pelamars';
    protected $fillable = [
        'nama',
        'user_id',
        'alamat',
        'no_telp',
        'jenis_kelamin',
        'umur',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
