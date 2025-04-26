<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use App\Models\Helpers\CommonHelper;
use App\Models\CustomMap;

class CustomMapController extends CommonController
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
		$data = CustomMap::find(1);
		return view('backend.customMap.edit',compact('data'));
	}
  
	// STORE
	public function store(Request $request){
		$user = Auth()->user();
 		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		try{
			// MEDIA UPLOAD
			if(!empty($request->image) && $request->image != 'undefined'){
				$data['image'] =  $this->saveMedia($request->file('image'));
				
				// UPDATE
				$query  = CustomMap::find(1);
				$query->fill($data);
				$return  =  $query->save();
				if($return){
					$this->sendResponse([], trans('common.saved_success'));
				}
			}
			
			$this->ajaxError([], trans('common.try_again'));

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	// Remove Image
	public function removeImage(Request $request){
		$validator = Validator::make($request->all(), [
			'id' => 'required',
		]);
		if($validator->fails()){
			$this->ajaxError($validator->errors(), trans('common.error'));
		}
		
		if(CustomMap::where('id',$request->id)->update(['image' => NULL ])){
			$this->sendResponse([], "Image deleted successfully");
		}else{
			$this->sendResponse([], trans('common.delete_error'));
		}
	}
}