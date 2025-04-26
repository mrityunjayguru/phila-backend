<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandingPage extends Model
{
    use SoftDeletes;

    public $table = 'landing_pages';
    protected $fillable = [
        'title',
        'image',
        'route_length',
        'route_time',
        'number_of_stops',
        'description',
        'status',
        'priority',
        'is_code_dependecy',
    ];
}