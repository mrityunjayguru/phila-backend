<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth,App,DB;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Bus;

class HomeController extends Controller
{
    /**
	* @return void
	*/
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
	* Show the application first page.
	*/
    public function index(){
		$page			= 'home';
		$page_title		= '';
		
		$user = Auth()->user();
 		if(empty($user)){
			return redirect('/login');
		}
		
		return view('theme/firstPage', compact('page','page_title'));
    }
	
	/**
	* CHECK BUS STATUS
	*/
	public function runCroe(){
		try {
			// GET Device Data
			$username = 'worklooper';
			$password = 'User@123';
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
							$depo_distance = $this->distance('39.938609', '-75.203738', $list->latitude, $list->longitude, 'K');
							if($depo_distance > 0 && $depo_distance < 1){
								$data['last_visited_stop'] = '0';
							}
							Bus::where('device_id', $list->deviceId)->update($data);
						}
					}
				}
			}
			echo 'Run successfully!!'; exit;
		} catch (Exception $e) {
			echo $e->getMessage(); exit;
		}
    }
	
	static function distance($lat1, $lon1, $lat2, $lon2, $unit = 'K') {
		if(empty($lat1) || empty($lon1)){
			return 0;
		}
		if(empty($lat2) || empty($lon2)){
			return 0;
		}
		
		if ($lat1 == $lat2){
			return 0;
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
			return $miles;
		}
	}
	
	
    //Localization function
    public function lang($locale){
        App::setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }
}