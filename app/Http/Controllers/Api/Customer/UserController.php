<?php

namespace App\Http\Controllers\Api\Customer;

use Validator,DB;
use Authy\AuthyApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Models\Helpers\CommonHelper;
use App\Http\Controllers\Api\BaseController;

use App\Models\User;
use App\Models\Address;
use App\Models\Product;
use App\Models\Banner;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProfileListResource;
use App\Http\Resources\DashboardResource;
use App\Http\Resources\AddressResource;
use App\Http\Resources\AddressListResource;

class UserController extends BaseController
{
	use CommonHelper;
	
    /**
     * DASHBOARD
     *
     * @return \Illuminate\Http\Response
     */ 
	public function dashboard(Request $request)
    {
		//Get User Data
		$user_id = Auth::user()->id;
		if(empty($user_id)){
			return $this->sendError('',trans('customer_api.invalid_user'));
		}
			
        try{
            // Collect Data
			$data						= new \stdClass();
            //$data->user					= User::where('id', $user_id)->first();
            $data->banners				= Banner::where('id', $user_id)->first();
            $data->trending_products	= Product::where('status', 'active')->get();
            $data->explore_new			= Product::where('status', 'active')->get();
			
            return $this->sendResponse(new DashboardResource($data), trans('customer_api.data_found_success'));
        }catch(\Exception $e){
            DB::rollback();
            return $this->sendError('',trans('customer_api.data_found_empty'));
        }
    }
	
	/**
     * GET PROFILE
     *
     * @return \Illuminate\Http\Response
     */ 
    public function profile(Request $request)
    {
        DB::beginTransaction();
        try{
            
            //Get User Data
            $user_id = Auth::user()->id;
            if(empty($user_id)){
                return $this->sendError('',trans('customer_api.invalid_user'));
            }

            // GET USER DATA
            $user = User::where('id', $user_id)->first();
            return $this->sendResponse(new UserResource($user), trans('customer_api.data_found_success'));
        }catch(\Exception $e){
            DB::rollback();
            return $this->sendError('',trans('customer_api.data_found_empty'));
        }
    }

	/**
     * PROFILE UPDATE
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     		 	=> 'required|string|min:3|max:99',
            'email'     		=> 'required|string|email|max:99',
            'country_code'		=> 'required|min:1|max:4',
            'phone_number' 		=> 'required|min:6|max:15',
            'gender'    		=> 'required|min:4|max:6',
            'dob'       		=> 'required',
        ]);
        if($validator->fails()){
            return $this->sendValidationError('', $validator->errors()->first());
        }

        $user = Auth::user();
        if(empty($user)){
            return $this->sendError('',trans('customer_api.invalid_user'));
        }

        // EMAIL VALIDATION
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return $this->sendError('',trans('customer_api.invalid_email'));
        }
        
        // CHECK EMAIL EXIST OR NOT
        $email = User::where('email', $request->email)->whereNotIn('id', [$user->id])->first();
        if(!empty($email)){
            return $this->sendError('',trans('customer_api.email_already_exist'));
        }

        // CHECK MOBILE NO EXIST OR NOT
        $phone_number = User::where('phone_number', $request->phone_number)->whereNotIn('id', [$user->id])->first();
        if(!empty($phone_number)){
            return $this->sendError('',trans('customer_api.phone_number_already_exist'));
        }
       
        DB::beginTransaction();
		try{
			$query = User::where('id', $user->id)->update([
				'name'          => $request->name,
				'email'         => $request->email,
				'country_code'  => $request->country_code,
				'phone_number'  => $request->phone_number,
				'gender'        => $request->gender,
                'dob'           => date('Y-m-d', strtotime($request->dob)),
			]);
			if($query){
                DB::commit();

                //Get User Data
                $user = User::where('id', $user->id)->first();

                $success['id']               =  (string)$user->id;
                $success['name']             =  $user->name;
                $success['email']            =  $user->email;
                $success['country_code']     =  $user->country_code;
                $success['phone_number']     =  $user->phone_number;
                $success['gender']           =  $user->gender ? $user->gender : '';
                $success['dob']              =  $user->dob ? date('d-m-Y', strtotime($user->dob)) : '';
                $success['status']           =  $user->status;
                $success['user_type']        =  $user->user_type;
				return $this->sendResponse($success, trans('customer_api.profile_update_success'));
			}else{
				DB::rollback();
				return $this->sendError('',trans('customer_api.profile_update_error'));
			}
		}catch(\Exception $e){
            DB::rollback();
			return $this->sendError('',trans('customer_api.profile_update_error'));
        }
    }
	
	/**
     * UPDATE PROFILE PICTURE
     * @return \Illuminate\Http\Response
	*/
    public function updateProfilePicture(Request $request)
    {
		$validator = Validator::make($request->all(), [
			'image'	=> 'required|image|mimes:jpeg,png,jpg|max:1024',
		]);
		if($validator->fails()){
			return $this->sendValidationError('', $validator->errors()->first());
		}
		
        $user = Auth::user();
        if(empty($user)){
            return $this->sendError('',trans('customer_api.invalid_user'));
        }
		
		DB::beginTransaction();
		try{
			// Save Image
			if($request->image){
                $path = $this->saveMedia($request->file('image'));
				$query = User::where('id', $user->id)->update(['profile_image' => $path]);
				
				if($query){
					DB::commit();
					//Get User Data
					$user = User::where('id', $user->id)->first();
					return $this->sendResponse(new UserResource($user), trans('customer_api.update_success'));
				}else{
					DB::rollback();
					return $this->sendError('',trans('customer_api.update_error'));
				}
            }
			return $this->sendError('',trans('customer_api.update_error'));
		}catch(\Exception $e){
            DB::rollback();
			return $this->sendError('',$e);
        }
    }
	
