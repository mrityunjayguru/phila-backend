<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LandingPage;
use App\AudioCode;
use App\Models\Bus;
use Illuminate\Validation\ValidationException;
use App\Models\Helpers\CommonHelper;
use App\Models\Setting;
use Validator;

class AudioCodeController extends CommonController
{
    use CommonHelper;
    //

    public function __construct()
	{
 		$this->middleware('auth');
		//$this->middleware('permission:brand-list', ['only' => ['index','show']]);
		//$this->middleware('permission:brand-create', ['only' => ['create','store']]);
		//$this->middleware('permission:brand-edit', ['only' => ['edit','update']]);
		//$this->middleware('permission:brand-delete', ['only' => ['destroy']]);
	}
  
	// ADD NEW
	public function index($id){
        $page_id = $id;
		return view('backend.codes.index',compact('page_id'));
	}

	// CREATE
	public function create($page_id){
		$buses = Bus::where('status', 'active')->get();
		return view('backend.codes.add', compact('buses','page_id'));
	}
	
	// EDIT
	public function edit($page_id,$id){
		$data  = AudioCode::find($id);
		$buses = Bus::where('status', 'active')->get();
		return view('backend.codes.edit',compact('data','buses','page_id'));
	}
  
	public function ajax_list(Request $request){
		$page     = $request->page ?? '1';
		$count    = $request->count ?? '100';
		$status    = $request->status ?? 'all';
		
		if ($page <= 0){ $page = 1; }
		$start  = $count * ($page - 1);
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		try{
			$query = AudioCode::query();
			
			// Filters
			if($request->status != 'all'){
				$query->where(['status' => $request->status]);
			}
			
			if($request->ticket_number){
				$query->where(['ticket_number' => $request->ticket_number]);
			}
			
			if($request->customer_name){
				//$query->where(['customer_name' => $request->customer_name]);
			}
            $query->where('page_id',$request->page_id);
			
			$data  = $query->orderBy('id', 'DESC')->offset($start)->limit($count)->get();
			if($data){
				foreach($data as $key=> $list){
					$delete_btn = '';
					if(Setting::UserPermission('ticket-delete')){
						$delete_btn = '<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>';
					}
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("codes.edit", ['page_id' => $request->page_id, 'id' => $list->id]).'"><i class="fa fa-eye"></i></a>'. $delete_btn .'
											</div>';
					$data[$key]['is_expired'] = '0';
					if($list->status  == 'inactive'){
						$data[$key]['is_expired'] = 'background-color: #f1d2d6;';
					}
					if($list->end_date < date('Y-m-d')){
						$data[$key]['is_expired'] = 'background-color: #f1d2d6;';
						$data[$key]['status'] = 'Expired';
					}
				}
				$this->sendResponse($data, trans('common.data_found_success'));
			}
			$this->sendResponse([], trans('common.data_found_empty'));

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	// STORE
	public function store(Request $request){

        // dd($request->all());
		$validator = Validator::make($request->all(), [
			'ticket_number'		=> 'required|min:1|max:99|unique:tickets,ticket_number,'.$request->item_id,
			'end_date'			=> 'required|date_format:Y-m-d|after:yesterday',
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
				'page_id'		=> $request->page_id,
				'ticket_number'		=> $request->ticket_number,
				'customer_name'		=> $request->customer_name,
				'customer_number'	=> $request->customer_number,
				'bus'				=> $request->bus,
				'end_date'			=> $request->end_date,
				'status'	  		=> $request->status,
			];
			
			if($request->item_id){
				
				// Validation
				if($request->item_id){
					$validator = Validator::make($request->all(), [
						'item_id' => 'required',
					]);
					if($validator->fails()){
						$this->ajaxValidationError($validator->errors(), trans('common.error'));
					}
				}
				
				// UPDATE
				$ticket = AudioCode::find($request->item_id);
				$ticket->fill($data);
				$return = $ticket->save();
				
				if($return){
					$this->sendResponse([], trans('common.updated_success'));
				}
			} else{
				// CREATE
				$return = AudioCode::create($data);
				if($return){
					$this->sendResponse([], trans('common.added_success'));
				}
			}
			$this->ajaxError([], trans('common.try_again'));

		} catch (Exception $e) {
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
		
		try{
			// DELETE
			$query = AudioCode::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}
