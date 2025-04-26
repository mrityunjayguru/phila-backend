<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
	protected $table = 'stops';
    protected $fillable = ['title','image','stop_image','priority','type','for_type','color','time','description','latitude','longitude','status'];
    
}
