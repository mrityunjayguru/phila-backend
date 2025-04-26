<?php

namespace App\Http\Controllers\backend\Locations;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Country;
use App\Models\City;
use App\Models\Helpers\CommonHelper;
use App,Validator,Auth,DB,Storage;

class CityController extends CommonController
{
    use CommonHelper;


    public function index() {
        $countries = Country::where('status','active')->get();
        $state = State::where(array('country_id' => 53, 'status' => 'active'))->get();
		return view('backend/locations/city/list', compact('countries', 'state'));
	}

    public function ajax_list(Request $req){    
        $user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
        $query = City::query();
        if(!empty($req->status) && $req->status != 'all'){
            $query->where('status', $req->status);
        }
        $data = $query->where('state_id',$req->state_id)->paginate($req->perPage);
        if(count($data) > 0){
            $this->sendArrayResponse($data,  trans('common.data_found_success'));
        }else{
            $this->sendResponse([], trans('common.data_found_empty'));
        }
    }

    public function add_cities(Request $req){
        $validator = Validator::make($req->all(), [
            'checkoperation'    => 'required',
            'state_id'        => 'required|numeric',
            'city_name'		=> 'required',
            'status'			=> 'required',
        ]);
        if($validator->fails()){
            $this->ajaxValidationError($validator->errors(), trans('common.error'));
        }

        $cityData = array(
            'state_id' => $req->state_id,
            'name' => $req->city_name,
            'status' => $req->status
        );
        if($req->checkoperation == 'add_section'){
            //----- Add Section-----
            if(City::create($cityData)){
                $this->sendResponse([], 'City added successfully');
            }else{
                $this->sendResponse([], 'Something went wrong try again after some time.');
            }
        }else if(!empty($req->edit_id) && $req->checkoperation == 'update_section'){
            //----- Update Section -----
            if(City::where('id', $req->edit_id)->update($cityData)){
                $this->sendResponse([], "City updated successfully");
            }else{
                $this->sendResponse([], trans('common.updated_error'));
            }
        }
    }

    public function editDeleteOperation(Request $req){
        $user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}

        $validator = Validator::make($req->all(), [
            'status_filter'			=> 'required',
            'city_id'      => 'required|numeric',
        ]);
        if($validator->fails()){
            $this->ajaxValidationError($validator->errors(), trans('common.error'));
        }

        if($req->status_filter == 'edit_section'){
            $res_json = json_decode(json_encode(City::find($req->city_id)),true);
            if($res_json != null){
                $this->sendResponse($res_json, trans('common.data_found_success'));
            }else{
                $this->sendResponse([], trans('common.data_found_empty'));
            }
        }

    }

    public function change_status(Request $request){
        $validator = Validator::make($request->all(), [
            'status'			=> 'required',
            'id'      => 'required|numeric',
        ]);
        if($validator->fails()){
            $this->ajaxValidationError($validator->errors(), trans('common.error'));
        }

		DB::beginTransaction();
		try {
			$query = City::where('id',$request->id)->update(['status'=>$request->status]);
			if($query){
			  DB::commit();
			  $this->sendResponse(['status'=>'success'], trans('common.updated_success'));
			}else{
			  DB::rollback();
			  $this->sendResponse(['status'=>'error'], trans('common.updated_error'));
			}
			
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
}
