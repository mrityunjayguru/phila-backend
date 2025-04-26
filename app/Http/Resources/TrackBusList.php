<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrackBusList extends JsonResource
{
	/**
	* Transform the resource into an array.
	* @param  \Illuminate\Http\Request  $request
	* @return array
	*/
    public function toArray($request)
    {
		return [
            'deviceId'		=> $this->device_id ? (string)$this->device_id : '',
            'device_type'	=> $this->device_type ? (string)$this->device_type : '',
            'distance'		=> $this->distance ? (string)$this->distance : '',
            'title'			=> $this->title ? (string)$this->title : '',
            'status'		=> $this->live_status ? (string)$this->live_status : '',
            'lastUpdate'	=> $this->last_update ? (string)$this->last_update : '',
            'deviceId'		=> $this->device_id ? (string)$this->device_id : '',
            'latitude'		=> $this->latitude ? (string)$this->latitude : '',
            'longitude'		=> $this->longitude ? (string)$this->longitude : '',
            //'time'		=> $this->time ? (string)$this->time : date('h:i A'),
            'time'			=> date('h:i A'),
            'minutes'		=> '20 MIN',
            'is_show'		=> $this->live_status == 'online' ? (string)1 : (string)0,
        ];
    }
}