<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SampleAudio extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'languages',
        'audio',
        'status',
    ];
}