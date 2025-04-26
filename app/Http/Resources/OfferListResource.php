<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\Place;

class OfferListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		$title 		= $this->title ? (string)$this->title : 'Untitled';
		$image 		= $this->image ? asset($this->image) : asset(config('constants.DEFAULT_THUMBNAIL'));
		$place		= Place::where('id', $this->place_id)->first();
		if($place){
			$image = $place->image ? asset($place->image) : asset(config('constants.DEFAULT_THUMBNAIL'));
			$title = $place->title ? $place->title : 'Untitled';
		}
		
        // return parent::toArray($request);
		return [
            'id'                    => (string) $this->id,
            'image'                 => (string)$image,
            'title'            		=> (string)$title,
            'description'			=> $this->description ? (string)$this->description : '',
            'reference_id'			=> $this->place_id ? (string)$this->place_id : '0',
            'is_redirect'			=> $this->website ? (string)1 : '0',
            'redirect_to'			=> $this->website ? (string)$this->website : '',
            
        ];
    }
}