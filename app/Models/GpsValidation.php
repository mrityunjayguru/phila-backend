<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GpsValidation extends Model
{
    protected $table = 'gps_validations';

    protected $fillable = ['gps_enabled'];
}