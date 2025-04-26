<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
	protected $table = 'tickets';
	
    protected $fillable = ['ticket_number','bus','end_date','customer_name','customer_number','customer_email','status'];
}