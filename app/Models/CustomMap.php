<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomMap extends Model
{
    protected $table = 'custom_map';

    protected $fillable = ['image','status'];
}