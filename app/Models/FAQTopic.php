<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAQTopic extends Model
{
    protected $table = 'faq_topics';

    protected $fillable = ['title','priority','status'];
}