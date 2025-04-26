<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    
    protected $fillable = ['custom_order_id','user_id', 'address_id', 'address','item_count','quantity','tax','discount','delivery_fee','item_total','grand_total','owner_id','shipping_date','shipping_address','coupon_id','delivery_date','tracking_id','payment_status','payment_method_id','order_status','goods_received','order_date','status'];

    // Get User detail
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    // Get address detail
    public function address(){
        return $this->belongsTo(Address::class,'address_id');
    }

    // Get order items
    public function order_items(){
        return $this->hasMany(OrderItem::class, 'order_id','id');
    }

    // Get payment detail
    public function payment(){
        return $this->belongsTo(Payment::class,'address_id');
    }

    // Get coupon detail
    public function coupon(){
        return $this->belongsTo(Coupon::class,'coupon_id');
    }
  
}