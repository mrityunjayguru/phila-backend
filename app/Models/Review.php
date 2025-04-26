<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
	protected $table = 'reviews';
	protected $guarded = [];
    //protected $fillable = ['user_id','relation_id','rating','review','status'];
	
	// Resview USER
	public function user(){
		return $this->belongsTo(User::class, 'user_id', 'id');
    }
}