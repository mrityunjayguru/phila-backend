<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
	protected $table = 'places';
    protected $fillable = [
		'type','title','image','address','description','latitude','longitude','google_business_url',
		'nearest_stop','website','phone_number','ticket_booking_url',
		'mon_sat','monday','tuesday','wednesday','thursday','friday','saturday','sunday','is_hours','is_charges','charges','status'
	];
}