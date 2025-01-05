<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_karyawan', 'id');
    }
    public function divisi()
    {
        return $this->belongsTo(Jabatan::class, 'divisi_id', 'id');
    }
    public function punishments()
    {
        return $this->hasMany(RewardPunishment::class, 'id_karyawan');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nama',
        'jabatan',
        'status',
        'status_kerja',
        'nik',
        'umur',
        'telepon',
        'alamat',
        'username',
        'password',
        'divisi',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
