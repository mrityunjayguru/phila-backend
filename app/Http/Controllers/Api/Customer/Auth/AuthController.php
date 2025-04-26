<?php

namespace App\Http\Controllers\Api\Customer\Auth;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Authy\AuthyApi;
use Validator,DB;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\SmsVerification;
use App\Models\Helpers\CommonHelper;;
use App\Models\DeviceDetail;
use App\Models\FirebaseToken;


class AuthController extends BaseController
{
    
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' 		=> 'required|min:8|max:51',
            'password' 		=> 'required|min:6|max:55'
        ]);
        if($validator->fails()){
            return $this->sendValidationError('', $validator->errors()->first());       
        }
		
		$auth_check = Auth::attempt(['phone_number' => $request->username, 'password' => $request->password,'user_type'=>'Customer']);
       
		if(empty($auth_check)){
            $auth_check = Auth::attempt(['email' => $request->username, 'password' => $request->password,'user_type'=>'Customer']);
        }
		
		
        if($auth_check){
            $user = Auth::user();
            if($user){
                DB::table('oauth_access_tokens')->where('user_id', $user->id)->update(['revoked' => true]);
            } else {
				return $this->sendError('',trans('customer_api.login_error'));
			}
			
			// If account !active
			if(strtolower($user->status) != 'active') {
				return $this->sendError('',trans('customer_api.login_status'), 200, 202);
			}
            
            //Add response details into variable
            $success['token']            =  $user->createToken(config('app.name'))->accessToken;
            $success['id']               =  (string)$user->id;
            $success['name']             =  $user->name;
            $success['email']            =  $user->email;
            $success['country_code']     =  $user->country_code;
            $success['phone_number']     =  $user->phone_number;
            $success['gender']           =  $user->gender ? $user->gender : '';
            $success['dob']              =  $user->dob ? date('d-m-Y', strtotime($user->dob)) : '';
            $success['status']           =  $user->status;
            $success['user_type']        =  $user->user_type;
            
			// Save Device Details
            $data = $request->except('phone_number','password','user_type');
            $createArray = array();

            foreach ($data as $key => $value) { $createArray[$key] = $value; }

            $device_detail = DeviceDetail::where('user_id',Auth::user()->id)->first();
            if($device_detail){
                $device_detail->update($createArray);
            } else {
                $createArray['user_id'] = Auth::user()->id;
                DeviceDetail::create($createArray);
            }

			return $this->sendResponse($success, trans('customer_api.login_success'));
			
        } else {
            return $this->sendError('',trans('customer_api.login_error'));
        }
    }

    /**
     * Registration api
     *
     * @return \Illuminate\Http\Response
	*/
    public function registration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      	=> 'required|string|min:3|max:99',
            'gender'   	 	=> 'required|min:4|max:6',
            'dob'       	=> 'required',
            'email'     	=> 'required|string|email|max:99|unique:users',
            'country_code' 	=> 'required|min:2|max:4',
            'phone_number' 	=> 'required|min:8|max:15|unique:users',
            'password'  	=> 'required|min:6|max:10',
        ]);
        
        //return \Hash::make($request->password);
        if($validator->fails()){
            return $this->sendValidationError('', $validator->errors()->first());       
        }

        // EMAIL VALIDATION
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
			return $this->sendError('',trans('customer_api.invalid_email'));
        }
        
        $data = array(
            'name'      	=> $request->name,
            'gender'    	=> $request->gender,
            'dob'       	=> date('Y-m-d', strtotime($request->dob)),
            'country_code' 	=> $request->country_code,
            'phone_number' 	=> $request->phone_number,
            'profile_image' => '',
            'status'    	=> 'active',
            'email'     	=> $request->email,
            'password'  	=> Hash::make($request->password),
            'user_type' 	=> 'Customer'
        );

        $user = new User();
        $user->fill($data);

        DB::beginTransaction();
        try {
            if($user->save()){
                
                // Save Device Details
                $inputs = $request->except('country_code','phone_number','password','user_type');
                $inputs['user_id'] = $user->id;
                $createArray = array();
                foreach ($inputs as $key => $value) {
                    $createArray[$key] = $value;
                }
                DeviceDetail::create($createArray);
                DB::commit();

                // Send OTP
                $fourRandomDigit = rand(1000,9999);
                $query = SmsVerification::create(['phone_number' => $request->country_code . $request->phone_number,'code' => $fourRandomDigit]);

                //Add response details into variable
                $success['token']            =  $user->createToken(config('app.name'))->accessToken;
                $success['id']               =  (string)$user->id;
                $success['name']             =  $user->name;
                $success['email']            =  $user->email;
                $success['country_code']     =  $user->country_code;
                $success['phone_number']     =  $user->phone_number;
                $success['gender']           =  $user->gender ? $user->gender : '';
                $success['dob']              =  $user->dob ? date('d-m-Y', strtotime($user->dob)) : '';
                $success['status']           =  'active';
                $success['user_type']        =  $user->user_type;
                
                return $this->sendResponse($success, trans('customer_api.registration_success'));
            }else{
                DB::rollback();
                return $this->sendError($this->object,trans('auth.registration_error'));
            }
        }catch (Exception $e) {
            DB::rollback();
            return $this->sendException($this->object,$e->getMessage());
        }
        
        return $this->sendError('',trans('customer_api.registration_error'));
    }


    /**
     * SEND OTP
     *
     * @return \Illuminate\Http\Response
     */
    public function sendOTP(Request $request){
        $validator = Validator::make($request->all(),[
            'phone_number' => 'required|min:8|max:15',
            'country_code' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }
		
        DB::beginTransaction();
        try{
			$user = User::where(['phone_number' => $request->phone_number, 'country_code' => $request->country_code, 'user_type'=>'Customer'])->first();
			if(!empty($user)){
				$otp 		= rand(1000,9999);
				$otp 		= 1111;
				$message 	='Your Verification Code For Bright Matrimonial is {'. $otp .'}. Thank You, Have a Wonderful Day. Bright Matrimonial Team, brightmatrimonial.com.';
				$sent 		= CommonHelper::sendOTP($user, $otp , $message);
				if($sent){
					DB::commit();
					return $this->sendResponse("", trans('customer_api.otp_sent_success'));
				} else {
					DB::rollback();
					return $this->sendError('', trans('customer_api.otp_sent_error'));
				}
			} else {
				return $this->sendError('', trans('customer_api.otp_sent_error'));
			}
			return $this->sendError('', trans('customer_api.try_again'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

	/**
     * SEND OTP
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyOTP(Request $request){
        $validator = Validator::make($request->all(),[
            'otp' 			=> 'required|min:4|max:4',
            'country_code' 	=> 'required|min:2|max:6',
            'phone_number' 	=> 'required|min:8|max:15',
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }
        
        DB::beginTransaction();
        try{
            $dataArray = $request->all();
            $status = SmsVerificationNew::where(array('mobile_number'=>$request->country_code . $request->phone_number, 'code'=>$request->otp, 'status'=>'pending'))->first();
            if(empty($status)){
                return $this->sendError('', trans('customer_api.invalid_otp'));
            }
            
            $status->status = 'verified';
            $status->update();
            DB::commit();
            return $this->sendResponse("", trans('customer_api.otp_verified_success'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', trans('customer_api.otp_verified_error'));
        }
    }
	
	
    /**
     * Active account
     *
     * @return [string] message
     */
    public function active(Request $request){
        $validator = Validator::make($request->all(),[
            'otp' => 'required|min:4|max:4',
            'country_code' => 'required|min:2|max:4',
            'phone_number' => 'required|min:8|max:15'
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }
        
        $user = User::where(array('country_code'=>$request->country_code, 'phone_number'=>$request->phone_number, 'status'=>'pending'))->first();
        if(empty($user)){
            return $this->sendError('', trans('customer_api.invalid_user'));
        }

        DB::beginTransaction();
        try{
            $dataArray = $request->all();
            $result = SmsVerification::where(array('mobile_number'=>$request->country_code . $request->phone_number, 'code'=>$request->otp, 'status'=>'pending'))->first();
            if(empty($result)){
                return $this->sendError('', trans('customer_api.invalid_otp'));
            }
            $result->status = 'verified';
            $result->update();
            //DB::commit();

            $user = User::where(array('phone_number'=>$request->phone_number, 'status'=>'pending'))->first();
            if(empty($user)){
                return $this->sendError('', trans('customer_api.invalid_user'));
            }
            
            // Update user status
            $user->status = 'active';
            $user->update();
            DB::commit();

            //Set response details into variable
            $success['token']            =  $user->createToken(config('app.name'))->accessToken;
            $success['id']               =  (string)$user->id;
            $success['name']             =  $user->name;
            $success['email']            =  $user->email;
			$success['country_code']     =  $user->country_code;
            $success['phone_number']     =  $user->phone_number;
            $success['gender']           =  $user->gender ? $user->gender : '';
            $success['dob']              =  $user->dob ? date('d-m-Y', strtotime($user->dob)): '';
            $success['status']           =  'active';
            $success['user_type']        =  $user->user_type;
			
            return $this->sendResponse($success, trans('customer_api.account_act_success'));
            
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', trans('customer_api.account_act_error'));
        }
    }

	/**
	* -----------------------------------
	* FORGOT PASSWORD
	* @return \Illuminate\Http\Response
	*------------------------------------
	**/
    public function forgot_password(Request $request){
        $validator = Validator::make($request->all(),[
            'username' 	=> 'required|min:5|max:55',
        ]);
        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());
        }
		
		$user = Auth::attempt(['phone_number' => $request->username, 'user_type'=>'Customer']);
		if(empty($auth_check)){
            $user = Auth::attempt(['email' => $request->username, 'user_type'=>'Customer']);
        }
		if(empty($user)){
            return $this->sendError('', trans('customer_api.no_account_user'));
        }

        DB::beginTransaction();
        try{
			if(!empty($user)){
					$otp = rand(100000,999999);
					$remember_token = md5($otp);
					SmsVerificationNew::create(['phone_number' => $user->country_code . $user->phone_number, 'code' => $otp]);
					$message ='You have made a request for OTP Please Use '. $otp .' to Verify Your Account';
					
					// Send Mail
					//$sent = $this->sendMailNew($user->email, trans('customer_api.reset_pass_token'), $message);
					
					// Send SMS
					$sent = CommonHelper::sendOTP($user, $otp, $message);
					if($sent){
						$user->remember_token = $remember_token;
						$user->update();
						DB::commit();
						return $this->sendResponse("", trans('customer_api.otp_sent_success'));
					}else{
						DB::rollback();
						return $this->sendError('', trans('customer_api.otp_sent_error'));
					}
				}
			DB::rollback();
			return $this->sendError('', trans('customer_api.try_again'));
			
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }
	
	/**
	* -----------------------------------
	* RESET PASSWORD
	* @return \Illuminate\Http\Response
	*------------------------------------
	**/
    public function reset_password(Request $request){
        $validator = Validator::make($request->all(),[
            'otp' 				=> 'required|min:4|max:6',
            'username'			=> 'required|min:4|max:15',
            'new_password'		=> 'required|max:15|min:6',
            'confirm_password'	=> 'required|max:15|min:6|same:new_password',
        ]);
        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());
        }

        $user = Auth::attempt(['phone_number' => $request->username, 'user_type'=>'Customer']);
		if(empty($auth_check)){
            $user = Auth::attempt(['email' => $request->username, 'user_type'=>'Customer']);
        }
        if(empty($user)){
            return $this->sendError('', trans('customer_api.no_account_user'));
        }
        
        DB::beginTransaction();
        try{
            $query = User::where('id', $user->id)->update(['password' => Hash::make($request->new_password)]);
            if($query){
                DB::commit();
                return $this->sendResponse('',trans('customer_api.reset_password_success'));
            }
            return $this->sendResponse('',trans('customer_api.reset_password_error'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

    /**
	* -----------------------------------
	* CHANGE PASSWORD
	* @return \Illuminate\Http\Response
	*------------------------------------
	**/
    public function change_password(Request $request){
        $validator = Validator::make($request->all(),[
            'password' => 'required',
            'new_password'   => 'required|max:15|min:6',
            'confirm_password' => 'required|max:15|min:6|same:new_password',
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }
        try {
            if(Hash::check($request->password, auth()->user()->password)){
                $user = auth()->user();
                $user->update(['password'=> Hash::make($request->new_password)]);

                // updating password in customer passwords table
                $customer_pass = CustomerPassword::where('customer_id', $user->id)->get();
                if($customer_pass->count()){
                    CustomerPassword::where('customer_id', $user->id)->update(['password' => encrypt($request->new_password)]);
                }
                return $this->sendResponse('',trans('customer_api.reset_password_success'));
            } else {
                return $this->sendError('', trans('customer_api.old_password_not_matched')); 
            }
        } catch (Exception $e) {
            return $this->sendError('', $e->getMessage()); 
        }
    }
	
	
	/**
     * UPDATE FIREBASE TOKEN
     * @return \Illuminate\Http\Response
     */
    public function updateToken(Request $request){
        $validator = Validator::make($request->all(),[
            'token'	=> 'required|min:4|max:1000',
        ]);
        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }
        
        DB::beginTransaction();
        try{
			
			$data = FirebaseToken::where('token', $request->token)->first();
			if(!empty($data)){
				return $this->sendResponse('', 'Updated successfully');
			}
			
			// Create
            if(FirebaseToken::create(['token' => $request->token, 'date' => date('Y-m-d')])){
				 DB::commit();
				return $this->sendResponse('', 'Added successfully');
			}
			
			DB::rollback();
            return $this->sendResponse('', 'Faild to create');
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', 'Faild to create');
        }
    }
	
	/**
	* -----------------------------------
	* LOGOUT PASSWORD
	* @return \Illuminate\Http\Response
	*------------------------------------
	**/
    public function logout(){
        $user = Auth::user();
        /*$device_detail = $user->device_detail;
        if($device_detail){
            $device_detail->delete();
        }*/
        $user->token()->revoke();
        return $this->sendResponse('', trans('customer_api.logout'));
    }
}