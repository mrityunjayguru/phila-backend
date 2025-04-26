<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB,Settings;
use App\Models\Helpers\CommonHelper;
use App\Models\SmsVerificationNew;
use App\Models\User;
use App\Models\About;
use Illuminate\Contracts\Encryption\DecryptException;

class CommonController extends BaseController
{
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
            $message ='You have made a request for OTP Please Use '. rand(1000,9999) .' to reset your password';
            $sent = CommonHelper::sendOTP($request->country_code . $request->phone_number, $message);
            $sent = true;
            if($sent){
                $query = SmsVerification::create(['phone_number' => $request->country_code . $request->phone_number,'code'    => $fourRandomDigit]);
                if($query){
                    DB::commit();
                    return $this->sendResponse("", trans('customer_api.otp_sent_success'));
                }
            }
			
			DB::rollback();
			return $this->sendError('', trans('customer_api.otp_sent_error'));
			
        } catch (\Exception $e) { 
			DB::rollback();
			return $this->sendError('', $e->getMessage());
        }
    }

    public function verifyOTP(Request $request){
        $validator = Validator::make($request->all(),[
            'otp' => 'required|min:4|max:4',
            'phone_number' => 'required|min:8|max:15',
            'country_code' => 'required',
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
     * General Information
     *
     * @return \Illuminate\Http\Response
     */
    public function general_information(Request $request)
	{
        try {
			$data = [
				'title' 				=> 'Philadelphia CitySight',
				'app_version'			=> '1.0.0',
				'copy_rights_year'		=> '2022',
				'xd_web'				=> '',
				'google_map_api_key' 	=> Settings::get('google_map_api_key'),
				'xd_mobile'				=> 'https://xd.adobe.com/view/193a09e8-8343-4837-a132-28bea5fc60f1-4c69/',
				'playstore_url'			=> 'https://play.google.com/store/apps/details?id=com.citysightseeingphila.citySightseeingapp',
				'appstore_url'			=> 'https://apps.apple.com/in/app/city-sightseeing-philadelphia/id1638327481',
			];
			return $this->sendResponse($data, trans('customer_api.data_found_success'));
			
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
    }
	
	/**
     * CMS Pages
     *
     * @return \Illuminate\Http\Response
	*/
    public function cmsPages(Request $request){
        
        $return['about']            = About::where('id','=',1)->pluck('description')->first();
        $return['contact']          = '';
        $return['terms']            = url('terms');
        $return['privacy_policy']   = url('privacy-policy');
        $return['chat_url']   		= Settings::get('chat_url');
        $return['ticket_url']		= Settings::get('ticket_url');
        $return['refress_time']		= '3';
        $return['center_latitude']	= '39.94996';
        $return['center_longitude']	= '-75.14886';
        return $this->sendResponse($return, trans('customer_api.data_found_success'));
    }
	
	/**
	* GET libitrack Data
	* @return \Illuminate\Http\Response
	*/
    public function libitrack(Request $request){
        
        // GET Device Data
		$username = 'worklooper';
		$password = 'User@123';
		$url	  = 'https://libitrack.in/api/v1/index.php/getDevPos';
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		//curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadName);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$rest = curl_exec($ch);
		
		$return = [];
		if ($rest === false){
			$data = [];
		}else{
			$data = json_decode($rest);
			
			if($data->status == 'success'){
				$return = $data->data;
			}
		}
		curl_close($ch);
		
		return $this->sendResponse($return, trans('customer_api.data_found_success'));
    }
}