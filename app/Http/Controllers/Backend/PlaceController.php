<?php

namespace App\Http\Controllers\Backend;

use Validator,Auth,DB,Storage;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;


use App\Models\Helpers\CommonHelper;
use App\Models\Place;
use App\Models\Stop;
use App\Models\Offer;
use App\Models\Setting;

class PlaceController extends CommonController
{   
	use CommonHelper;
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct()
	{
		//$this->middleware('permission:place-list', ['only' => ['index','ajax','show']]);
		//$this->middleware('permission:place-create', ['only' => ['create','store']]);
		//$this->middleware('permission:place-edit', ['only' => ['edit','update']]);
		//$this->middleware('permission:place-delete', ['only' => ['destroy']]);
	}
  
	// LIST
	public function index($type = ''){
		try{
			$page_title = trans('title.places');
			
			return view("backend.places.list", compact(['page_title', 'type']));
			
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	// CREATE
	public function create($type = ''){
		
		$page_title = trans('title.place_create');
		$stops 		= Stop::where('status', 'active')->get();
		
		$days = [
					['id'=>'monday', 'title'=>"Monday"],
					['id'=>'tuesday', 'title'=>"Tuesday"],
					['id'=>'wednesday', 'title'=>"Wednesday"],
					['id'=>'thursday', 'title'=>"Thursday"],
					['id'=>'friday', 'title'=>"Friday"],
					['id'=>'saturday', 'title'=>"Saturday"],
					['id'=>'sunday', 'title'=>"Sunday"],
				];
		return view("backend.places.add", compact(['page_title', 'type', 'stops', 'days']));
	}
	
	// EDIT PAGE
	public function edit($type = null, $id = ''){
		try {
			$page_title = trans('title.place_update');
			$data 		= Place::where('id',$id)->first();
			$stops 		= Stop::where('status', 'active')->get();
			$days = [
					['id'=>'monday', 'title'=>"Monday"],
					['id'=>'tuesday', 'title'=>"Tuesday"],
					['id'=>'wednesday', 'title'=>"Wednesday"],
					['id'=>'thursday', 'title'=>"Thursday"],
					['id'=>'friday', 'title'=>"Friday"],
					['id'=>'saturday', 'title'=>"Saturday"],
					['id'=>'sunday', 'title'=>"Sunday"],
				];
			if($data){
				return view("backend.places.edit", compact(['page_title','data', 'type','stops','days']));
			}
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	// LIST
	public function ajax_list(Request $request){
		$page     = $request->page ?? '1';
		$count    = $request->count ?? '100';
		$status    = $request->status ?? 'all';
		
		if ($page <= 0){ $page = 1; }
		$start  = $count * ($page - 1);
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		try{
			// GET LIST
			$query = Place::where('type', $request->type);
			
			// Filters
			if($request->status != 'all'){
				$query->where(['status' => $request->status]);
			}
			
			// SEARCH
			if($request->search){
				$query->where('title','like','%'.$request->search.'%');
			}
			$data = $query->orderBy('title', 'ASC')->offset($start)->limit($count)->get();
			if($data){
				foreach($data as $key=> $list){
					$delete_btn = '';
					if(Setting::UserPermission('place-delete')){
						$delete_btn = '<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>';
					}
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. url('backend/places/'. $request->type .'/'.$list->id) .'"><i class="fa fa-eye"></i></a>'. $delete_btn .'
											</div>';
					if($list->image){ $data[$key]->image  = asset($list->image); }else { $data[$key]->image  = asset(config('constants.DEFAULT_MENU_IMAGE')); }
				}
				$this->sendResponse($data, trans('common.data_found_success'));
			}
			$this->sendResponse([], trans('common.data_found_empty'));

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	// STORE
	public function store(Request $request){

		$validator = Validator::make($request->all(), [
			'title'					=> 'required|min:3|max:99',	
			//'latitude'				=> 'required',
			//'longitude'				=> 'required',
			//'google_business_url'	=> 'required',
			'nearest_stop'			=> 'required',
			'type'					=> 'required',
			'status'				=> 'required',
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		
		$user = Auth()->user();
 		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
	
		if($request->item_id){
			$validator = Validator::make($request->all(), [
				'item_id' => 'required',
			]);
			if($validator->fails()){
				$this->ajaxValidationError($validator->errors(), trans('common.error'));
			}
		}
	
		try{
			$data = [
				'type'       		=> $request->type,
				'title'       		=> $request->title,
				'address'			=> $request->address,
				'description' 		=> $request->description,
				'nearest_stop'		=> $request->nearest_stop,
				'latitude'			=> $request->latitude,
				'longitude'			=> $request->longitude,
				'google_business_url' => $request->google_business_url,
				'website'	  		=> $request->website,
				'phone_number'		=> $request->contact_number,
				'is_hours'			=> $request->is_hours,
				'monday' 			=> $request->monday,
				'tuesday' 			=> $request->tuesday,
				'wednesday' 		=> $request->wednesday,
				'thursday' 			=> $request->thursday,
				'friday' 			=> $request->friday,
				'saturday' 			=> $request->saturday,
				'sunday' 			=> $request->sunday,
				'status'	  		=> $request->status,
			];
			
			if($request->type == 'landmark' || $request->type == 'attraction'){
				$data['is_charges']		= $request->is_charges;
				if($request->is_charges){
					$data['charges'] = $request->charges;
				}
				$data['ticket_booking_url'] = $request->ticket_booking_url;
			}
			
			
			// // MEDIA UPLOAD
			if(!empty($request->image) && $request->image != 'undefined'){
				$data['image'] =  $this->saveMedia($request->file('image'));
			}
			
			if($request->item_id){
				// UPDATE
				$place  =  Place::find($request->item_id);
				$place->fill($data);
				$return  =  $place->save();
				
				if($return){
					
					$place = Place::find($request->item_id);
					// Udate Offer Stop ID
					Offer::where('place_id', $place->id)->update(array('nearest_stop' => $place->nearest_stop));
					
					$this->sendResponse([], trans('common.updated_success'));
				}
			} else{
				// CREATE
				$return = Place::create($data);
				if($return){
					$this->sendResponse([], trans('common.added_success'));
				}
			}
			$this->ajaxError([], trans('common.try_again'));

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	// DESTROY
	public function destroy(Request $request){
		$validator = Validator::make($request->all(), [
			'item_id' => 'required',
		]);
		if($validator->fails()){
			$this->ajaxError($validator->errors(), trans('common.error'));
		}
		
		try{
			// DELETE
			$query = Place::where(['id'=>$request->item_id])->delete();
			if($query){
				Offer::where(['place_id'=>$request->item_id])->delete();
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}

	public function removePlaceImage(Request $request){
		$validator = Validator::make($request->all(), [
			'id' => 'required',
		]);
		if($validator->fails()){
			$this->ajaxError($validator->errors(), trans('common.error'));
		}
		
		if(Place::where('id',$request->id)->update(['image' => NULL ])){
			$this->sendResponse([], "Image deleted successfully");
		}else{
			$this->sendResponse([], trans('common.delete_error'));
		}
	}
}