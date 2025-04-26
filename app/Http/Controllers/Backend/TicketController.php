<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use App\Models\Helpers\CommonHelper;
use App\Models\Ticket;
use App\Models\Bus;
use App\Models\Setting;

class TicketController extends CommonController
{   
	use CommonHelper;
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct()
	{
 		$this->middleware('auth');
		//$this->middleware('permission:brand-list', ['only' => ['index','show']]);
		//$this->middleware('permission:brand-create', ['only' => ['create','store']]);
		//$this->middleware('permission:brand-edit', ['only' => ['edit','update']]);
		//$this->middleware('permission:brand-delete', ['only' => ['destroy']]);
	}
  
	// ADD NEW
	public function index(){
		return view('backend.tickets.list');
	}

	// CREATE
	public function create(){

		$buses = Bus::where('status', 'active')->get();
		return view('backend.tickets.add', compact('buses'));
	}
	
	// EDIT
	public function edit($id = null){
		$data  = Ticket::find($id);
		$buses = Bus::where('status', 'active')->get();
		return view('backend.tickets.edit',compact('data','buses'));
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
			$query = Ticket::query();
			
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
			
			$data  = $query->orderBy('id', 'DESC')->offset($start)->limit($count)->get();
			if($data){
				foreach($data as $key=> $list){
					$delete_btn = '';
					if(Setting::UserPermission('ticket-delete')){
						$delete_btn = '<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>';
					}
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("tickets.edit",$list->id) .'"><i class="fa fa-eye"></i></a>'. $delete_btn .'
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
				$ticket = Ticket::find($request->item_id);
				$ticket->fill($data);
				$return = $ticket->save();
				
				if($return){
					$this->sendResponse([], trans('common.updated_success'));
				}
			} else{
				// CREATE
				$return = Ticket::create($data);
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
			$query = Ticket::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}