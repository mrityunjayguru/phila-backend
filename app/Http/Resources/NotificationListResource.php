<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Offer;
use App\Models\Place;

class NotificationListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      // return parent::toArray($request);
	  
	  $title = $this->title ? (string) $this->title : '';
	  $image = '';
	  if($this->type_id){
		  $title = Place::where('id','=',$this->type_id)->pluck('title')->first();
		  $image = Place::where('id','=',$this->type_id)->pluck('image')->first();
	  }
	  
      return[
        'notification_id'   => (string) $this->id,
        'title'             => $title ? (string) $title : '',
        'message'           => $this->message ? (string) $this->message : '',
        'type'              => $this->type ? (string) $this->type : '',
        'type_id'           => $this->type_id ? (string) $this->type_id : '',
        'image'           	=> $image ? (string) asset($image) : asset(config('constants.DEFAULT_THUMBNAIL')),
        'date_time'         => (string) date('Y-m-d H:i:s', strtotime($this->created_at)),
      ];
    }
}
