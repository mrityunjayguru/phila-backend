<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Audio extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'page_id',
        'title',
        'priority',
        'image',
        'file_path',
        'latitude',
        'longitude',
        'languages','description','status','show_icon','audio_status','is_in_queue','proximity','angle', 'tolerance',
    ];
}