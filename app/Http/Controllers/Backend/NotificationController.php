<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use App\Models\Helpers\CommonHelper;
use App\Models\Place;
use App\Models\FirebaseToken;

class NotificationController extends CommonController
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
		$data = Place::where('status', 'active')->get();
		return view('backend.notification.index',compact('data'));
	}
  
	// STORE
	public function fire(Request $request){
		$user = Auth()->user();
 		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}

		$validator = Validator::make($request->all(), [
			//'title'		=> 'required',	
			'description'	=> 'required|max:1000',
		]);
		
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		
		try{
			$tokens = FirebaseToken::where('status', 'active')->where('other_notification', 1)->get();
			foreach($tokens as $list){
				// Send
				$type	 = '';
				$type_id = '';
				if($request->place){
					$type	 = 'Place';
					$type_id = $request->place;
				}
				CommonHelper::send_notification($user, $request->title, $request->description, $type, $type_id, $list->token);
			}
			
			$sent = 1;
			if($sent){
				return $this->sendResponse('', 'Sent successfully');
			}

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}