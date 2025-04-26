<?php
namespace App\Http\Controllers\theme;

use Validator,Auth,App;
use Illuminate\Http\Request;

use App\Http\Controllers\CommonController;
use App\Models\Helpers\CommonHelper;
use App\Models\Stop;
use App\Models\Bus;
use App\Models\Place;
use App\Models\Ticket;
use App\Models\Audio;

class MapController extends CommonController
{
	use CommonHelper;
	
    /**
	* @return void
	*/
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
	* Show the product list page.
	*/
	public function index(){
		try {
			$page       = 'mapPage';
			$page_title = 'Map View';
			
			return view('theme.trip', compact('page','page_title'));
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	/**
	* Show ticket validate page.
	*/
	public function ticketValidate(){
		try {
			$page       = 'ticketValidate';
			$page_title = 'Ticket Validate';
			
			return view('theme.ticket-validate', compact('page','page_title'));
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	/**
	* Ticket validate page.
	*/
	public function ajax_ticketValidate(Request $request){
		$validator = Validator::make($request->all(), [
			'code'	=> 'required',
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		
		try {
			// Validate Ticket
			$data = Ticket::where(['status'=>'active'])->where('ticket_number', $request->code)->first();
			if(empty($data)){
				return $this->ajaxError([], 'Invalid Ticket code');
			}
			
			if(date("Y-m-d") > $data->end_date){
				return $this->ajaxError([], 'Ticket has been expired');
			}
			
			return $this->sendResponse($data, 'Invalid Ticket code');
			
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	/**
	* Track the Bus.
	*/
	public function track($ticket_number = ''){
		try {
			$page       = 'trackBus';
			$page_title = 'Track Bus';
			
			
			// Validate Ticket
			$data = Ticket::where(['status'=>'active'])->where('ticket_number', $ticket_number)->first();
			if(empty($data)){
				return redirect()->route('ticketValidate');
			}
			
			if(date("Y-m-d") > $data->end_date){
				return redirect()->route('ticketValidate');
			}
			
			return view('theme.track', compact('page','page_title'));
		} catch (Exception $e) {
			return redirect()->route('ticketValidate');
		}
	}
	
	/**
	* Track the Bus Backend.
	*/
	public function trackBackend(){
		
		$user = Auth()->user();
 		if(empty($user)){
			//return redirect()->route('login');
		}
		
		try {
			$page       = 'trackBackend';
			$page_title = 'Track Vehicles';
			
			return view('theme.track-vehicles', compact('page','page_title'));
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}

	public function mapTriggerPoints($page_id){
		$triggerPoints = Audio::where('page_id',$page_id)->select('latitude', 'longitude', 'title')->get();
		// dd($triggerPoints);
        return view('backend.audio_files.map-trigger-points', compact('triggerPoints'));
	}
	
	/**
	* Display a listing of the resource.
	* @return \Illuminate\Http\Response
	*/
	public function ajax_roots(Request $request){
		
		try {
			$data = CommonHelper::mapRouts($request->type);
			if(count($data) > 0){
				$this->sendResponse($data, trans('theme.data_found_success'));
			}
			$this->sendResponse($data, trans('theme.data_found_empty'));
		} catch (Exception $e) {
			
		}
	}
	
	/**
	* Display a listing of the resource.
	* @return \Illuminate\Http\Response
	*/
	public function ajax_stops(Request $request){
		$page	= $request->page ?? '1';
		$count	= $request->count ?? '100';
		
		if ($page <= 0){ $page = 1; }
		$start  = $count * ($page - 1);
		
		try {
			// Get Bus Data
			$query = Stop::where(['status'=>'active'])->offset($start)->limit($count)->get();
			if(count($query) > 0){
				$this->sendResponse($query, trans('theme.data_found_success'));
			}
			$this->sendResponse([], trans('theme.data_found_empty'));
		} catch (Exception $e) {
			
		}
	}
	
	/**
	* Display a listing of the resource.
	* @return \Illuminate\Http\Response
	*/
	public function ajax_places(Request $request){
		$page     = $request->page ?? '1';
		$count	= $request->count ?? '100';
		
		if ($page <= 0){ $page = 1; }
		$start  = $count * ($page - 1);
		
		try {
			// Get Bus Data
			$query = Place::where(['status'=>'active'])->offset($start)->limit($count)->get();
			if(count($query) > 0){
				$this->sendResponse($query, trans('theme.data_found_success'));
			}
			$this->sendResponse([], trans('theme.data_found_empty'));
		} catch (Exception $e) {
			
		}
	}
	
	/**
	* Display a listing of the resource.
	* @return \Illuminate\Http\Response
	*/
	public function ajax_track(Request $request){
		try {
			// Get Bus Data
			$query = Bus::query();
			if($request->status){
				$query->where('status', $request->status)->where('live_status','online');
			}
			$data = $query->whereIn('device_type', explode(",", $request->device_type))->get();
			if(count($data) > 0){
				$this->sendResponse($data, trans('theme.data_found_success'));
			}
			$this->sendResponse([], trans('theme.data_found_empty'));
		} catch (Exception $e) {
			
		}
	}
}