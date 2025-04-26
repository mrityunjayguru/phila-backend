<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlaceListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		$google_business_url = $this->google_business_url ? (string)$this->google_business_url : '';
		if(empty($google_business_url)){
			if($this->latitude && $this->longitude){
				$google_business_url = 'https://www.google.com/maps?q='. $this->latitude .','. $this->longitude .'&z=17&hl=en';
			}
		}
		
        // return parent::toArray($request);
		return [
            'id'                    => (string) $this->id,
            'image'                 => $this->image ? asset($this->image) : asset(config('constants.DEFAULT_THUMBNAIL')),
            'title'            		=> $this->title ? (string)$this->title : '',
            'description'			=> $this->description ? (string)$this->description : '',
			'type'            		=> $this->type ? (string)$this->type : '',
			'latitude'				=> $this->latitude ? (string)$this->latitude : '',
            'longitude'				=> $this->longitude ? (string)$this->longitude : '',
			'google_business_url'	=> $google_business_url,
        ];
    }
}