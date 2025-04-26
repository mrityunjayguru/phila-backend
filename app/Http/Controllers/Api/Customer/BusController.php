<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB,Validator,Auth;

use App\Models\Ticket;
use App\Models\Bus;

use App\Http\Resources\BusResource;
use App\Http\Resources\TrackBusList;

class BusController extends BaseController
{
    /**
	* Display a listing of the resource.
	* @return \Illuminate\Http\Response
	*/
    public function index(Request $request){
		
		try{
			// Validate Ticket
			$data = Ticket::where(['status'=>'active'])->where('ticket_number', $request->ticket_number)->first();
			
			if(empty($data)){
				return $this->sendError('', 'Invalid Ticket code');
			}
			
			if(date("Y-m-d") > $data->end_date){
				return $this->sendError('', 'Ticket has been expired');
			}
			
			/*
			// GET Device Data
			$username = 'worklooper';
			$password = 'Worklooper@123';
			$url	  = 'https://libitrack.in/api/v1/index.php/getDevPos';
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			//curl_setopt($ch, CURLOPT_POST, 1);
			//curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadName);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$rest = curl_exec($ch);
			if ($rest === false){
				$rest = [];
			}else{
				$rest = json_decode($rest);
			}
			curl_close($ch);
			
			if(!empty($rest)){
				if($rest->status == 'success'){
					if(count($rest->data) > 0){
						foreach($rest->data as $list){
							
							$data = ['latitude'=>$list->latitude, 'longitude'=>$list->longitude, 'last_update'=>$list->lastUpdate, 'live_status'=>$list->status];
							$query = DB::table("stops")->select("stops.id","stops.priority",DB::raw("6371 * acos(cos(radians(" . $list->latitude . ")) * cos(radians(stops.latitude)) * cos(radians(stops.longitude) - radians(" . $list->longitude . ")) + sin(radians(" .$list->latitude. ")) * sin(radians(stops.latitude))) AS distance"))->where("stops.status", "active")->having('distance', '<', '0.2')->first();
							if($query){
								$data['last_visited_stop'] = $query->priority;
							}
							//Bus::where('device_id', $list->deviceId)->update($data);
						}
					}
				}
			}*/
			
			
			
			// For Nearby Bus Only
			if($request->latitude && $request->longitude){
				$query = DB::table("buses")->select("buses.*",DB::raw("6371 * acos(cos(radians(" . $request->latitude . ")) * cos(radians(buses.latitude)) * cos(radians(buses.longitude) - radians(" . $request->longitude . ")) + sin(radians(" .$request->latitude. ")) * sin(radians(buses.latitude))) AS distance"))
				->where("buses.status", "active")->whereIn("buses.device_type", ["Bus","Trolley-Blue-Route"])->orderBy("distance", "ASC");
			}
			// For All Live Buses
			else{
				$query = Bus::where(['status'=>'active'])->whereIn("buses.device_type", ["Bus","Trolley-Blue-Route"]);
			}
			
			$query = $query->where("buses.deleted_at", null)->get();
			return $this->sendArrayResponse(TrackBusList::collection($query),trans('customer_api.data_found_success'));
			exit;
			
			return $this->sendResponse('',trans('customer_api.data_found_empty'));
		}catch (\Exception $e) {
			return $this->sendError('', $e->getMessage());
		}
	}
	


	// STOP DETAILS
    public function show($id = null) {
      try{
        $query = Stop::where('id', $id)->first();
        if(!empty($query)){
			$query->nearest_places = PlaceListResource::collection(Place::where(['status'=> 'active', 'nearest_stop'=>$query->id])->whereIn('type', ['dining', 'shopping'])->get());
			$query->nearest_landmarks = PlaceListResource::collection(Place::where(['status'=> 'active', 'nearest_stop'=>$query->id])->whereIn('type', ['landmark', 'attraction'])->get());
			return $this->sendResponse(new StopResource($query), trans('customer_api.data_found_success'));
		}
		return $this->sendResponse('', trans('customer_api.data_found_empty'));
        
      }catch (\Exception $e) { 
		return $this->sendError('', $e->getMessage());
      }
    }
	
