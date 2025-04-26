<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Stop;
use App\Http\Resources\StopListResource;

class PlaceResource extends JsonResource
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
		$monday 		= $this->monday ? (string)$this->monday : '';
		$tuesday 		= $this->tuesday ? (string)$this->tuesday : '';
		$wednesday 		= $this->wednesday ? (string)$this->wednesday : '';
		$thursday 		= $this->thursday ? (string)$this->thursday : '';
		$friday 		= $this->friday ? (string)$this->friday : '';
		$saturday 		= $this->saturday ? (string)$this->saturday : '';
		$sunday 		= $this->sunday ? (string)$this->sunday : '';
		$google_business_url = $this->google_business_url ? (string)$this->google_business_url : '';
		$nearest_stop 	= null;
		$stop 	 		= Stop::where('id', $this->nearest_stop)->first();
		if($stop){
			$nearest_stop = ['id'=>(string)$stop->id, 'title'=>(string)$stop->title, 'stop_no'=>(string)$stop->priority];
			$nearest_stop = new StopListResource($stop);
		}
		if(empty($google_business_url)){
			if($this->latitude && $this->longitude){
				$google_business_url = 'https://www.google.com/maps?q='. $this->latitude .','. $this->longitude .'&z=17&hl=en';
			}
		}
		return [
            'id'                    => (string) $this->id,
            'image'                 => $this->image ? asset($this->image) : asset(config('constants.DEFAULT_THUMBNAIL')),
            'title'            		=> $this->title ? (string)$this->title : '',
            'address'				=> $this->address ? (string)$this->address : '',
            'description'			=> $this->description ? (string)$this->description : '',
            'is_charges'			=> $this->is_charges ? (string)$this->is_charges : '0',
            'charges'				=> $this->charges ? (string)$this->charges : '',
            'website'				=> $this->website ? (string)$this->website : '',
            'phone_number'			=> $this->phone_number ? (string)$this->phone_number : '',
            'ticket_booking_url'	=> $this->ticket_booking_url ? (string)$this->ticket_booking_url : '',
            'latitude'				=> $this->latitude ? (string)$this->latitude : '',
            'longitude'				=> $this->longitude ? (string)$this->longitude : '',
            'google_business_url'	=> $google_business_url,
            'is_hours'				=> $this->is_hours ? (string)$this->is_hours : '0',
            'hours'					=> [
										['title'=>'Monday', 'value'=>(string)$monday],
										['title'=>'Tuesday', 'value'=>(string)$tuesday],
										['title'=>'Wednesday', 'value'=>(string)$wednesday],
										['title'=>'Thursday', 'value'=>(string)$thursday],
										['title'=>'Friday', 'value'=>(string)$friday],
										['title'=>'Saturday', 'value'=>(string)$saturday],
										['title'=>'Sunday', 'value'=>(string)$sunday]
									],
            'nearest_stop'			=> $nearest_stop,
        ];
    }
}