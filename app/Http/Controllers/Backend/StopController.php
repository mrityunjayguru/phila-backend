<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use App\Models\Helpers\CommonHelper;
use App\Models\Stop;
use App\Models\Setting;

class StopController extends CommonController
{   
	use CommonHelper;
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct()
	{
 		$this->middleware('auth');
		//$this->middleware('permission:brand-list', ['only' => ['index','show']]);
		//$this->middleware('permission:brand-create', ['only' => ['create','store']]);
		//$this->middleware('permission:brand-edit', ['only' => ['edit','update']]);
		//$this->middleware('permission:brand-delete', ['only' => ['destroy']]);
	}
  
	// ADD NEW
	public function index(){
		return view('backend.stops.list');
	}

	// CREATE
	public function create(){

		return view('backend.stops.add');
	}
  
	public function ajax_list(Request $request){
		$page     = $request->page ?? '1';
		$count    = $request->count ?? '100';
		if ($page <= 0){ $page = 1; }
		$start  = $count * ($page - 1);
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		try{
			// GET LIST
			$query = Stop::query();
			
			// Filters
			if($request->status != 'all'){
				$query->where(['status' => $request->status]);
			}
			
			// Filters
			if($request->type != 'all'){
				$query->where(['type' => $request->type]);
			}
			
			// SEARCH
			if($request->search){
				$query->where('title','like','%'.$request->search.'%');
			}
			$data = $query->orderBy('priority', 'ASC')->offset($start)->limit($count)->get();
			
			
			if($data){
				foreach($data as $key=> $list){
					$delete_btn = '';
					if(Setting::UserPermission('stop-delete')){
						//$delete_btn = '<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>';
					}
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("stops.edit",$list->id) .'"><i class="fa fa-eye"></i></a>'. $delete_btn .'
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
  
	// EDIT
	public function edit($id = null){
		$data = Stop::find($id);
		
		$for_type = '';
		if($data->for_type == 'tour'){
			$for_type = 'Tour';
		}else if($data->for_type == 'fairmount_park_loop'){
			$for_type = 'Fairmount Park Loop';
		}
		return view('backend.stops.edit',compact('data', 'for_type'));
	}
  
	// STORE
	public function store(Request $request){
		$user = Auth()->user();
 		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}

		$validator = Validator::make($request->all(), [
			'title'				=> 'required|min:3|max:99',	
			'time'				=> 'required',	
			'latitude'			=> 'required|min:3|max:99',	
			'longitude'			=> 'required|min:3|max:99',	
			'type'				=> 'required',
			'status'			=> 'required',
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		$data =  $request->all();
	
		if($request->item_id){
			$id = $request->item_id;
			$validator = Validator::make($request->all(), [
				'item_id' => 'required',
			]);
			if($validator->fails()){
				$this->ajaxValidationError($validator->errors(), trans('common.error'));
			}
			
			// Ignore if stop no. = 12
			$checkExist =  Stop::where('priority', $request->priority)->where('id','!=',$request->item_id)->first();
			if($checkExist){
				if($checkExist->priority != 12){
					$error = [];
					$error['priority'][0] = 'The priority has already been taken.';
					return $this->ajaxValidationError($error, trans('common.error'));
				}
			}
		}
		
		try{
			$color = '';
			if($request->type == 'tour'){
				$color = 'red';
			}else if($request->type == 'fairmount_park_loop'){
				$color = 'blue';
			}else{
				$color = 'mix';
			}

			$data = [
				'title'       	=> $request->title,
				'priority'		=> $request->priority,
				'time'			=> $request->time,
				'latitude' 		=> $request->latitude,
				'longitude' 	=> $request->longitude,
				'description' 	=> $request->description,
				'color'			=> $color,
				'type'			=> $request->type,
				'status'	  	=> $request->status,
			];
			
			// // MEDIA UPLOAD
			if(!empty($request->image) && $request->image != 'undefined'){
				$data['image'] =  $this->saveMedia($request->file('image'));
			}
			
			// STOP IMAGE
			if(!empty($request->stop_image) && $request->stop_image != 'undefined'){
				$data['stop_image'] =  $this->saveMedia($request->file('stop_image'));
			}
			
			if($request->item_id){
				// UPDATE
				$stop  =  Stop::find($request->item_id);
				
				
				if($stop->for_type == 'tour'){
					$data['color'] = 'red';
				}
				if($stop->for_type == 'fairmount_park_loop'){
					$data['color'] = 'blue';
				}
				
				
				$stop->fill($data);
				$return  =  $stop->save();
				
				if($return){
					$this->sendResponse([], trans('common.updated_success'));
				}
			} else{
				// CREATE
				$validator = Validator::make($request->all(), [
					'priority'			=> 'required|min:1|max:99|unique:stops,priority',
				]);
				if($validator->fails()){
					$this->ajaxValidationError($validator->errors(), trans('common.error'));
				}
				$return = Stop::create($data);
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
			$query = Stop::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}

	public function removeStopImage(Request $request){
		$validator = Validator::make($request->all(), [
			'id' => 'required',
		]);
		if($validator->fails()){
			$this->ajaxError($validator->errors(), trans('common.error'));
		}
		
		if(Stop::where('id',$request->id)->update(['image' => NULL ])){
			$this->sendResponse([], "Image deleted successfully");
		}else{
			$this->sendResponse([], trans('common.delete_error'));
		}
	}
	
	public function removeStopStopImage(Request $request){
		$validator = Validator::make($request->all(), [
			'id' => 'required',
		]);
		if($validator->fails()){
			$this->ajaxError($validator->errors(), trans('common.error'));
		}
		
		if(Stop::where('id',$request->id)->update(['stop_image' => NULL ])){
			$this->sendResponse([], "Image deleted successfully");
		}else{
			$this->sendResponse([], trans('common.delete_error'));
		}
	}
}