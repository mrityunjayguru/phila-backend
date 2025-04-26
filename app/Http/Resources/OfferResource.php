<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class OfferResource extends JsonResource
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
            'id'                    => (string) $this->id,
            'image'                 => $this->image ? asset($this->image) : asset(config('constants.DEFAULT_THUMBNAIL')),
            'title'            		=> $this->title ? (string)$this->title : '',
            'address'				=> $this->address ? (string)$this->address : '',
            'description'			=> $this->description ? (string)$this->description : '',
            'adult_charges'			=> $this->adult_charges ? (string)$this->adult_charges : '0.00',
            'student_charges'		=> $this->student_charges ? (string)$this->student_charges : '0.00',
            'website'				=> $this->website ? (string)$this->website : '',
            'phone_number'			=> $this->phone_number ? (string)$this->phone_number : '',
            'ticket_booking_url'	=> $this->ticket_booking_url ? (string)$this->ticket_booking_url : '',
            'latitude'				=> $this->latitude ? (string)$this->latitude : '',
            'longitude'				=> $this->longitude ? (string)$this->longitude : '',
            'hours'					=> [['title'=>'Mon - Saturday', 'value'=>'10:00 am - 11:00 pm'], ['title'=>'Sun', 'value'=>'10:00 am - 2:00 am']],
            'nearest_stop'			=> ['id'=>'1', 'title'=>'Stop 1', 'latitude'=>'', 'longitude'=>''],
        ];
    }
}