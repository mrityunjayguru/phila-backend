<?php

namespace App\Http\Resources;
use App\Models\City;
use App\Models\Country;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		$city           = City::where(['id'=>$this->city_id])->first();
        $country        = Country::where(['id'=>$this->country_id])->first();
        $city_name      = $city ? (string) $this->city->name : '';
        $country_name   = $country ? (string) $this->country->name : '';
		
        // return parent::toArray($request);
        return [
            'id'        		=> (string)$this->id,
            'city_id'			=> $this->city_id ? (string)$this->city_id : '',
            'city_name'			=> $this->city_name ? (string)$this->city_name : '',
            'country_id'		=> $this->country_id ? (string)$this->country_id : '',
            'country_name'		=> $this->country_name ? (string)$this->country_name : '',
            'address_type'		=> $this->address_type ? (string)$this->address_type : '',
            'address'   		=> $this->address ? (string)$this->address : '',
            'postal_code'		=> $this->postal_code ? (string)$this->postal_code : '',
            'latitude'			=> $this->latitude ? (string)$this->latitude : '',
            'longitude'			=> $this->longitude ? (string)$this->longitude : '',
        ];
    }
}
