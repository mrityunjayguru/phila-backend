<?php
namespace App\Http\Controllers\Api\Customer;

use Validator;
use DB,Settings;
use Authy\AuthyApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Helpers\CommonHelper;

use App\Models\Slider;
use App\Models\Slide;
use App\Models\CustomMap;
use App\Http\Resources\SlideListResource;


class SliderController extends BaseController
{
	/**
	* Slider
	* @return \Illuminate\Http\Response
	*/
	public function index(Request $request){
		$validator = Validator::make($request->all(), [
			'slug'  => 'required',
		]);
		if($validator->fails()){
		  return $this->sendValidationError('', $validator->errors()->first());       
		}
		
		$user = Auth::user();
		if(empty($user)){
			//return $this->sendUnauthorizedError('',trans('customer_api.unauthorized_access'));
		}

		try{
			$query = Slider::where(['slug'=>$request->slug])->orderBy('id', 'DESC')->first();
			if($query){
				$slides = Slide::where(['status'=>'active', 'slider_id'=>$query->id])->orderBy('priority', 'ASC')->get();
				return $this->sendArrayResponse(SlideListResource::collection($slides), trans('customer_api.data_found_success'));
			}
			return $this->sendArrayResponse('', trans('customer_api.data_found_empty'));
		}catch (\Exception $e) { 
			return $this->sendError('', $e->getMessage()); 
		}
	}
	
	public function customMap(Request $request){
		
		try{
			$data = CustomMap::find(1);
			if($data){
				$return['image'] = $data->image ? asset($data->image) : asset(config('constants.DEFAULT_THUMBNAIL'));
				return $this->sendResponse($return, trans('customer_api.data_found_success'));
			}
			return $this->sendResponse('', trans('customer_api.data_found_empty'));
		}catch (\Exception $e) { 
			return $this->sendError('', $e->getMessage());
		}
	}
}