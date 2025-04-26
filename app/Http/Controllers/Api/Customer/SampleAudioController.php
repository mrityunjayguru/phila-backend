<?php

namespace App\Http\Controllers\Api\Customer;

use App\AudioCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\Language;
use App\Models\SampleAudio;
use App\Models\LandingPage;
use Log;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Validator, Auth, Storage;
use App\Models\Helpers\CommonHelper;
use DB;

class SampleAudioController extends BaseController
{
    use CommonHelper;

    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'languages' => 'required',
                'page_id' => 'required',
            ]);
            
            if ($validator->fails()) {
                return $this->sendValidationError('',$validator->errors()->first()); 
            }
            
            $getLanguage = $request->languages;
            $getLandingPageData = LandingPage::select('title','image','route_length','route_time','number_of_stops','description','status')->first();
            $getDataAudio = SampleAudio::where('status', 1)
                ->where('languages', $getLanguage)
                ->where('page_id', $request->page_id)
                ->select('audio')
                ->first();
            if($getDataAudio == null) {
                return $this->sendArrayResponse($getDataAudio,trans('customer_api.no_languages_found'));
            } else {
                $query = SampleAudio::where(['status'=> 'active', 'languages'=> $getDataAudio]);
                $data = $query->orderBy('id', 'ASC')->get();

                
                $responseData = [
                    'landing_page_data' => $getLandingPageData,
                    'audio' => $getDataAudio
                ];

                if (count($responseData) > 0) {
                    return $this->sendArrayResponse($responseData,trans('customer_api.save_success'));
                } else {
                    return $this->sendArrayResponse($responseData,trans('customer_api.data_found_success'));
                }
            }
        } catch (\Exception $e) {
	        DB::rollback();
            return $this->sendError('API/SampleAudioController->index', $e->getMessage()); 
        }
    }
    
    public function getSampleAudioStatusActive()
    {
        try {
            $getDataAudio = SampleAudio::select('page_id','languages','tag','status')->where('status', 'active')
                ->get();
            if(count($getDataAudio) > 0) {
                return $this->sendArrayResponse($getDataAudio,trans('customer_api.data_found_success'));
            } else {
                return $this->sendArrayResponse($getDataAudio,trans('customer_api.no_languages_found'));
            }
        } catch (\Exception $e) {
	        DB::rollback();
            return $this->sendError('API/SampleAudioController->index', $e->getMessage()); 
        }
    }

    public function getLandingPage(){
        // $user_id = Auth::user()->id;
		// if(empty($user_id)){
		// 	return $this->sendError('',trans('customer_api.invalid_user'));
		// }

        $landingPage = LandingPage::orderBy('priority', 'asc')->get()->map(function ($page) {

            if($page->is_code_dependecy == "yes"){
                $code = AudioCode::where('page_id', $page->id)->get()->map(function ($audioCode) {
                    return [
                        'ticket_number' => $audioCode->ticket_number,
                    ];
                });
            }else{
                $code = [];
            }
            return [
                'id' => $page->id,
                'title' => $page->title,
                'image' => env('base_url').$page->image,
                'priority' => $page->priority,
                'is_code_dependency' => ($page->is_code_dependecy == "yes") ? true : false,
                'code' => $code,
		'status' => $page->status
            ];
        });
        
        if($landingPage){
            return response()->json([
                "success" => true,
                "data" => $landingPage
            ]);
        }else{
            return response()->json([
                "success" => false,
                "data" => [],
                "message" => "Data not found"
            ]);
        }
    }
}
