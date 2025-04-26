<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FirebaseToken extends Model
{
    protected $table = 'firebase_tokens';
    
    protected $fillable = ['token','bus_arrivel_notification','other_notification','date','status'];

}