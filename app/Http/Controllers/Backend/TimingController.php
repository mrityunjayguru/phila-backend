<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use App\Models\Helpers\CommonHelper;
use App\Models\Timing;

class TimingController extends CommonController
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
		//$this->middleware('permission:timing-list', ['only' => ['index','show']]);
		//$this->middleware('permission:timing-create', ['only' => ['create','store']]);
		//$this->middleware('permission:timing-edit', ['only' => ['edit','update']]);
		//$this->middleware('permission:timing-delete', ['only' => ['destroy']]);
	}
  
	// ADD NEW
	public function index(){
		$data = Timing::find(1);
		return view('backend.timing.edit',compact('data'));
	}
  
	// STORE
	public function store(Request $request){
		$user = Auth()->user();
 		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}

		$validator = Validator::make($request->all(), [
			'first_bus'				=> 'required',	
			'last_bus'				=> 'required',
			'frequency'				=> 'required',
			'fairmount_first_bus'	=> 'required',
			'fairmount_last_bus'	=> 'required',
			'fairmount_frequency'	=> 'required',
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		
		try{
			$data = [
				'first_bus'				=> $request->first_bus,
				'last_bus'				=> $request->last_bus,
				'frequency'				=> $request->frequency,
				'fairmount_first_bus'	=> $request->fairmount_first_bus,
				'fairmount_last_bus'	=> $request->fairmount_last_bus,
				'fairmount_frequency'	=> $request->fairmount_frequency,
			];
			
			// UPDATE
			$query  = Timing::find(1);
			$query->fill($data);
			$return  =  $query->save();
			if($return){
				$this->sendResponse([], trans('common.updated_success'));
			}
			$this->ajaxError([], trans('common.try_again'));

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}