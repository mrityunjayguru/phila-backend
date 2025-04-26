<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offers';

    protected $fillable = [
		'title','image','address','description',
		'place_id','type','nearest_stop','website','phone_number','ticket_booking_url',
		'mon_sat','sunnday','adult_charges','student_charges',
		'start_date','end_date','status'
	];
}