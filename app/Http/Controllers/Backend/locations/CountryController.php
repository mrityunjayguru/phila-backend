<?php

namespace App\Http\Controllers\Backend\Locations;

use App\Http\Controllers\CommonController;

use App,Validator,Auth,DB,Storage;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Helpers\CommonHelper;

class CountryController extends CommonController
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
        //$this->middleware('permission:product-list', ['only' => ['index','show']]);
        //$this->middleware('permission:product-create', ['only' => ['create','store']]);
        //$this->middleware('permission:product-edit', ['only' => ['edit','update']]);
        //$this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
  
	// ADD NEW
	public function index(){
		return view('backend.locations.country.list');
	}
  
	// LIST
	public function ajax_list(Request $request){
		$page     = $request->page ?? '1';
		$count    = $request->count ?? '10';
		$status    = $request->status ?? 'all';
		
		if ($page <= 0){ $page = 1; }
		$start  = $count * ($page - 1);
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		try{
			// GET LIST
			$query = Country::query();
			
			$data  = $query->orderBy('id', 'DESC')->offset($start)->limit($count)->get();
			if($data){
				foreach($data as $key=> $list){
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("countries.edit",$list->id) .'"><i class="fa fa-eye"></i></a>
											<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>
											</div>';
					
					$status_array = ['active'=>'', 'inactive'=>''];
					if($list->status == 'active') { $status_array['active'] = 'selected'; }
					if($list->status == 'inactive') { $status_array['inactive'] = 'selected'; }
					$data[$key]->status = "<select class='form-control status' id='$list->id'>
										<option value='active' 	". $status_array['active'] .">Active</option>
										<option value='inactive'". $status_array['inactive'] .">Inactive</option>
									</select>";
				}
				$this->sendResponse($data, trans('common.data_found_success'));
			}
			$this->sendResponse([], trans('common.data_found_empty'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}

	// CREATE
	public function create(){
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		return view('backend.locations.country.add');
	}
	
	// EDIT
	public function edit($id = null){
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		$countries = Country::find($id);
		return view('backend.locations.country.edit',compact('countries'));
	}
  
	// STORE
	public function store(Request $request){
		$user = Auth()->user();
 		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		try{
			if(!empty($request->filter_section && $request->filter_section == 'update')){
				//Update section
				$validator = Validator::make($request->all(), [
					'edit_id'			=> 'required|numeric',
					'country_name'      => 'required|min:3|max:99',
				]);
				if($validator->fails()){
					$this->ajaxValidationError($validator->errors(), trans('common.error'));
				}
				$updaetData = [
					'country_name' => $request->country_name,
					'country_code' => $request->country_code,
					'dial_code' => $request->dial_code,
					'currency_code' => $request->currency_code,
					'currency' => $request->currency,
					'currency_symbol' => $request->currency_symbol,
					'status' => $request->status
				];
				if(Country::where('id',$request->edit_id)->update($updaetData)){
					$this->sendResponse([], trans('common.updated_success'));
				}
			}else if(!empty($request->filter_section && $request->filter_section == 'add_section')){
				$validator = Validator::make($request->all(), [
					'country_name'      => 'required|min:3|max:99',
				]);
				if($validator->fails()){
					$this->ajaxValidationError($validator->errors(), trans('common.error'));
				}
				$insertData = [
					'country_name' => $request->country_name,
					'country_code' => $request->country_code,
					'dial_code' => $request->dial_code,
					'currency_code' => $request->currency_code,
					'currency' => $request->currency,
					'currency_symbol' => $request->currency_symbol,
					'status' => $request->status
				];
				if(Country::create($insertData)){
					$this->sendResponse([], 'Country added successfully');
				}
			}
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
  
	/**
	* Change Table Status.
	* @return void
	*/
	public function change_status(Request $request){
		DB::beginTransaction();
		try {
			$query = Country::where('id',$request->id)->update(['status'=>$request->status]);
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
			$query = Country::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}