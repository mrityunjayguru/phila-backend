<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB,Validator,Auth;

use App\Models\Helpers\CommonHelper;
use App\Models\Stop;
use App\Models\Place;
use App\Models\Distance;
use App\Models\Bus;
use App\Http\Resources\StopResource;
use App\Http\Resources\StopListResource;
use App\Http\Resources\PlaceListResource;
use App\Http\Resources\TrackBusList;

class StopController extends BaseController
{
	use CommonHelper;
	
    /**
	* Display a listing of the resource.
	* @return \Illuminate\Http\Response
	*/
    public function index(Request $request){
		
		try{
			$query = Stop::where(['status'=> 'active']);
			//$query->where(['type'=> $request->type]);
			$data = $query->orderBy('priority', 'ASC')->get();
			// dd($data);
			if(count($data) > 0){
				return $this->sendArrayResponse(StopListResource::collection($data),trans('customer_api.data_found_success'));
			}
			return $this->sendArrayResponse('',trans('customer_api.data_found_empty'));
			
		}catch (\Exception $e) {
			return $this->sendError('', $e->getMessage());
		}
	}
	
	// STOP DETAILS
    public function show($id = null) {
      try{
        $query = Stop::where('id', $id)->first();
        if(!empty($query)){
			
			$bus = DB::table("buses")->select("buses.*",DB::raw("6371 * acos(cos(radians(" . $query->latitude . ")) * cos(radians(buses.latitude)) * cos(radians(buses.longitude) - radians(" . $query->longitude . ")) + sin(radians(" .$query->latitude. ")) * sin(radians(buses.latitude))) AS distance"))
				->where("buses.last_visited_stop", "<", $query->priority)->where("buses.status", "active")->where("buses.live_status", "online")->orderBy("distance", "ASC")->where("buses.deleted_at", null)->first();
			
			if($bus){
				$stop_gap = $query->priority - $bus->last_visited_stop;
				
				if($query->priority == $bus->last_visited_stop){ 
					$query->next_bus = date('H:i');
				}
				else if($stop_gap === -11){
					$query->next_bus = $this->distance($query->latitude, $query->longitude, $bus->latitude, $bus->longitude, 'K');
				}else{
					// Find from database
					$query->next_bus = $this->new_distance($query->priority, $bus->last_visited_stop, $query->latitude, $query->longitude, $bus->latitude, $bus->longitude, 'K');
				}
			}else{
				$query->next_bus = 'N/A';
			}
			
			$query->nearest_places 		= PlaceListResource::collection(Place::where(['status'=> 'active', 'nearest_stop'=>$query->id])->whereIn('type', ['dining', 'shopping'])->get());
			$query->nearest_landmarks 	= PlaceListResource::collection(Place::where(['status'=> 'active', 'nearest_stop'=>$query->id])->whereIn('type', ['landmark', 'attraction'])->get());
			$query->buses 				= TrackBusList::collection(Bus::where(['status'=>'active'])->whereIn("buses.device_type", ["Bus","Trolley-Blue-Route"])->where("buses.deleted_at", null)->get());
			
			return $this->sendResponse(new StopResource($query), trans('customer_api.data_found_success'));
		}
		return $this->sendResponse('', trans('customer_api.data_found_empty'));
        
      }catch (\Exception $e) { 
		return $this->sendError('', $e->getMessage());
      }
    }
	
	
	static function distance($lat1, $lon1, $lat2, $lon2, $unit) {
		if(empty($lat1) || empty($lon1)){
			return 'N/A';
		}
		if(empty($lat2) || empty($lon2)){
			return 'N/A';
		}
		
		if ($lat1 == $lat2){
			return date('H:i');
		}
		else {
			$theta = $lon1 - $lon2;
			$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
			$dist = acos($dist);
			$dist = rad2deg($dist);
			$miles = $dist * 60 * 1.1515;
			$unit = strtoupper($unit);

			if ($unit == "K") {
			  $miles = ($miles * 1.609344);
			} else if ($unit == "N") {
			  $miles = ($miles * 0.8684);
			}
			
			//return $miles;
			$miles = ($miles * 5);
			$date = date("Y-m-d H:i:s");
			$currentDate = strtotime($date);
			$futureDate = $currentDate+(60*$miles);
			return $formatDate = date("H:i", $futureDate);
		}
	}
	
	
	static function new_distance($stop_number, $bus_visited_stop, $lat1, $lon1, $lat2, $lon2, $unit) {
		
		date_default_timezone_set("America/New_York");
		//date_default_timezone_set("Asia/Kolkata");
		
		if(empty($bus_visited_stop)){ return 'N/A'; }
		if($stop_number == $bus_visited_stop){ return date('H:i'); }
		
		$stop_gap = $stop_number - $bus_visited_stop;
		
		if($stop_gap === -11){
			if ($lat1 == $lat2){
				return date('H:i');
			}
			$theta = $lon1 - $lon2;
			$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
			$dist = acos($dist);
			$dist = rad2deg($dist);
			$miles = $dist * 60 * 1.1515;
			$unit = strtoupper($unit);

			if ($unit == "K") {
			  $miles = ($miles * 1.609344);
			} else if ($unit == "N") {
			  $miles = ($miles * 0.8684);
			}
			$miles = ($miles * 5);
		}else{
			
			$data = Distance::where('stop', $bus_visited_stop.':'.$stop_number)->first();
			if($data){
				$miles 				= ($data->distance_in_km * 5);
				$date 				= date("Y-m-d H:i:s");
				$currentDate 		= strtotime($date);
				$futureDate 		= $currentDate+(60*$miles);
				return $formatDate  = date("H:i", $futureDate);
			}
			return date("H:i");
			exit;
		}
		
		$date = date("Y-m-d H:i:s");
		$currentDate = strtotime($date);
		$futureDate = $currentDate+(60*$miles);
		return $formatDate = date("H:i", $futureDate);
	}
	
	/**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
	*/
    public function routs(Request $request){
		
		try{
			$query = Stop::where(['status'=> 'active'])->where(['type'=> $request->type]);
			
			if($request->type == 'tour'){
				$query->orWhere(['for_type'=> 'tour']);
			}else if($request->type == 'fairmount_park_loop'){
				$query->orWhere(['for_type'=> 'fairmount_park_loop']);
			}
			
			$data = $query->orderBy('priority', 'ASC')->get();
			if(count($data) > 0){
				return $this->sendArrayResponse(StopListResource::collection($data),trans('customer_api.data_found_success'));
			}
			return $this->sendArrayResponse('',trans('customer_api.data_found_empty'));
			
		}catch (\Exception $e) {
			return $this->sendError('', $e->getMessage());
		}
	}
	
	/**
	* Display a listing of map routs.
	* @return \Illuminate\Http\Response
	*/
    public function mapRouts(Request $request){
		
		try{
			$data = CommonHelper::mapRouts($request->type);
			if(count($data) > 0){
				return $this->sendArrayResponse($data, trans('customer_api.data_found_success'));
			}
			return $this->sendArrayResponse([], trans('customer_api.data_found_empty'));
			
		}catch (\Exception $e) {
			return $this->sendError('', $e->getMessage());
		}
	}
}