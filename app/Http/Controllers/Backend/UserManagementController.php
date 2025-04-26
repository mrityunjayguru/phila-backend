<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;

use App\Models\Helpers\CommonHelper;
use App\Models\UserInfo;
use App\Models\User;
use App\Models\Permission;
use App\Models\UserPermissions;
use App\Models\Setting;

class UserManagementController extends CommonController
{   
	use CommonHelper;
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct()
	{
		//$this->middleware('permission:user-management-list', ['only' => ['index','ajax','show']]);
		//$this->middleware('permission:user-management-create', ['only' => ['create','store']]);
		//$this->middleware('permission:user-management-edit', ['only' => ['edit','update']]);
		//$this->middleware('permission:user-management-delete', ['only' => ['destroy']]);
	}
  
	// LIST
	public function index($role = ''){
		try{
			$page_title = trans('user_management.update');
			
			return view("backend.user-management.list", compact(['page_title', 'role']));
			
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	public function create($role = ''){
		$page_title  = trans('user_management.create');
		$permissions = Permission::get();
		return view("backend.user-management.add", compact(['page_title', 'role', 'permissions']));
	}

	// DETAILS PAGE
	public function show($role = null, $id = ''){
		try {
			$page_title = trans('user.update');
			$data 		= User::where('id',$id)->first();
			if($data){
				
				$permissions = Permission::get();
				return view("backend.user-management.edit", compact(['page_title','data', 'role', 'permissions']));
			}
			return redirect()->route('homePage');
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	// AJAX LIST
	public function ajax_list(Request $request){
		$page		= $request->page ?? '1';
		$count		= $request->count ?? '10';
		
		if ($page <= 0){ $page = 1; }
		$start  = $count * ($page - 1);
		
		try{
			$query = User::where('user_type', $request->role);
			
			/* STATUS */
			if(!empty($request->status) && $request->status != 'all'){
				$query->where('status', $request->status);
			}
			
			// SEARCH
			if($request->search){
				$query->where('name','like','%'.$request->search.'%');
			}
			$data = $query->orderBy('id', 'DESC')->offset($start)->limit($count)->get();
			if(count($data) > 0){
				foreach($data as $key=> $list){
					$delete_btn = '';
					if(Setting::UserPermission('user-delete')){
						$delete_btn = '<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>';
					}
					if($list->id == '2'){
						$delete_btn = '';
					}
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. url('backend/manage/'. $request->role .'/'.$list->id) .'"><i class="fa fa-eye"></i></a>'. $delete_btn .'
											</div>';
					if($list->profile_image){ $data[$key]->image  = asset($list->profile_image); }else { $data[$key]->image  = asset(config('constants.DEFAULT_USER_IMAGE')); }
				}
				$this->sendResponse($data, trans('common.data_found_success'));
			}
			$this->sendResponse([], trans('common.data_found_empty'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	
	// AJAX LIST
	public function permissions(Request $request){
		try{
			$data = UserPermissions::where(['user_id'=>$request->user_id])->orderBy('id', 'DESC')->get();
			if(count($data) > 0){
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
			'role'			=> 'required',
			'name'			=> 'required|min:3|max:99',
			'email'			=> 'required|min:3|max:99',
			//'phone_number'	=> 'required|min:3|max:99',
			'status'		=> 'required',
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		
		// If User role == Doctor
		if($request->role == 'Doctor'){
			$validator = Validator::make($request->all(), [
				'speciality'	=> 'required',
			]);
			if($validator->fails()){
				$this->ajaxValidationError($validator->errors(), trans('common.error'));
			}
		}
		
		$user = Auth()->user();
 		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		try{
			$data = [
				'user_type'		=> $request->role,
				'name'       	=> $request->name,
				'email'			=> $request->email,
				'phone_number' 	=> $request->phone_number,
				'status'	  	=> $request->status,
			];
			
			// MEDIA UPLOAD
			if(!empty($request->image) && $request->image != 'undefined'){
				$validator = Validator::make($request->all(), [
					'profile_image'	=> 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
				]);
				if($validator->fails()){
					$this->ajaxValidationError($validator->errors(), trans('common.error'));
				}
				$data['profile_image'] =  $this->saveMedia($request->file('image'));
			}
			
			if(!empty($request->password)){
				
				$validator = Validator::make($request->all(), [
					'password'	=> 'required|min:8|max:51',
				]);
				if($validator->fails()){
					$this->ajaxValidationError($validator->errors(), trans('common.error'));
				}
				
				$data['password'] = Hash::make($request->password);
			}
			
			if($request->user_id){
				
				// CHECK EMAIL EXIST OR NOT
				$email = User::where('email', $request->email)->whereNotIn('id', [$request->user_id])->first();
				if(!empty($email)){
					return $this->ajaxError('',trans('customer_api.email_already_exist'));
				}
		
				// CHECK MOBILE NO EXIST OR NOT
				if(!empty($phone_number)){
					$phone_number = User::where('phone_number', $request->phone_number)->whereNotIn('id', [$request->user_id])->first();
					if(!empty($phone_number)){
						return $this->ajaxError('',trans('customer_api.phone_number_already_exist'));
					}
				}
				
				// UPDATE
				$query  =  User::find($request->user_id);
				$query->fill($data);
				$return  =  $query->save();
				if($return){
					
					// Update User Permissions
					$permissions = explode(',', $request->permissions);
					if(!empty($permissions)){
						UserPermissions::where(['user_id'=>$request->user_id])->delete();
						foreach($permissions as $list){
							UserPermissions::firstOrCreate(array('user_id' => $request->user_id, 'permission_id' => $list));
						}
					}
					
					$this->sendResponse([], trans('common.updated_success'));
				}
			} else{
				
				// CHECK EMAIL EXIST OR NOT
				$email = User::where('email', $request->email)->first();
				if(!empty($email)){
					return $this->ajaxError('',trans('customer_api.email_already_exist'));
				}
				
				// CHECK MOBILE NO EXIST OR NOT
				if(!empty($phone_number)){
					$phone_number = User::where('phone_number', $request->phone_number)->first();
					if(!empty($phone_number)){
						return $this->ajaxError('',trans('customer_api.phone_number_already_exist'));
					}
				}
				
				if(empty($request->password)){
					return $this->ajaxError('','Invalid Password');
				}
				
				// CREATE
				$return = User::create($data);
				if($return){
					
					// Create Usder Info
					$data = ['user_id' => $return->id];
					UserInfo::create($data);
					
					// Create User Permissions
					$permissions = explode(',', $request->permissions);
					if(!empty($permissions)){
						foreach($permissions as $list){
							UserPermissions::Create(array('user_id' => $return->id, 'permission_id' => $list));
						}
					}
					
					$this->sendResponse([], trans('common.added_success'));
				}
			}
			$this->ajaxError([], trans('common.try_again'));

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	/**
	* --------------
	* Change Status
	* --------------
	*/
	public function change_status(Request $request){
		DB::beginTransaction();
		try {
			$query = User::where('id',$request->id)->update(['status'=>$request->status]);
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
	
	/**
	* --------------
	* DESTROY
	* --------------
	*/
	public function destroy(Request $request){
		
		$validator = Validator::make($request->all(), [
			'item_id' => 'required',
		]);
		if($validator->fails()){
			$this->ajaxError($validator->errors(), trans('common.error'));
		}
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		if($request->item_id == '2'){
			$this->ajaxError([], 'Unable to delete administrator');
		}
		
		try{
			if($user->id == $request->item_id){
				$this->ajaxError([], 'Unable to delete login user');
			}
			// DELETE
			$query = User::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}