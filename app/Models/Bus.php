<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bus extends Model
{
    use SoftDeletes;
	protected $table = 'buses';
    protected $fillable = ['imei_number','device_id','device_type','title','last_update','live_status','last_visited_stop','latitude','longitude','status','security','audio_available'];
    
}