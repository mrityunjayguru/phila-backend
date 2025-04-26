<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SlideListResource extends JsonResource
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
            'id'                => (string) $this->id,
            'title'             => $this->title ? $this->title : '',
            'tagline'			=> $this->tagline ? $this->tagline : '',
            'image'             => $this->image ? asset($this->image) : asset(config('constants.DEFAULT_SLIDE_IMAGE')),
            'is_clickable'		=> $this->is_clickable ? $this->is_clickable : '0',
            'redirect_to'		=> $this->redirect_to ? $this->redirect_to : '',
            'button_text'		=> $this->button_text ? $this->button_text : '',
            'description'		=> $this->description ? $this->description : '',
        ];
    }
}
