<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FAQListResource extends JsonResource
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
		return [
            'id'			=> (string) $this->id,
            'question'		=> $this->title ? (string) $this->title : '',
            'description' 	=> $this->description ? (string) $this->description : '',
        ];
    }
}