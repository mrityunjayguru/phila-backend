<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB,Validator,Auth;
use App\Models\Offer;
use App\Models\Stop;
use App\Http\Resources\OfferResource;
use App\Http\Resources\OfferListResource;
use App\Http\Resources\StopListResource;

class OfferController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
	*/
    public function index(Request $request){
      
      $order_by = "id";
      $order    = "DESC";
      $search = $request->search;
      $page   = $request->page ?? '0';
      $count  = $request->count ?? '100';

      if ($page <= 0){ $page = 1; }
      $start  = $count * ($page - 1);

		try{
			$query = Offer::query()->where(['status'=>'active'])->where('end_date', '>=', date('Y-m-d'));
			
			/* FILTERS */
			if($search){
				$query->where('title','like','%'.$search.'%');
			}
			
			if($request->nearest_stop){
				$query->where('nearest_stop',$request->nearest_stop);
			}
			
			if($request->type){
				$query->where('type',$request->type);
			}

			// SOONEST FIRST 
			if($request->soonest_first){
				$order_by = 'end_date';
				$order    = 'ASC';
			}

			// CHIPEST FIRST 
			if($request->chipest_price){
				$order_by = 'offer_price';
				$order    = 'ASC';
			}

			$query = $query->orderBy($order_by, $order)->offset($start)->limit($count)->get();
			if(count($query) > 0){
				return $this->sendArrayResponse(OfferListResource::collection($query),trans('customer_api.data_found_success'));
			}
			return $this->sendArrayResponse([],trans('customer_api.data_found_empty'));
		}catch (\Exception $e) { 
			return $this->sendError('', $e->getMessage());
		}
	}
	
	// OFFER DETAILS
    public function stops() {
      
		try{
			$data = StopListResource::collection(Stop::where(['status'=> 'active'])->orderBy('priority', 'ASC')->get());
			return $this->sendArrayResponse($data,trans('customer_api.data_found_success'));
			
		}catch (\Exception $e) { 
			return $this->sendError('', $e->getMessage()); 
		}
    }
}