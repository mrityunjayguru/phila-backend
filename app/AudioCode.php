<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AudioCode extends Model
{
    protected $table = 'audio_codes';
	
    protected $fillable = ['page_id','ticket_number','bus','end_date','customer_name','customer_number','customer_email','status'];
    //
}
