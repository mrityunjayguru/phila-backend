<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distance extends Model
{
    use SoftDeletes;
	protected $table = 'distance';
    protected $fillable = ['stop','distance_in_km','status'];
    
}