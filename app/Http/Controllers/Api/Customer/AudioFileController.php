<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Audio;
use App\Models\GpsValidation;
use App\Models\Distance;
use App\Models\Bus;
use App\Http\Resources\PlaceListResource;
use App\Models\Place;
use Log;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Validator, Auth, Storage;
use App\Models\Helpers\CommonHelper;
use DB;
use App\Http\Resources\TrackBusList;

class AudioFileController extends BaseController
{
	use CommonHelper;

    public function index(Request $request)
    {
        try {
            $query = Audio::where(['status'=> 'active'])->select('title','page_id','latitude','longitude','priority','proximity','show_icon','angle','tolerance','is_in_queue');
			$data = $query->orderBy('priority', 'ASC')->get();

            if (count($data) > 0) {
                return $this->sendArrayResponse($data,trans('customer_api.save_success'));
            } else {
                return $this->sendArrayResponse($getDataAudio,trans('customer_api.data_found_success'));
            }
        } catch (\Exception $e) {
	        DB::rollback();
            return $this->sendError('API/AudioFileController->index', $e->getMessage()); 
        }
    }

    // 18-7-2024 Utsav Shah//
    public function show(Request $request) 
    {
      try {
            $validator = Validator::make($request->all(), [
                'languages' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'page_id' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendValidationError('', $validator->errors()->first());
            }

            // Request parameter languages pass in parameters
            $postLanguage = $request->languages;
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $page_id = $request->page_id;

            $getDataRadius = Audio::select('is_in_queue','title', 'description', 'image', 'file_path')
                ->where('latitude', $latitude)
                ->where('longitude', $longitude)
                ->where('page_id', $page_id)
                ->where('status', 'active')
                ->first();

            if ($getDataRadius) {
                $filePaths = $getDataRadius['file_path'];
                $filePaths = str_replace('\\', '/', $filePaths); // Convert backslashes to forward slashes

                $alldata = json_decode($filePaths, true);

                if ($alldata !== null && is_array($alldata)) {
                    $audioFilePath = '';
                    $audioStatus = '';

                    foreach ($alldata as $data) {
                        if (isset($data['languages']) && $data['languages'] === $postLanguage) {
                            $audioFilePath = str_replace('\\', '/', $data['file_path']);
                            $audioFilePath = ltrim($audioFilePath, '/');
                            $audioStatus = $data['audio_status'];
                            break; // Exit loop if matching language is found
                        }
                    }

                    unset($getDataRadius['file_path']);
                    $getDataRadius['audio'] = $audioFilePath; // If no match, audio will be an empty string

                    return $this->sendArrayResponse( $getDataRadius, trans('customer_api.data_found_success'));
                } else {
                    return $this->sendError("", trans('customer_api.data_error')); 
                }
            } else {
                return $this->sendError("", trans('customer_api.data_not_found'));
            }
        
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('API/AudioFileController->show', $e->getMessage());
        }
    }
    // 18-7-2024 Utsav Shah//




    public function updateGps(Request $request)
    {
        try {
            $gpsValidation = GpsValidation::first();
                return response()->json([
                    'status' => 'success',
                    'message' => trans('customer_api.save_success'), 
                    'data' => $gpsValidation
                ]);
           
        } catch (\Exception $e) {
            // Rollback in case of an exception (if you have transactions)
            DB::rollback();
            
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}