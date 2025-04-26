<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use App\Models\Helpers\CommonHelper;
use App\Models\Slider;
use App\Models\Slide;
use App\Models\Setting;

class SliderController extends CommonController
{   
	use CommonHelper;
	
	public function __construct()
	{
 		$this->middleware('auth');
		//$this->middleware('permission:slider-list', ['only' => ['index','show']]);
		//$this->middleware('permission:slider-create', ['only' => ['create','store']]);
		//$this->middleware('permission:slider-edit', ['only' => ['edit','update']]);
		//$this->middleware('permission:slider-delete', ['only' => ['destroy']]);
	}
	
	/**
	* --------------------
	* Slider List
	* Show the list page
	* --------------------
	*/
	public function index(){
		return view('backend.sliders.list');
	}

	/**
	* --------------------
	* Create Slider
	* Show the create page
	* --------------------
	*/
	public function create(){

		return view('backend.sliders.add');
	}
	
	/**
	* --------------------
	* Edit Slider
	* Show the edit page
	* --------------------
	*/
	public function edit($id = null){
		$data	= Slider::find($id);
		$slides = Slide::where('slider_id', $data->id)->get();
		return view('backend.sliders.edit',compact('data', 'slides'));
	}
	
	public function slideBox(Request $request){

		try{
			$html = '<div class="form-row">
						<div class="col-md-6">
							<div class="position-relative form-group">
								<label for="title" class="">Title</label>
								<input type="text" id="title" placeholder="Enter Title" class="form-control">
								<div class="validation-div" id="val-title"></div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="position-relative form-group">
								<label for="priority">Priority</label>
								<input type="number" id="priority" placeholder="Enter Priority" class="form-control">
								<div class="validation-div" id="val-priority"></div>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-6">
							<div class="position-relative form-group">
								<label for="is_clickable" class="">Is Clickable?</label>
								<select class="form-control" id="is_clickable">
									<option value=""> Is Clickable...</option>
									<option value="Yes"> Yes </option>
									<option value="No"> No </option>
								</select>
							</div>
							<div class="position-relative form-group">
								<label for="redirect_to">Redirect To</label>
								<input type="text" id="redirect_to" placeholder="Enter URL or model name" class="form-control">
								<div class="validation-div" id="val-redirect_to"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-row">
								<div class="col-md-12">
									<div class="position-relative form-group">
										<label for="exampleFile" class="">Image</label>
										<input name="file" id="image" type="file" class="form-control-file item-img" accept="image/*">
										<div class="validation-div" id="val-image"></div>
										<div class="image-preview"><img id="image-src" src="" width="100%"/></div>
										<input type="hidden" id="item_id" value="">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-12">
							<div class="position-relative form-group">
								<label for="description" class="">Description</label>
								<textarea name="description" id="description" rows="4" class="form-control"></textarea>
								<div class="validation-div" id="val-description"></div>
							</div>
						</div>
					</div>';
			if(!empty($request->item_id)){
				$data = Slide::find($request->item_id);
				if($data){
					$image = '';
					$yes = '';
					$no = '';
					if($data->image){ $image = asset($data->image); }
					if($data->is_clickable == 'Yes'){ $yes = 'selected'; }
					if($data->is_clickable == 'No'){ $no = 'selected'; }
					$html = '<div class="form-row">
							<div class="col-md-6">
								<div class="position-relative form-group">
									<label for="title" class="">Title</label>
									<input type="text" id="title" placeholder="Enter Title" class="form-control" value="'. $data->title .'">
									<div class="validation-div" id="val-title"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="position-relative form-group">
									<label for="priority">Priority</label>
									<input type="number" id="priority" placeholder="Enter Priority" class="form-control" value="'. $data->priority .'">
									<div class="validation-div" id="val-priority"></div>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-6">
								<div class="position-relative form-group">
									<label for="is_clickable" class="">Is Clickable?</label>
									<select class="form-control" id="is_clickable">
										<option value=""> Is Clickable...</option>
										<option value="Yes" '. $yes .'> Yes </option>
										<option value="No" '. $no .'> No </option>
									</select>
								</div>
								<div class="position-relative form-group">
									<label for="redirect_to">Redirect To</label>
									<input type="text" id="redirect_to" placeholder="Enter URL or model name" class="form-control" value="'. $data->redirect_to .'">
									<div class="validation-div" id="val-redirect_to"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-row">
									<div class="col-md-12">
										<div class="position-relative form-group">
											<label for="exampleFile" class="">Image</label>
											<input name="file" id="image" type="file" class="form-control-file item-img" accept="image/*">
											<div class="validation-div" id="val-image"></div>
											<div class="image-preview"><img id="image-src" src="'. $image .'" width="100%"/></div>
											<input type="hidden" id="item_id" value="'. $data->id .'">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-12">
								<div class="position-relative form-group">
									<label for="description" class="">Description</label>
									<textarea  name="description" id="description"  rows="4" class="form-control">'. $data->description .'</textarea>
									<div class="validation-div" id="val-description"></div>
								</div>
							</div>
						</div>';
				}
				$this->sendResponse($html, trans('common.data_found_success'));
			}else{
				$this->sendResponse($html, trans('common.data_found_success'));
			}
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	
	// Ajax List
	public function ajax_list(Request $request){
		try{
			$query = Slider::orderBy('id','DESC')->get();
			if($query){
				foreach($query as $key=> $list){
					$delete_btn = '';
					if(Setting::UserPermission('slider-delete')){
						$delete_btn = '<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>';
					}
					$query[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("sliders.edit",$list->id) .'"><i class="fa fa-eye"></i></a>'. $delete_btn .'
											</div>';
					if($list->image){ $query[$key]->image  = asset($list->image); }else { $query[$key]->image  = asset(config('constants.DEFAULT_MENU_IMAGE')); }
					$list->slides = Slide::where('slider_id', $list->id)->count();
				}
				$this->sendResponse($query, trans('common.data_found_success'));
			}
			$this->sendResponse([], trans('common.data_found_empty'));

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
  
	// STORE
	public function store(Request $request){
		$validator = Validator::make($request->all(), [
			'title'				=> 'required|min:3|max:99',	
			'device'			=> 'required|min:3|max:21',	
			'slug'				=> 'required|min:3|max:51',	
			'status'			=> 'required',
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		
		$user = Auth()->user();
 		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		DB::beginTransaction();
		try{
			$data = [
				'title'       	=> $request->title,
				'device'		=> $request->device,
				'slug' 			=> $request->slug,
				'status'	  	=> $request->status,
			];
			
			if($request->item_id){
				// UPDATE
				$query  =  Slider::find($request->item_id);
				$query->fill($data);
				$return  =  $query->save();
				if($return){
					DB::commit();
					$this->sendResponse([], trans('common.updated_success'));
				}
			} else{
				// CREATE
				$return = Slider::create($data);
				if($return){
					DB::commit();
					$this->sendResponse([], trans('common.added_success'));
				}
			}
			DB::rollback();
			$this->ajaxError([], trans('common.try_again'));
			
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	// Change Status
	public function change_status(Request $request){
		$validator = Validator::make($request->all(), [
			'id' => 'required',
		]);
		if($validator->fails()){
			$this->ajaxError($validator->errors(), trans('common.error'));
		}
		
		DB::beginTransaction();
	    try {
	        $query = Slider::where('id',$request->id)->update(['status'=>$request->status]);
	        if($query){
	          DB::commit();
	          $this->sendResponse(['status'=>'success'], trans('common.status_updated_successfully'));
	        }
			else{
	          DB::rollback();
	          $this->sendResponse(['status'=>'error'], trans('common.status_not_updated'));
	        }
			
			$this->sendResponse(['status'=>'error'], trans('common.status_not_updated'));
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
		
		try{
			// DELETE
			$query = Slider::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	
	// Store Slide
	public function storeSlide(Request $request){
		$validator = Validator::make($request->all(), [
			'slider_id'	=> 'required',
			'title'		=> 'required|min:3|max:99',
			'priority'	=> 'required|min:1|max:99',
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		
		$user = Auth()->user();
 		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		DB::beginTransaction();
		try{
			$data = [
				'slider_id'		=> $request->slider_id,
				'title'       	=> $request->title,
				'priority'		=> $request->priority,
				'is_clickable'	=> $request->is_clickable ? $request->is_clickable : 'No',
				'redirect_to'	=> $request->redirect_to,
				'description'	=> $request->description,
			];
			
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
				$query = Slide::find($request->item_id);
				$query->fill($data);
				$return = $query->save();
				if($return){
					DB::commit();
					$this->sendResponse([], trans('common.updated_success'));
				}
			} else{
				
				if(empty($request->image) && $request->image != 'undefined'){
					$validator = Validator::make($request->all(), [
						'image'	=> 'require|image|mimes:jpeg,png,jpg|max:1024',
					]);
					if($validator->fails()){
						$this->ajaxValidationError($validator->errors(), trans('common.error'));
					}
					$data['image'] =  $this->saveMedia($request->file('image'));
				}
				
				// CREATE
				$return = Slide::create($data);
				if($return){
					DB::commit();
					$this->sendResponse([], trans('common.added_success'));
				}
			}
			DB::rollback();
			$this->ajaxError([], trans('common.try_again'));
			
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	// Delete Slide
	public function destroy_slide(Request $request){
		$validator = Validator::make($request->all(), [
			'item_id' => 'required',
		]);
		if($validator->fails()){
			$this->ajaxError($validator->errors(), trans('common.error'));
		}
		
		try{
			// DELETE
			$query = Slide::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}