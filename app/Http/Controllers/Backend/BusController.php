<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use App\Models\Helpers\CommonHelper;
use App\Models\Bus;
use App\Models\Setting;

class BusController extends CommonController
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
		return view('backend.buses.list');
	}

	// CREATE
	public function create(){

		return view('backend.buses.add');
	}
  
	public function ajax_list(Request $request){
		$page     = $request->page ?? '1';
		$count    = $request->count ?? '100';
		
		if ($page <= 0){ $page = 1; }
		$start = $count * ($page - 1);
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		try{
			// GET LIST
			$query = Bus::query();
			// Filters
			if($request->status != 'all'){
				$query->where(['status' => $request->status]);
			}
			
			// SEARCH
			if($request->search){
				$query->where('title','like','%'.$request->search.'%');
			}
			$data = $query->orderBy('id', 'DESC')->offset($start)->limit($count)->get();
			
			if(count($data) > 0){
				foreach($data as $key=> $list){
					$delete_btn = '';
					if(Setting::UserPermission('bus-delete')){
						$delete_btn = '<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>';
					}
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("buses.edit",$list->id) .'"><i class="fa fa-eye"></i></a>'. $delete_btn .'
											</div>';

					$status_array = ['active'=>'', 'inactive'=>''];
					if($list->status == 'active') { $status_array['active'] = 'selected'; }
					if($list->status == 'inactive') { $status_array['inactive'] = 'selected'; }
					$data[$key]->status = "<select class='form-control status' id='$list->id'>
										<option value='active' 	". $status_array['active'] .">Active</option>
										<option value='inactive'". $status_array['inactive'] .">Inactive</option>
									</select>";
									
				    	$audio_available = ['yes'=>'', 'no'=>''];
					if($list->audio_available == 'yes') { $audio_available['yes'] = 'selected'; }
					if($list->audio_available == 'no') { $audio_available['no'] = 'selected'; }
					$data[$key]->audio_available = "<select class='form-control audio_available' id='$list->id'>
										<option value='yes' 	". $audio_available['yes'] .">Yes</option>
										<option value='no'". $audio_available['no'] .">No</option>
									</select>";
					
					// For Security
					if($list->security == 'private') {
						$data[$key]->security = "<select class='form-control security' id='$list->id'>
										<option value='private' selected>Private</option>
										<option value='public'>Public</option>
									</select>";
					}
					if($list->security == 'public') {
						$data[$key]->security = "<select class='form-control security' id='$list->id'>
										<option value='public' selected>Public</option>
										<option value='private'>Private</option>
									</select>";
					}
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
		$data = Bus::find($id);
		return view('backend.buses.edit',compact('data'));
	}
  
	// STORE
	public function store(Request $request){
		$user = Auth()->user();
 		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}

		$validator = Validator::make($request->all(), [
			'title'				=> 'required|min:3|max:99',
			'device_type'		=> 'required|min:1|max:99',
			'imei_number'		=> 'required|min:1|max:99',
			//'latitude'		=> 'required|min:3|max:99',
			//'longitude'		=> 'required|min:3|max:99',
			'status'			=> 'required',
			'audio_available'			=> 'required',
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		
		
		try{
			
			$data = [
				'title'       	=> $request->title,
				'device_id'		=> $request->device_id,
				'device_type'	=> $request->device_type,
				'imei_number'	=> $request->imei_number,
				//'latitude'	=> $request->latitude,
				//'longitude' 	=> $request->longitude,
				'status'	  	=> $request->status,
				'audio_available' => $request->audio_available,
			];
			
			
			if($request->id){
				// UPDATE
				$validator = Validator::make($request->all(), [
					'id' 			=> 'required|exists:buses,id',
					'device_id'		=> 'required|min:1|max:99|unique:buses,device_id,'.$request->device_id.',device_id',
				]);
				if($validator->fails()){
					$this->ajaxValidationError($validator->errors(), trans('common.error'));
				}
				$stop  =  Bus::find($request->id);
				$stop->fill($data);
				$return  =  $stop->save();
				
				if($return){
					$this->sendResponse([], trans('common.updated_success'));
				}
			} else{
				// CREATE
				$validator = Validator::make($request->all(), [
					'device_id'			=> 'required|min:1|max:99|unique:buses,device_id',
				]);
				if($validator->fails()){
					$this->ajaxValidationError($validator->errors(), trans('common.error'));
				}
			
				$return = Bus::create($data);
				if($return){
					$this->sendResponse([], trans('common.added_success'));
				}
			}
			$this->ajaxError([], trans('common.try_again'));

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	// Change Status
	public function change_status(Request $request){
        $validator = Validator::make($request->all(), [
            'status'	=> 'required',
            'id'		=> 'required|numeric',
        ]);
        if($validator->fails()){
            $this->ajaxValidationError($validator->errors(), trans('common.error'));
        }

		DB::beginTransaction();
		try {
			$query = Bus::where('id',$request->id)->update(['status'=>$request->status]);
			if($query){
			  DB::commit();
			  $this->sendResponse(['status'=>'success'], trans('common.updated_success'));
			}else{
			  DB::rollback();
			  $this->sendResponse(['status'=>'error'], trans('common.updated_error'));
			}
			
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	// Change Audio Status
	public function change_audio_status(Request $request){
        $validator = Validator::make($request->all(), [
            'audio_available'	=> 'required',
            'id'		=> 'required|numeric',
        ]);
        if($validator->fails()){
            $this->ajaxValidationError($validator->errors(), trans('common.error'));
        }

		DB::beginTransaction();
		try {
			$query = Bus::where('id',$request->id)->update(['audio_available'=>$request->audio_available]);
			if($query){
			  DB::commit();
			  $this->sendResponse(['status'=>'success'], trans('common.updated_success'));
			}else{
			  DB::rollback();
			  $this->sendResponse(['status'=>'error'], trans('common.updated_error'));
			}
			
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	// Change Security
	public function change_security(Request $request){
        $validator = Validator::make($request->all(), [
            'security'	=> 'required',
            'id'		=> 'required|numeric',
        ]);
        if($validator->fails()){
            $this->ajaxValidationError($validator->errors(), trans('common.error'));
        }

		DB::beginTransaction();
		try {
			$query = Bus::where('id',$request->id)->update(['security'=>$request->security]);
			if($query){
			  DB::commit();
			  $this->sendResponse(['status'=>'success'], trans('common.updated_success'));
			}else{
			  DB::rollback();
			  $this->sendResponse(['status'=>'error'], trans('common.updated_error'));
			}
			
		} catch (Exception $e) {
			DB::rollback();
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
			$query = Bus::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}

	
}