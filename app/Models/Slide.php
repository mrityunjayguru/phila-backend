<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
	protected $table = 'slides';
    protected $fillable = ['slider_id','priority','title','tagline','image','is_clickable','redirect_to','button_text','description','status'];
}
