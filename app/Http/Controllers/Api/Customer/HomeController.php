<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB,Validator,Auth;

use App\Models\Slider;
use App\Models\Slide;
use App\Models\Stop;
use App\Http\Resources\SlideListResource;
use App\Http\Resources\StopListResource;
use App\Http\Resources\DashboardResource;

class HomeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
	*/
    public function dashboard(Request $request){
		
		try{
			$query = Slider::where(['slug'=> 'home'])->orderBy('id', 'DESC')->first();
			if($query){
				$slider = SlideListResource::collection(Slide::where(['status'=>'active', 'slider_id'=>$query->id])->orderBy('priority', 'ASC')->get());
			}
			
			$data = new \stdClass();
			$data->slider = $query ? $slider : [];
			$data->stops = StopListResource::collection(Stop::where(['status'=> 'active'])->orderBy('priority', 'ASC')->get());
			
			return $this->sendResponse(new DashboardResource($data),trans('customer_api.data_found_success'));
		}catch (\Exception $e) { 
			return $this->sendError('', $e->getMessage());
		}
	}
}