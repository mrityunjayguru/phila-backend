<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Timing;

class StopResource extends JsonResource
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
		$first_bus = '';
		$last_bus  = '';
		$frequency  = '';
		$next_bus = date('h:s');
		$timing	= Timing::where('id', 1)->first();
        
		if($timing){
			$seconds = strtotime($this->time) - strtotime('00:00:00');
            // dd($this->time);
			
			$first_bus = $this->time ? date("H:i", (strtotime(date($timing->first_bus)) + $seconds)) : '';
			$last_bus  = $this->time ? date("H:i", (strtotime(date($timing->last_bus)) + $seconds)) : '';
			$frequency  = $timing->frequency ? $timing->frequency : '';
			
			if($this->type == 'fairmount_park_loop' || $this->for_type == 'fairmount_park_loop'){
				$first_bus = $this->time ? date("H:i", (strtotime(date($timing->fairmount_first_bus)) + $seconds)) : '';
				$last_bus  = $this->time ? date("H:i", (strtotime(date($timing->fairmount_last_bus)) + $seconds)) : '';
				$frequency = $timing->fairmount_frequency ? $timing->fairmount_frequency : '';
			}
		}
		
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
            'time'            		=> $this->time ? (string)$this->time : '',
            'latitude'				=> $this->latitude ? (string)$this->latitude : '',
            'longitude'				=> $this->longitude ? (string)$this->longitude : '',
            'description'			=> $this->description ? (string)$this->description : '',
            'bus_timings'           => [['First Bus'=>(string)$first_bus],['Next Bus'=>(string)$this->next_bus],['Last Bus'=>(string)$last_bus],['Frequency'=>(string)$frequency]],
            'nearest_places'		=> $this->nearest_places ? $this->nearest_places : [],
            'nearest_landmarks'		=> $this->nearest_landmarks ? $this->nearest_landmarks : [],
            'buses'					=> $this->buses ? $this->buses : [],
        ];
    }
}