<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Helpers\CommonHelper;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use App\Models\Offer;
use App\Models\Stop;
use App\Models\Place;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class OfferController extends CommonController
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
        //$this->middleware('permission:offer-list', ['only' => ['index','show']]);
        //$this->middleware('permission:offer-create', ['only' => ['create','store']]);
        //$this->middleware('permission:offer-edit', ['only' => ['edit','update']]);
        //$this->middleware('permission:offer-delete', ['only' => ['destroy']]);
    }
  
	// ADD NEW
	public function index(){
		return view('backend.offers.list');
	}

	// CREATE
	public function create(){
		$offers  = Offer::all();
		$stops   = Stop::where('status', 'active')->orderBy('priority', 'ASC')->get();
		$places   = Place::where('status', 'active')->get();
		
		return view('backend.offers.add',compact('offers', 'stops', 'places'));
	}
	
	// EDIT
	public function edit($id = null){
		$data  	  = Offer::find($id);
		$stops    = Stop::where('status', 'active')->get();
		$places   = Place::where('status', 'active')->get();
		
		return view('backend.offers.update',compact('data','stops', 'places'));
	}
	
	// LIST
	public function ajax(Request $request){
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
			// GET LIST
			$query = Offer::query();
			
			// Filters
			if($request->status != 'all'){
				if($request->status == 'expired'){
					$query->where('end_date', '<', date('Y-m-d'));
				}else{
					$query->where(['status' => $request->status]);
				}
			}
			
			// SEARCH
			if($request->search){
				$query->where('title','like','%'.$request->search.'%');
			}
			$data = $query->orderBy('title', 'ASC')->offset($start)->limit($count)->get();
			
			if($data){
				foreach($data as $key=> $list){
					$delete_btn = '';
					if(Setting::UserPermission('offer-delete')){
						$delete_btn = '<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>';
					}
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("offers.edit",$list->id) .'"><i class="fa fa-eye"></i></a>'. $delete_btn .'
											</div>';
					if($list->place_id){
						$data[$key]['title'] = Place::where('id','=',$list->place_id)->pluck('title')->first();
					}
					$data[$key]['is_expired'] = '0';
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
		if(!empty($request->place_id)){
			$validator = Validator::make($request->all(), [
				'place_id'			 => 'required|min:1|max:99',
				'start_date'         => 'required|date',
				'end_date' 			 => 'required|date|after:start_date',
				'status'             => 'required'
			]);
			if($validator->fails()){
				$this->ajaxValidationError($validator->errors(), trans('common.error'));
			}
		} else {
			$validator = Validator::make($request->all(), [
				'title'   			 => 'required|min:3|max:99',
				'nearest_stop'		 => 'required|min:1|max:99',
				'website'		 	 => 'required|min:1|max:155',
				'start_date'         => 'required|date',
				'end_date' 			 => 'required|date|after:start_date',
				'status'             => 'required'
			]);
			if($validator->fails()){
				$this->ajaxValidationError($validator->errors(), trans('common.error'));
			}
		}
		
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
	
		if(isset($request->item_id)){
			$validator = Validator::make($request->all(), [
				'item_id' => 'required',
			]);
			if($validator->fails()){
				$this->ajaxValidationError($validator->errors(), trans('common.error'));
			}
		}
		
		try{
			$data = [
				'title'      	=> $request->title,
				'nearest_stop'	=> $request->nearest_stop,
				'description'	=> $request->description,
				'website'      	=> $request->website,
				'start_date'    => date('Y-m-d',strtotime($request->start_date)),
				'end_date'      => date('Y-m-d',strtotime($request->end_date)),
				'status'        => $request->status,
			];
			if(!empty($request->place_id)){
				
				$place = Place::where('id', $request->place_id)->first();
				if(empty($place)){
					$this->ajaxError([], trans('common.invalid_user'));
				}
				$data['website'] 	  = '';
				$data['place_id'] 	  = $request->place_id;
				$data['type'] 	 	  = $place->type;
				$data['nearest_stop'] = $place->nearest_stop;
			}
			
			// MEDIA UPLOAD
			if(!empty($request->image) && $request->image != 'undefined'){
				$validator = Validator::make($request->all(), [
					'image'	=> 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
				]);
				if($validator->fails()){
					$this->ajaxValidationError($validator->errors(), trans('common.error'));
				}
				$data['image'] =  $this->saveMedia($request->file('image'));
			}
			
			if($request->item_id){
				// UPDATE
				$Offer  =  Offer::find($request->item_id);
				$Offer->fill($data);
				$return  =  $Offer->save();
				
				if($return){
					$this->sendResponse([], trans('common.updated_success'));
				}
			} else{
				// CREATE
				$return = Offer::create($data);
				if($return){
					$this->sendResponse([], trans('common.added_success'));
				}
			}
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
			$query = Offer::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}