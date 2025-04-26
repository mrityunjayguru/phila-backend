<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationSettingResource extends JsonResource
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
	  
      return[
        'token'						=> (string) $this->token,
        'bus_arrivel_notification'	=> $this->bus_arrivel_notification ? (string) $this->bus_arrivel_notification : '',
        'other_notification'		=> $this->other_notification ? (string) $this->other_notification : '',
      ];
    }
}
