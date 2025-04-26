<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StopListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		$is_stop_image = '1';
		if($this->priority == '28'){
			$is_stop_image = '0';
		}
		
        // return parent::toArray($request);
		return [
            'id'                    => (string) $this->id,
            'image'                 => $this->image ? asset($this->image) : asset(config('constants.DEFAULT_THUMBNAIL')),
			'is_stop_image'         => $is_stop_image,
            'stop_image'            => $this->stop_image ? asset($this->stop_image) : asset(config('constants.DEFAULT_THUMBNAIL')),
            'title'            		=> $this->title ? (string)$this->title : '',
            'stop_no'            	=> $this->priority ? (string)$this->priority : '',
            'color'            		=> $this->color ? (string)$this->color : '',
            'time'					=> $this->time ? (string)$this->time : '',
            'description'			=> $this->description ? (string)$this->description : '',
			'latitude'				=> $this->latitude ? (string)$this->latitude : '',
            'longitude'				=> $this->longitude ? (string)$this->longitude : '',
        ];
    }
}