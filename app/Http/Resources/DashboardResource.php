<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Notification;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		$is_notifications = Notification::where('is_read', 0)->where('token', $request->header('fcm-token'))->get()->count();
		
		// return parent::toArray($request);
        return [
			'notification_count'	=> $is_notifications ? (string)$is_notifications : '0',
			'slider'				=> $this->slider ? $this->slider : [],
			'stops'					=> $this->stops ? $this->stops : [],
        ];
    }
}