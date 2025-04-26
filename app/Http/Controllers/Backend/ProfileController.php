<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\Helpers\CommonHelper;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class ProfileController extends CommonController
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
  
	/**
	*--------------------------
	* View for Change Password
	*--------------------------
	*/
	public function change_password(){
		return view('backend.profile.change-password');
	}
	
	/**
	*--------------------------
	* Change Password
	*--------------------------
	*/
	public function ajax_change_password(Request $request){
	    
		$validator = Validator::make($request->all(), [
			'old_password'				=> 'required|min:3|max:99',
			'password' 					=> ['required', 'string', 'min:8', 'confirmed'],
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		DB::beginTransaction();
	    try {
			
			$user = User::findOrFail($user->id);
			if($user){
				if (Hash::check($request->old_password, $user->password)) {
					$user->fill(['password' => Hash::make($request->password)])->save();
					
					DB::commit();
					$this->sendResponse(['status'=>'success'], trans('common.updated_success'));
				}
				DB::rollback();
				$this->ajaxError('', trans('common.updated_faild'));
			}
	    } catch (Exception $e) {
	        DB::rollback();
	        $this->ajaxError([], $e->getMessage());
	    }
	}
}