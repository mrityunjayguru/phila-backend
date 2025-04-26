<?php

namespace App\Http\Controllers\backend\Locations;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Country;
use App\Models\Helpers\CommonHelper;
use App,Validator,Auth,DB,Storage;

class StateController extends CommonController
{
    use CommonHelper;


    public function index() {
        $countries = Country::where('status','active')->get();
		return view('backend/locations/state.list', compact('countries'));
	}

    public function ajax_list(Request $request){    
        $user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
        
		$query = State::query();
        if(!empty($request->status) && $request->status != 'all'){
            $query->where('status', $request->status);
        }
        $data = $query->where('country_id',$request->country_id)->paginate($request->count);
        if(count($data) > 0){
            $this->sendArrayResponse($data, trans('common.data_found_success'));
        }else{
            $this->sendArrayResponse([], trans('common.data_found_empty'));
        }
    }

    public function add_states(Request $req){
        $validator = Validator::make($req->all(), [
            'checkoperation'    => 'required',
            'country_id'        => 'required|numeric',
            'state_name'		=> 'required',
            'status'			=> 'required',
        ]);
        if($validator->fails()){
            $this->ajaxValidationError($validator->errors(), trans('common.error'));
        }

        $stateData = array(
            'country_id' => $req->country_id,
            'name' => $req->state_name,
            'status' => $req->status
        );
        if($req->checkoperation == 'add_section'){
            //----- Add Section-----
            if(State::create($stateData)){
                $this->sendResponse([], 'State added successfully');
            }else{
                $this->sendResponse([], 'Something went wrong try again after some time.');
            }
        }else if(!empty($req->edit_id) && $req->checkoperation == 'update_section'){
            //----- Update Section -----
            if(State::where('id', $req->edit_id)->update($stateData)){
                $this->sendResponse([], trans('common.updated_success'));
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
            'status_filter'	=> 'required',
            'state_id'      => 'required|numeric',
        ]);
        if($validator->fails()){
            $this->ajaxValidationError($validator->errors(), trans('common.error'));
        }

        if($req->status_filter == 'edit_section'){
            $res_json = json_decode(json_encode(State::find($req->state_id)),true);
            if($res_json != null){
                $this->sendResponse($res_json, trans('common.data_found_success'));
            }else{
                $this->sendResponse([], trans('common.data_found_empty'));
            }
        }

    }

    public function change_status(Request $request){
        $validator = Validator::make($request->all(), [
            'status'	=> 'required',
            'id'		=> 'required|numeric',
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

    public function getStateByCountryId(Request $req) {
        $validator = Validator::make($req->all(), [
            'country_id'      => 'required|numeric',
        ]);
        if($validator->fails()){
            $this->ajaxValidationError($validator->errors(), trans('common.error'));
        }

        $data = json_decode(json_encode(State::where('status', 'active')->get()), true);
        if(count($data)){
            $this->sendResponse($data, trans('common.data_found_success'));
        }else{
            $this->sendResponse([], trans('common.data_found_empty'));
        }

    }
}