	/**
     * UPDATE COVER IMAGE
     * @return \Illuminate\Http\Response
	*/
    public function updateCoverImage(Request $request)
    {
		$validator = Validator::make($request->all(), [
			'image' => 'required|image|mimes:jpeg,png,jpg|max:1024',
		]);
		if($validator->fails()){
			return $this->sendError('', $validator->errors()->first());
		}
		
        $user = Auth::user();
        if(empty($user)){
            return $this->sendError('',trans('customer_api.invalid_user'));
        }

        DB::beginTransaction();
		try{
			// Save Image
			if($request->image){
                $path = $this->saveMedia($request->file('image'));
				$query = User::where('id', $user->id)->update(['cover_image' => $path]);
				
				if($query){
					DB::commit();
					//Get User Data
					$user = User::where('id', $user->id)->first();
					return $this->sendResponse(new UserResource($user), trans('customer_api.update_success'));
				}else{
					DB::rollback();
					return $this->sendError('',trans('customer_api.update_error'));
				}
            }
			return $this->sendError('',trans('customer_api.update_error'));
		}catch(\Exception $e){
            DB::rollback();
			return $this->sendError('',$e);
        }
    }

    /**
     * SAVE NOTIFICATION SETTINGS
     *
     * @return \Illuminate\Http\Response
     */
    public function savegeneralSettings(Request $request){
        $validator = Validator::make($request->all(),[
            'via_nitification' => 'max:1',
            'via_email' => 'max:1',
        ]);
        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }

        $user = Auth::user();
        if(empty($user)){
            return $this->sendResponse("", trans('customer_api.invalid_user'));
        }
        
