<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
	protected $table = 'user_info';
    protected $fillable = ['title','role','status'];
}
