<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Latlong extends Model
{
    protected $fillable = ['location', 'latitude', 'longitude'];
}
