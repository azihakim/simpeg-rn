<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penugasan extends Model
{
    use HasFactory;
    protected $table = 'penugasans';
    protected $fillable = ['user_id', 'surat', 'keterangan'];

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
