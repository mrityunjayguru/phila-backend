<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB,Validator,Auth;
use App\Models\Coupon;
use App\Http\Resources\OfferResource;
use App\Http\Resources\OfferListResource;

class CouponController extends BaseController
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
			$query = Coupon::query()->where(['status'=>'active'])->where('end_date', '>=', date('Y-m-d'));
			
			/* FILTERS */
			if($search){
				$query->where('title','like','%'.$search.'%');
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
		   
			if(!empty($query)){
				return $this->sendArrayResponse(OfferListResource::collection($query),trans('customer_api.data_found_success'));
			}
			return $this->sendArrayResponse('',trans('customer_api.data_found_empty'));
		}catch (\Exception $e) { 
			return $this->sendError('', $e->getMessage());
		}
	}

    // OFFER DETAILS
    public function show($id = null) {
      
      try{
        $query = Coupon::where('id', $id)->first();
        if(empty($query)){ return $this->sendResponse('', trans('customer_api.data_found_empty')); }

        return $this->sendResponse(new OfferResource($query), trans('customer_api.data_found_success'));
      }catch (\Exception $e) { 
		return $this->sendError('', $e->getMessage()); 
      }
    }
}
