<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worldcities extends Model
{ 
    use HasFactory;
    protected $fillable = [
        'name', 
        'latitude',
        'longitude',
        'timezone',       
    ];
}
