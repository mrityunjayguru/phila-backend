<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use App\Models\Helpers\CommonHelper;
use App\Models\Faq;
use App\Models\FAQTopic;

class FaqController extends CommonController
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
	}
  
	// FAQ LIST
	public function index(){
		return view('backend.faq.list');
	}

	// CREATE
	public function create(){
		
		$topics = FAQTopic::where('status', 'active')->get();
		return view('backend.faq.add', compact('topics'));
	}
	
	// EDIT
	public function edit($id = null){
		$data 	= Faq::find($id);
		$topics = FAQTopic::where('status', 'active')->get();
		return view('backend.faq.edit',compact('data', 'topics'));
	}
  
	public function ajax_list(Request $request){
		$page     = $request->page ?? '1';
		$count    = $request->count ?? '20';
		$status    = $request->status ?? 'all';
		
		if ($page <= 0){ $page = 1; }
		$start  = $count * ($page - 1);
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		try{
			$query = Faq::query();
			
			// Filters
			if($request->status != 'all'){
				$query->where(['status' => $request->status]);
			}
			
			$data  = $query->orderBy('id', 'DESC')->offset($start)->limit($count)->get();
			if($data){
				foreach($data as $key=> $list){
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("faqs.edit",$list->id) .'"><i class="fa fa-eye"></i></a>
											<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>
											</div>';
					$topic = FAQTopic::where('id', $list->topic)->pluck('title')->first();
					$data[$key]->topic = $topic ? $topic : '';
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
			'title'			=> 'required|min:1|max:99',
			'priority'		=> 'required|min:1|max:99',
			'topic'			=> 'required|min:1|max:99',
			'description'	=> 'required|min:1|max:999',
			'status'		=> 'required',
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
				'title'				=> $request->title,
				'priority'			=> $request->priority,
				'topic'				=> $request->topic,
				'description'		=> $request->description,
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
				$faq = Faq::find($request->item_id);
				$faq->fill($data);
				$return = $faq->save();
				
				if($return){
					$this->sendResponse([], trans('common.updated_success'));
				}
			} else{
				// CREATE
				$return = Faq::create($data);
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
			$query = Faq::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}