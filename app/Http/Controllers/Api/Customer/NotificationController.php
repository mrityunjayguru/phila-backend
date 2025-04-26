<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\FirebaseToken;
use App\Http\Resources\NotificationListResource;
use App\Http\Resources\NotificationSettingResource;
use DB,Validator,Auth;

class NotificationController extends BaseController
{
    /**
	* Display a listing of the resource.
	* @return \Illuminate\Http\Response
	*/
    public function list(Request $request) {
      
      $search = $request->search;
      $page   = $request->page ?? '0';
      $count  = $request->count ?? '1000';

      if ($page <= 0){ $page = 1; }
      $start  = $count * ($page - 1);

      $user = Auth::user();
      if(empty($user)){
		//return $this->sendError('',trans('customer_api.invalid_user'));
      }

      try{

        $query = Notification::where(['token'=>$request->token])->orderBy('id', 'DESC')->offset($start)->limit($count)->get();
        if(empty($query)){
			return $this->sendArrayResponse('', trans('customer_api.data_found_empty'));
        }
		// Update Read Status
		Notification::where('token', $request->token)->update(['is_read'=>1]);
			
        return $this->sendArrayResponse(NotificationListResource::collection($query), trans('customer_api.data_found_success'));
      }catch (\Exception $e) { 
        return $this->sendError('', $e->getMessage()); 
      }
    }

    // NOTIFICATION DETAILS
    public function show($id = null) {
      
      try{
        $query = Notification::where(['id'=>$request->notification_id])->first();
        return $this->sendResponse(new NotificationListResource($query),trans('customer_api.data_found_success'));
      }catch (\Exception $e) { 
        return $this->sendError('', $e->getMessage()); 
      }
    }
	
	/**
	* Get Setting
	* @return \Illuminate\Http\Response
	*/
    public function getSettings(Request $request) {
      $validator = Validator::make($request->all(), [
          'token' => 'required',
      ]);
      if($validator->fails()){
        return $this->sendValidationError('', $validator->errors()->first());
      }
      
      $user = Auth::user();
      if(empty($user)){
		//return $this->sendError('',trans('customer_api.invalid_user'));
      }

      try{
        $data = FirebaseToken::where(['token'=>$request->token])->first();
        if(!empty($data)){
			return $this->sendResponse(new NotificationSettingResource($data), 'Updated Successfully');
        }
        return $this->sendResponse([], 'No data found');
      }catch (\Exception $e) {
        return $this->sendError('', $e->getMessage());
      }
    }
	
	/**
	* Update Settings.
	* @return \Illuminate\Http\Response
	*/
    public function settings(Request $request) {
      $validator = Validator::make($request->all(), [
          'token' 						=> 'required',
          'bus_arrivel_notification' 	=> 'required',
          'other_notification' 			=> 'required'
      ]);
      if($validator->fails()){
        return $this->sendValidationError('', $validator->errors()->first());
      }
      
      $user = Auth::user();
      if(empty($user)){
		//return $this->sendError('',trans('customer_api.invalid_user'));
      }

      try{
        $data = FirebaseToken::where(['token'=>$request->token])->first();
		
        if(!empty($data)){
			$return = $data->where(['token'=>$request->token])->update(['bus_arrivel_notification'=>$request->bus_arrivel_notification, 'other_notification'=>$request->other_notification]);
			if($return){
				return $this->sendResponse([], 'Updated Successfully');
			}
        }
        return $this->sendError('', 'Failed to Update');
      }catch (\Exception $e) {
        return $this->sendError('', $e->getMessage());
      }
    }
}