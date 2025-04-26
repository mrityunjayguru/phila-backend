<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB,Validator,Auth;

use App\Models\FAQTopic;

use App\Http\Resources\FAQListResource;
use App\Http\Resources\FAQTopicListResource;

class FaqController extends BaseController
{
    /**
	* Display a listing of the resource.
	* @return \Illuminate\Http\Response
	*/
    public function index(Request $request){
		
		try{
			$data = FAQTopic::where(['status'=> 'active'])->orderBy('priority', 'ASC')->get();
			if(count($data) > 0){
				return $this->sendArrayResponse(FAQTopicListResource::collection($data),trans('customer_api.data_found_success'));
			}
			return $this->sendResponse('',trans('customer_api.data_found_empty'));
			
		}catch (\Exception $e) {
			return $this->sendError('', $e->getMessage());
		}
	}
}