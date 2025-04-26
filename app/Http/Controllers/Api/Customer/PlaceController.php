<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB,Validator,Auth;

use App\Models\Place;
use App\Http\Resources\PlaceResource;
use App\Http\Resources\PlaceListResource;

class PlaceController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
	*/
    public function index(Request $request){
		
		try{
			$query = Place::where(['status'=> 'active']);
			
			if($request->type){
				$query->where(['type'=> $request->type]);
			}
			
			$data = $query->orderBy('title', 'ASC')->get();
			if(count($data) > 0){
				return $this->sendArrayResponse(PlaceListResource::collection($data),trans('customer_api.data_found_success'));
			}
			return $this->sendArrayResponse('',trans('customer_api.data_found_empty'));
			
		}catch (\Exception $e) {
			return $this->sendError('', $e->getMessage());
		}
	}
	
	// OFFER DETAILS
    public function show($id = null) {
      try{
        $query = Place::where('id', $id)->first();
        if(empty($query)){ return $this->sendResponse('', trans('customer_api.data_found_empty')); }

        return $this->sendResponse(new PlaceResource($query), trans('customer_api.data_found_success'));
      }catch (\Exception $e) { 
		return $this->sendError('', $e->getMessage());
      }
    }
}