	/**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
	*/
    public function routs(Request $request){
		
		try{
			$query = Stop::where(['status'=> 'active']);
			
			$query->where(['type'=> $request->type]);
			$query->orWhere(['type'=> 'mix']);
			
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
			if($request->type == 'tour'){
				$data2 = [
					//['id'=>'1', 'latitude'=>'39.949758', 'longitude'=>'-75.150631'],
					//['id'=>'2', 'latitude'=>'39.954637', 'longitude'=>'-75.149528'],
					['id'=>'3', 'latitude'=>'39.954403', 'longitude'=>'-75.147986'],
					['id'=>'4', 'latitude'=>'39.953782', 'longitude'=>'-75.142860'],
					['id'=>'5', 'latitude'=>'39.951952', 'longitude'=>'-75.143335'],
					['id'=>'6', 'latitude'=>'39.952123', 'longitude'=>'-75.144772'],
					['id'=>'7', 'latitude'=>'39.952589', 'longitude'=>'-75.148486'],
					['id'=>'8', 'latitude'=>'39.952760', 'longitude'=>'-75.149934'],
					['id'=>'9', 'latitude'=>'39.953560', 'longitude'=>'-75.156270'], // Chinatown Friendship Arch
					['id'=>'8', 'latitude'=>'39.953948', 'longitude'=>'-75.159432'], // 2022 Philadelphia Auto Show - March 5-13
					['id'=>'9', 'latitude'=>'39.952947', 'longitude'=>'-75.159659'], // World Park: Orders and Perspectives
					//['id'=>'10', 'latitude'=>'39.951968', 'longitude'=>'-75.160187'],
					//['id'=>'11', 'latitude'=>'39.952387', 'longitude'=>'-75.162524'],
					//['id'=>'12', 'latitude'=>'39.953213', 'longitude'=>'-75.162524'],
					//['id'=>'13', 'latitude'=>'39.95396', 'longitude'=>'-75.16642'],
					//['id'=>'14', 'latitude'=>'39.954053', 'longitude'=>'-75.166534'],
					//['id'=>'15', 'latitude'=>'39.955013', 'longitude'=>'-75.166719'],
					//['id'=>'16', 'latitude'=>'39.95613', 'longitude'=>'-75.16792'],
					//['id'=>'17', 'latitude'=>'39.95861', 'longitude'=>'-75.17026'],
					//['id'=>'18', 'latitude'=>'39.96139', 'longitude'=>'-75.17465'],
					//['id'=>'19', 'latitude'=>'39.96744', 'longitude'=>'-75.17416'],
				];
				
				$data = [
					['id'=>'1', 'latitude'=>'39.949980', 'longitude'=>'-75.148964'],
					['id'=>'2', 'latitude'=>'39.950138', 'longitude'=>'-75.148990'],
					['id'=>'3', 'latitude'=>'39.950402', 'longitude'=>'-75.148939'],
					['id'=>'4', 'latitude'=>'39.950797', 'longitude'=>'-75.148870'],
					['id'=>'5', 'latitude'=>'39.951952', 'longitude'=>'-75.148534'],
					['id'=>'6', 'latitude'=>'39.952756', 'longitude'=>'-75.148470'],
					['id'=>'7', 'latitude'=>'39.953332', 'longitude'=>'-75.148336'],
					['id'=>'8', 'latitude'=>'39.953492', 'longitude'=>'-75.148247'],
					['id'=>'9', 'latitude'=>'39.953710', 'longitude'=>'-75.148014'],
					['id'=>'10', 'latitude'=>'39.954304', 'longitude'=>'-75.147870'],
					['id'=>'11', 'latitude'=>'39.954369', 'longitude'=>'-75.147647'],
					['id'=>'12', 'latitude'=>'39.954233', 'longitude'=>'-75.146561'],
					['id'=>'13', 'latitude'=>'39.954194', 'longitude'=>'-75.146258'],
					['id'=>'14', 'latitude'=>'39.954134', 'longitude'=>'-75.145837'],
					['id'=>'15', 'latitude'=>'39.954124', 'longitude'=>'-75.145696'],
					['id'=>'16', 'latitude'=>'39.954034', 'longitude'=>'-75.144976'],
					['id'=>'17', 'latitude'=>'39.953991', 'longitude'=>'-75.144661'],
					['id'=>'18', 'latitude'=>'39.953922', 'longitude'=>'-75.144064'],
					['id'=>'19', 'latitude'=>'39.953906', 'longitude'=>'-75.143959'],
					['id'=>'20', 'latitude'=>'39.953873', 'longitude'=>'-75.143686'],
					['id'=>'21', 'latitude'=>'39.953796', 'longitude'=>'-75.143086'],
					['id'=>'22', 'latitude'=>'39.953666', 'longitude'=>'-75.142942'],
					['id'=>'23', 'latitude'=>'39.953323', 'longitude'=>'-75.143016'],
					['id'=>'24', 'latitude'=>'39.953141', 'longitude'=>'-75.143048'],
					['id'=>'25', 'latitude'=>'39.952991', 'longitude'=>'-75.146561'],
					['id'=>'26', 'latitude'=>'39.952644', 'longitude'=>'-75.143156'],
					/*['id'=>'27', 'latitude'=>'39.952159', 'longitude'=>'-75.143261'],
					['id'=>'28', 'latitude'=>'39.951971', 'longitude'=>'-75.143521'],
					['id'=>'29', 'latitude'=>'39.952051', 'longitude'=>'-75.144183'],
					['id'=>'30', 'latitude'=>'39.952085', 'longitude'=>'-75.144419'],
					['id'=>'31', 'latitude'=>'39.952167', 'longitude'=>'-75.145076'],
					['id'=>'32', 'latitude'=>'39.952207', 'longitude'=>'-75.145390'],
					['id'=>'33', 'latitude'=>'39.952359', 'longitude'=>'-75.146661'],
					['id'=>'34', 'latitude'=>'39.952396', 'longitude'=>'-75.146974'],
					['id'=>'35', 'latitude'=>'39.952565', 'longitude'=>'-75.148253'],
					['id'=>'36', 'latitude'=>'39.952732', 'longitude'=>'-75.149656'],
					['id'=>'37', 'latitude'=>'39.952808', 'longitude'=>'-75.150237'],
					['id'=>'38', 'latitude'=>'39.952930', 'longitude'=>'-75.151237'],
					['id'=>'39', 'latitude'=>'39.953006', 'longitude'=>'-75.151803'],
					['id'=>'40', 'latitude'=>'39.953315', 'longitude'=>'-75.154387'],
					['id'=>'41', 'latitude'=>'39.953398', 'longitude'=>'-75.155005'],
					['id'=>'42', 'latitude'=>'39.953537', 'longitude'=>'-75.156113'],
					['id'=>'43', 'latitude'=>'39.953599', 'longitude'=>'-75.156501'],
					['id'=>'44', 'latitude'=>'39.953721', 'longitude'=>'-75.157547'],
					['id'=>'45', 'latitude'=>'39.953796', 'longitude'=>'-75.158147'],
					['id'=>'46', 'latitude'=>'39.953905', 'longitude'=>'-75.159098'],
					['id'=>'47', 'latitude'=>'39.953588', 'longitude'=>'-75.159527'],
					['id'=>'48', 'latitude'=>'39.953438', 'longitude'=>'-75.159582'],
					['id'=>'49', 'latitude'=>'39.953278', 'longitude'=>'-75.159607'],
					['id'=>'50', 'latitude'=>'39.953061', 'longitude'=>'-75.159662'],
					['id'=>'51', 'latitude'=>'39.952788', 'longitude'=>'-75.159720'],
					['id'=>'52', 'latitude'=>'39.952132', 'longitude'=>'-75.159859'],
					['id'=>'53', 'latitude'=>'39.951954', 'longitude'=>'-75.160169'],
					['id'=>'54', 'latitude'=>'39.952094', 'longitude'=>'-75.161226'],
					['id'=>'55', 'latitude'=>'39.952157', 'longitude'=>'-75.161735'],
					['id'=>'56', 'latitude'=>'39.952370', 'longitude'=>'-75.162521'],
					['id'=>'57', 'latitude'=>'39.953139', 'longitude'=>'-75.162532'],
					['id'=>'58', 'latitude'=>'39.953423', 'longitude'=>'-75.163849'],
					['id'=>'59', 'latitude'=>'39.953620', 'longitude'=>'-75.164829'],
					['id'=>'60', 'latitude'=>'39.953765', 'longitude'=>'-75.165845'],
					['id'=>'61', 'latitude'=>'39.953843', 'longitude'=>'-75.166298'],
					['id'=>'62', 'latitude'=>'39.954073', 'longitude'=>'-75.166477'],
					['id'=>'63', 'latitude'=>'39.954611', 'longitude'=>'-75.166389'],
					['id'=>'64', 'latitude'=>'39.955077', 'longitude'=>'-75.166613'],
					['id'=>'65', 'latitude'=>'39.955749', 'longitude'=>'-75.167484'],
					['id'=>'66', 'latitude'=>'39.956115', 'longitude'=>'-75.167999'],
					['id'=>'67', 'latitude'=>'39.956798', 'longitude'=>'-75.168948'],
					['id'=>'68', 'latitude'=>'39.957345', 'longitude'=>'-75.169681'],
					['id'=>'69', 'latitude'=>'39.957598', 'longitude'=>'-75.169835'],*/
				];
			}else{
				$data = [
					//['id'=>'1', 'latitude'=>'39.96844', 'longitude'=>'-75.17516'],
					//['id'=>'2', 'latitude'=>'39.96944', 'longitude'=>'-75.17616'],
				];
			}
			if(count($data) > 0){
				return $this->sendArrayResponse($data,trans('customer_api.data_found_success'));
			}
			return $this->sendArrayResponse('',trans('customer_api.data_found_empty'));
			
		}catch (\Exception $e) {
			return $this->sendError('', $e->getMessage());
		}
	}
   //show list of audio files is yes
	public function list()
    {
        $vehicles = Bus::where('audio_available', 'yes')->get();
        return response()->json($vehicles);
    }
}