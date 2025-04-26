<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Bus;

class BusTracking extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bus:tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store Bus lat long from API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
	* Execute the console command.
	* @return mixed
	*/
    public function handle(){
        
		
		\Log::info("Cron XXZ is working fine!");
		
		return;
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
						Bus::where('device_id', $list->deviceId)->update($data);
					}
				}
			}
		}
    }
}
