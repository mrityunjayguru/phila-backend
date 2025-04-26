<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use App\Models\Helpers\CommonHelper;
use App\Models\About;

class AboutController extends CommonController
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
	}
  
	// ADD NEW
	public function index(){
		$data = About::find(1);
		return view('backend.about.edit',compact('data'));
	}
  
	// STORE
	public function store(Request $request){
		$user = Auth()->user();
 		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}

		$validator = Validator::make($request->all(), [
			'description' => 'required',
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		
		try{
			$data = [
				'description' => $request->description,
			];
			
			// UPDATE
			$query  = About::find(1);
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