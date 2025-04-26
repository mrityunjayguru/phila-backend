<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timing extends Model
{
    protected $table = 'timings';

    protected $fillable = ['first_bus','last_bus','frequency','fairmount_first_bus','fairmount_last_bus','fairmount_frequency'];
}