<?php

namespace App\Http\Resources;

use App\Models\Faq;
use Illuminate\Http\Resources\Json\JsonResource;

class FAQTopicListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		
		$list = FAQListResource::collection(Faq::where(['topic'=>$this->id, 'status'=>'active'])->orderBy('priority', 'ASC')->get());
		
        // return parent::toArray($request);
		return [
            'id'	=> (string) $this->id,
            'title'	=> $this->title ? (string) $this->title : '',
            'list'	=> $list ? $list : [],
        ];
    }
}