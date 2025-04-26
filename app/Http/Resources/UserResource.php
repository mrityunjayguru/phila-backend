<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'            		=> (string)$this->id,
            'name'          		=> $this->name,
            'profile_image'			=> $this->profile_image ? (string) asset($this->profile_image) : asset(config('constants.DEFAULT_THUMBNAIL')),
            //'cover_image'			=> $this->cover_image ? (string) asset($this->cover_image) : asset(config('constants.DEFAULT_THUMBNAIL')),
            'email'         		=> $this->email,
            'country_code'  		=> $this->country_code,
            'phone_number'  		=> $this->phone_number,
            'gender'        		=> $this->gender ? $this->gender : '',
            'dob'           		=> $this->dob ? date('Y-m-d', strtotime($this->dob)) : '',
            'user_type'  			=> $this->user_type ? $this->user_type : '',
            'status'        		=> $this->status,
        ];
    }
}
