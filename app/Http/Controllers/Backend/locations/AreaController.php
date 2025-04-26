<?php

namespace App\Http\Controllers\backend\Locations;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\City;
use App\Models\Area;
use App\Models\Helpers\CommonHelper;
use App,Validator,Auth,DB,Storage;

class AreaController extends CommonController
{
    use CommonHelper;
	public function __construct()
	{
        $this->middleware('auth');
        //$this->middleware('permission:area-list', ['only' => ['index','show']]);
        //$this->middleware('permission:area-create', ['only' => ['create','store']]);
        //$this->middleware('permission:area-edit', ['only' => ['edit','update']]);
        //$this->middleware('permission:area-delete', ['only' => ['destroy']]);
    }

	// ADD NEW
	public function index(){
		return view('backend.locations.area.list');
	}
  
	// LIST
	public function ajax_list(Request $request){
		$page     = $request->page ?? '1';
		$count    = $request->count ?? '10';
		$status    = $request->status ?? 'all';
		
		if ($page <= 0){ $page = 1; }
		$start  = $count * ($page - 1);
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		try{
			// GET LIST
			$query = Area::query();
			
			$data  = $query->orderBy('id', 'DESC')->offset($start)->limit($count)->get();
			if($data){
				foreach($data as $key=> $list){
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("areas.edit",$list->id) .'"><i class="fa fa-eye"></i></a>
											<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>
											</div>';
					
					if($list->status == 'active') { 
						$data[$key]->status = "<select class='form-control data_status' id='$list->id'>
										<option value='active' selected>Active</option>
										<option value='inactive'>Inactive</option>
									</select>";
					}
					else if($list->status == 'inactive') { 
						$data[$key]->status = "<select class='form-control data_status' id='$list->id'>
										<option value='active'>Active</option>
										<option value='inactive' selected>Inactive</option>
									</select>";
					}
				}
				$this->sendResponse($data, trans('common.data_found_success'));
			}
			$this->sendResponse([], trans('common.data_found_empty'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}

	// CREATE
	public function create(){
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		return view('backend.locations.area.add');
	}
	
	// EDIT
	public function edit($id = null){
		$data = Area::find($id);
		return view('backend.locations.area.edit',compact('data'));
	}
  
	// STORE
	public function store(Request $request){

		$validator = Validator::make($request->all(), [
			'title'				=> 'required|min:3|max:99',
			'pincode'			=> 'required|min:3|max:11',
			'latitude'			=> 'required|min:3|max:51',
			'longitude'			=> 'required|min:3|max:51',
			'status'			=> 'required',
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		
		$user = Auth()->user();
 		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		try{
			$data = [
				'title'       	=> $request->title,
				'priority'		=> $request->priority,
				'pincode'		=> $request->pincode,
				'latitude'		=> $request->latitude,
				'longitude'		=> $request->longitude,
				'status'	  	=> $request->status,
			];
			
			// MEDIA UPLOAD
			if(!empty($request->image)){
				$data['image'] =  $this->saveMedia($request->file('image'));
			}
			
			if($request->item_id){
				// UPDATE
				$query  =  Area::find($request->item_id);
				$query->fill($data);
				$return  =  $query->save();
				if($return){
					$this->sendResponse([], trans('common.updated_success'));
				}
			} else{
				// CREATE
				$return = Area::create($data);
				if($return){
					$this->sendResponse([], trans('common.added_success'));
				}
			}
			$this->ajaxError([], trans('common.try_again'));

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}

    public function change_status(Request $request){
        $validator = Validator::make($request->all(), [
            'status'	=> 'required',
            'item_id'	=> 'required|numeric',
        ]);
        if($validator->fails()){
            $this->ajaxValidationError($validator->errors(), trans('common.error'));
        }

		DB::beginTransaction();
		try {
			$query = Area::where('id',$request->id)->update(['status'=>$request->status]);
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
	
	// DESTROY
	public function destroy(Request $request){
		$validator = Validator::make($request->all(), [
			'item_id' => 'required',
		]);
		if($validator->fails()){
			$this->ajaxError($validator->errors(), trans('common.error'));
		}
		
		DB::beginTransaction();
		try{
			// DELETE
			$query = Area::where(['id'=>$request->item_id])->delete();
			if($query){
				DB::commit();
				$this->sendResponse([], trans('common.delete_success'));
			}
			DB::rollback();
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
}