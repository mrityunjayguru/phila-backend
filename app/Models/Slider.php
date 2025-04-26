<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
	protected $table = 'slider';
    protected $fillable = ['title','device','slug','status'];
}
