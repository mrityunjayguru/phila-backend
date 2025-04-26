<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';
    
    protected $fillable = ['title','city_id','priority','pincode','latitude','longitude','status'];

    // GET PRODUCT DETAILS
    public function product(){
        return $this->belongsTo(Product::class, 'city_id' );
    }
}
