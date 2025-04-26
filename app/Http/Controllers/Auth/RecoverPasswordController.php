<?php

namespace App\Http\Controllers\Auth;

use DB;
use App\Http\Controllers\CommonController;
use App\Models\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RecoverPasswordController extends CommonController
{
	use CommonHelper;
    /*
    |----------------------------------------------------------------------
    | Login Controller
    |----------------------------------------------------------------------
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    */
	
    /**
	* Where to redirect users after login.
	*
	* @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$this->middleware('guest')->except('logout');
    }
	
	public function recover_password(Request $request){
		
		return view('auth.recover-password');
	}
	
	public function ajax_recover_password(Request $request){
		
		$validator = Validator::make($request->all(), [
            'email'	=> 'required|email|min:5|max:55',
        ]);
        if($validator->fails()){
            $this->ajaxValidationError($validator->errors(), trans('common.error'));
        }
				
		DB::beginTransaction();
		try{
			// CHECK EMAIL EXIST OR NOT
			$user = User::where('email', $request->email)->first();
			if(empty($user)){
				return $this->ajaxError('',trans('common.email_not_exist'));
			}

			$code = rand(100000,999999);
			
			// Send Mail
			$template_data = new \stdClass();
			$template_data->user				= $user;
			$template_data->code				= $code;
			$sent = CommonHelper::sendMail($user->email, 'Verify your account - OTP', 'email-templates.send-otp', $template_data);
			if($sent){
				DB::commit();
				return $this->sendResponse('',trans('common.sent_successfully'));
			}
			DB::rollback();
			$this->sendResponse('', trans('common.failed_to_sent'));
			
		} catch (Exception $e) {
            $this->ajaxError([], trans('common.try_again'));
        }
	}
}