        DB::beginTransaction();
        try{
            if($request->via_nitification != ''){
                $query = User::where('id', $user->id)->update(['noti_via_nitification' => $request->via_nitification]);
                if($query){
                    DB::commit();
                }
            }
            if($request->via_email != ''){
                $query = User::where('id', $user->id)->update(['noti_via_email' => $request->via_email]);
                if($query){
                    DB::commit();
                }
            }
			
            //Get User Data
            $user = User::where('id', $user->id)->first();
            $success['via_nitification'] = $user->noti_via_nitification ? (string) $user->noti_via_nitification : '0';
            $success['via_email']        = $user->noti_via_email ? (string) $user->noti_via_email : '0';
            return $this->sendResponse($success,trans('customer_api.save_success'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

    /**
     * GET SETTINGS
     *
     * @return \Illuminate\Http\Response
     */
    public function getgeneralSettings(Request $request){
        
        $user = Auth::user();
        if(empty($user)){
            return $this->sendResponse("", trans('customer_api.invalid_user'));
        }
        
        DB::beginTransaction();
        try{
            //Get User Data
            $user = User::where('id', $user->id)->first();
            $success['via_nitification'] 	= $user->noti_via_nitification ? (string) $user->noti_via_nitification : '0';
            $success['via_email']        	= $user->noti_via_email ? (string) $user->noti_via_email : '0';
            return $this->sendResponse($success,trans('customer_api.data_found_success'));
        } catch (\Exception $e) {
          DB::rollback();
          return $this->sendError('', $e->getMessage());
        }
    }

    /**
    * SAVE FAVORITES
    *
    * @return \Illuminate\Http\Response
    */
    public function save_favorites(Request $request){
        $validator = Validator::make($request->all(),[
            'product_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }

        $user = Auth::user();
        if(empty($user)){
            return $this->sendResponse("", trans('customer_api.invalid_user'));
        }
        
        DB::beginTransaction();
        try{
            if($request->product_id){
                $rowItem = FavoriteItems::where(['product_id'=>$request->product_id, 'user_id'=>$user->id])->first();
                if($rowItem){
                    $delete = FavoriteItems::where(['id'=>$rowItem->id])->delete();
					if($delete){
					  DB::commit();
					  return $this->sendResponse('',trans('customer_api.removed_from_favorite_items'));
					}
                }else{
					$query				= new FavoriteItems;
					$query->user_id		= $user->id;
					$query->product_id	= $request->product_id;
					$query->save();
					
					if($query){
						$query->is_added = '1';
						DB::commit();
						return $this->sendResponse(new FavoriteItemsResource($query),trans('customer_api.added_to_favorite_items'));
					}
				}
            }
            DB::rollback();
            return $this->sendResponse('',trans('customer_api.save_error'));
        } catch (\Exception $e) {
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }
	
	
    /**
    * GET ADDRESSES
    *
    * @return \Illuminate\Http\Response
    */
    public function addresses(Request $request){
        
        $user = Auth::user();
        if(empty($user)){
            return $this->sendError("", trans('customer_api.invalid_user'));
        }
        
        try{
			$query = Address::query();
            $query->where('user_id','=',$user->id);
            $query = $query->orderBy('id', 'DESC')->get();
            if($query){
                DB::commit();
                return $this->sendArrayResponse(AddressListResource::collection($query),trans('customer_api.data_found_success'));
            }
            return $this->sendArrayResponse('',trans('customer_api.data_found_empty'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

    /**
    * SAVE ADDRESS
    *
    * @return \Illuminate\Http\Response
    */
    public function save_address(Request $request){
        
        $validator = Validator::make($request->all(),[
            'address_type'  => 'required|min:3|max:100',
            'address'       => 'required|min:3|max:1000',
            'city_id'       => 'required',
            'country_id'    => 'required',
            'postal_code'   => 'required|min:3|max:10',
			'latitude'   	=> 'required',
            'longitude'   	=> 'required'
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }

        $user = Auth::user();
        if(empty($user)){
            return $this->sendError("", trans('customer_api.invalid_user'));
        }

        $data = array(
            'address_type'  => $request->address_type,
            'city_id'       => $request->city_id,
            'country_id'    => $request->country_id,
            'address'       => $request->address,
            'postal_code'   => $request->postal_code,
            'user_id'       => $user->id,
			'latitude'		=> $request->latitude,
            'longitude'		=> $request->longitude,
        );
        
        try{
            if($request->address_id){
                Address::where('id', $request->address_id)->update($data);
                $query = Address::where('id', $request->address_id)->first();
            }else{
               $query = Address::create($data);
            }
            if($query){
                DB::commit();
                return $this->sendArrayResponse(new AddressResource($query),trans('customer_api.data_saved_success'));
            }
            return $this->sendArrayResponse('',trans('customer_api.data_saved_error'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage());
        }
    }

    /**
    * DELETE ADDRESS
    *
    * @return \Illuminate\Http\Response
    */
    public function delete_address(Request $request){
        
        $validator = Validator::make($request->all(),[
            'address_id'       => 'required|exists:addresses,id',
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }

        $user = Auth::user();
        if(empty($user)){
            return $this->sendError("", trans('customer_api.invalid_user'));
        }
        
        try{
			return $this->sendArrayResponse('',trans('customer_api.data_delete_error'));
            $delete = Address::where(['id'=>$request->address_id, 'user_id'=>$user->id])->delete();
            if($delete){
                DB::commit();
                return $this->sendArrayResponse('',trans('customer_api.data_delete_success'));
            }
            return $this->sendArrayResponse('',trans('customer_api.data_delete_error'));
        } catch (\Exception $e) {
          DB::rollback();
          return $this->sendError('', $e->getMessage());
        }
    }
}