<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Validator, Auth, Storage;
use App\Models\Helpers\CommonHelper;
use App\Models\Audio;
use App\Models\GpsValidation;
use App\Models\Setting;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\Language;
use Log;

class AudioFileController extends CommonController
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
		//$this->middleware('permission:brand-list', ['only' => ['index','show']]);
		//$this->middleware('permission:brand-create', ['only' => ['create','store']]);
		//$this->middleware('permission:brand-edit', ['only' => ['edit','update']]);
		//$this->middleware('permission:brand-delete', ['only' => ['destroy']]);
	}
  
	// LISTING DATA
	public function index($id)
    {
		$page_id = $id;
		return view('backend.audio_files.list',compact('page_id'));
	}

	// CREATE
	public function create($id)
    {
		$page_id = $id;
		// dd($page_id);
		$getLanguage = Language::select('*')->where('status','active')->orderBy('language','ASC')->get();
		return view('backend.audio_files.add',compact('getLanguage','page_id'));
	}

  	// STORE
	public function store(Request $request)
	{
		// dd($request->all());
		$user = Auth()->user();
		if (empty($user)) {
			return response()->json(['error' => trans('common.invalid_user')], 400);
		}

		$validator = Validator::make($request->all(), [
			'title'      => 'required',
			'latitude'   => 'required|numeric',
			'longitude'  => 'required|numeric',
			// 'language'   => 'required|string',
			'status'     => 'required',
			'show_icon'  => 'required',
            'angle'   => 'required|numeric',
			'tolerance'  => 'required|numeric',
			'proximity' => [
				'required',
				function ($attribute, $value, $fail) {
					if ($value <= 0  ) {
						$fail($attribute.' must be greater than 0.');
					}
				},
			],
			// 'priority' => [
			// 	'required',
			// 	function ($attribute, $value, $fail) {
			// 		if ($value <= 0 ) {
			// 			$fail($attribute.' must be greater than 0.');
			// 		}
			// 	},
			// ],
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()], 400);
		}

		try {
			$checkExist =  Audio::where('priority', $request->priority)->where('id','!=',$request->item_id)->first();
			if($checkExist != null){
				$error = [];
				$error['priority'][0] = 'The priority has already been taken.';
				return response()->json(['error' => $error], 400);
			} 
			
			$data = [
				'page_id'     => $request->page_id,
				'title'       => $request->title,
				'latitude'    => $request->latitude,
				'longitude'   => $request->longitude,
				'languages'   => $request->languages,
				'description' => $request->description,
				'status'      => $request->status,
				'is_in_queue' => $request->is_in_queue,
				'show_icon'   => $request->show_icon,
				'priority'    => $request->priority,
				'proximity'   => $request->proximity,
				'angle'       => $request->angle,
   				'tolerance'   => $request->tolerance,
			];

			// Handle Image Upload (if needed)
			
			if (!empty($request->image) && $request->image != 'undefined') {
				$image = $request->file('image');
				$fileNameWithExt = $image->getClientOriginalName();
				$fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
				$extension = $image->getClientOriginalExtension();
				$fileNameToStore = $fileName . '_' . time() . '.' . $extension;

				// Determine the directory structure based on the current date
				$year = date('Y');
				$month = date('m');
				$directory = "uploads/{$year}/{$month}";

				// Create the directory if it doesn't exist
				if (!file_exists(public_path($directory))) {
					mkdir(public_path($directory), 0777, true);
				}

            	// Move the file to the newly created directory
				$path = $image->move(public_path($directory), $fileNameToStore);

				// Store the relative file path in the database
				$data['image'] = "{$directory}/{$fileNameToStore}";
			}


			// Handle Audio upload files
			$audioData = json_decode($request->input('audio_data'), true);
			
			$processedData = [];

			foreach ($audioData as $index => $audio) {
				if ($request->hasFile("audio_files.$index")) {
					$file = $request->file("audio_files.$index");

					if ($file->isValid()) {
						$fileName = time() . '_' . $file->getClientOriginalName();
						$language = $audio['languages'];
						$directory = "uploads/audio/{$language}";

						// Create directory if it doesn't exist
						if (!file_exists(public_path($directory))) {
							mkdir(public_path($directory), 0777, true);
						}

						// Move the file to the directory
						$filePath = $file->move(public_path($directory), $fileName);

						// Get relative file path
						$relativeFilePath = str_replace(public_path(), '', $filePath);
						$relativeFilePath = str_replace('/', '\\', $relativeFilePath);

						// Append processed data
						$processedData[] = [
							'languages' => $audio['languages'],
							'file_path' => $relativeFilePath,
							'audio_status' => $audio['audio_status']
						];
					}
				}
			}
          
			$data['file_path'] = json_encode($processedData,JSON_UNESCAPED_UNICODE);
			$data['file_path'] = str_replace('\\\\', '/', $data['file_path']);
			/** End audio path 17-7-2024 */

			// Create or update record in database
			if ($request->id) {
				$audio = Audio::find($request->id);
				$audio->fill($data);
				$saved = $audio->save();
			} else {
			   
				$return = Audio::create($data);
				$this->sendResponse([], trans('common.added_success'));
			}
		} catch (Exception $e) {
			return response()->json(['error' => $e->getMessage()], 500);
		}
	}

	private function saveAudio($file, $folder = '', $type = '', $language = '', $width = '', $height = '')
	{
		/** Start 8-7-2024 */
        $fileName = time() . '_' . $file->getClientOriginalName();
		// Move the file to the specified directory
		$filePath = $file->move(public_path($directory), $fileName);
		return $filePath;
        /** 8-7-2024 end changes Utsav Shah */
	}

	private function get_upload_directory($folder)
	{
		$folder = rtrim($folder, '/') . '/';
		// Define the base directory for uploads
		$base_directory = 'uploads/';
		// Combine base directory with the provided folder
		$uploaded_directory = $base_directory . $folder;

		// Ensure the directory exists
		if (!file_exists(public_path($uploaded_directory))) {
			mkdir(public_path($uploaded_directory), 0777, true);
		}

		return $uploaded_directory;
	}
	  
	public function ajax_list(Request $request)
    {
		$page = $request->page ?? '1';
        $count = $request->count ?? '100';
        if ($page <= 0) { $page = 1; }
        $start = $count * ($page - 1);

        $user = Auth::user();
        if (empty($user)) {
            return $this->ajaxError([], trans('common.invalid_user'));
        }

        try {
            // GET LIST
           $query = Audio::query();
			
			// Filters
			if($request->status != 'all'){
				$query->where(['status' => $request->status]);
			}
			
			// Filters
			if($request->type != 'all'){
				$query->where(['type' => $request->type]);
			}
			
			// SEARCH
			if($request->search){
				$query->where('title','like','%'.$request->search.'%');
			}
			$query->where('page_id',$request->page_id);
			$data = $query->orderBy('title', 'ASC')->offset($start)->limit($count)->get();
			
			
			if($data){
				foreach($data as $key=> $list){
					$delete_btn = '';
					if(Setting::UserPermission('audio-delete')){
						// $delete_btn = '<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>';
					}
					// $data[$key]->action = '<div class="widget-content-right widget-content-actions">
					// 						<a class="border-0 btn-transition btn btn-outline-success" href="'. route("edit-audio",$list->page_id,$list->id) .'"><i class="fa fa-eye"></i></a>'. $delete_btn .'
					// 						</div>';
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
                            <a class="border-0 btn-transition btn btn-outline-success" 
                               href="'. route("edit-audio", ['page_id' => $list->page_id, 'id' => $list->id]) .'">
                                <i class="fa fa-eye"></i>
                            </a>' . $delete_btn . '
                        </div>';

					if($list->image){ $data[$key]->image  = asset($list->image); }else { $data[$key]->image  = asset(config('constants.DEFAULT_MENU_IMAGE')); }
				}
				$this->sendResponse($data, trans('common.data_found_success'));
			}
			$this->sendResponse([], trans('common.data_found_empty'));

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
  
	// DESTROY
	public function destroy(Request $request)
    {
		$validator = Validator::make($request->all(), [
        	'item_id' => 'required',
		]);
		if ($validator->fails()) {
			return $this->ajaxError($validator->errors(), trans('common.error'));
		}

		try {
			// Retrieve the record to get the file path
			$audio = Audio::find($request->item_id);
			if (!$audio) {
				return $this->ajaxError([], trans('common.not_found'));
			}

			// Assuming you have a 'file_name' attribute in your Audio model
		
			$filePath = public_path($audio->file_path); // Adjust as needed
			
			// Delete the file if it exists
			if (file_exists($filePath) && is_file($filePath)) {
				unlink($filePath);
			}

			// Delete the record from the database
			$query = $audio->delete();
			if ($query) {
				return $this->sendResponse([], trans('common.delete_success'));
			}
			return $this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			return $this->ajaxError([], $e->getMessage());
		}
	}

	// EDIT
	public function edit($page_id, $id)
	{
		$data = Audio::find($id);
		// dd($data);

		// Decode the file path to get language and status information
		$getDataLanguage = str_replace('\\', '/', $data->file_path);
		$audioDataArray = json_decode($getDataLanguage, true);
		$getLanguage = Language::select('*')->where('status', 'active')->get();
		
		if (json_last_error() !== JSON_ERROR_NONE) {
			dd('JSON Decode Error: ' . json_last_error_msg());
		}

		// Map the languages to the audio data
		$audioDataMapped = [];
		
		for ($i = 0; $i < count($audioDataArray); $i++) {
			$audio = $audioDataArray[$i];
			$audioDataMapped[$audio['languages']] = [
				'file_path' => $audio['file_path'],
				'audio_status' => $audio['audio_status']
			];
		}
		
		foreach ($getLanguage as $language) {
			$languageKey = $language->language;

			// Check if the language already exists in $audioDataMapped
			if (!array_key_exists($languageKey, $audioDataMapped)) {
				// If not, add a default entry
				$audioDataMapped[$languageKey] = [
					'file_path' => '',
					'audio_status' => 'inactive' // Default status for missing languages
				];
			}
		}
		$triggerPoints = Audio::where('page_id',$page_id)->where('status','active')->get();
		// dd($triggerPoints);
		// Pass the data to the view
		return view('backend.audio_files.edit', compact('data', 'audioDataMapped','page_id','triggerPoints'));
	}

	public function update(Request $request)
	{
		$data = $request->all();
		// dd($data);
		$id = $data['id']; // Assuming 'id' is part of $data array
    	$audio = Audio::where('id', $id)->first();

    	if (!$audio) {
       		return response()->json(['error' => 'Audio not found'], 404);
    	}
			
		$updateData = [
			'page_id' => $data['page_id'],
			'is_in_queue' => $data['is_in_queue'],
			'title' => $data['title'],
			'latitude' => $data['latitude'],
			'longitude' => $data['longitude'],
			'description' => $data['description'],
			'status' => $data['status'],
			'show_icon' => $data['show_icon'],
			'priority' => $data['priority'],
			'proximity' => $data['proximity'],
			'angle' => $data['angle'],
			'tolerance' => $data['tolerance'],
		];
		
		// Handle audio file upload if present in $data

	    $existingData = json_decode($audio->file_path, true);
    	$processedData = $existingData ? $existingData : [];
	   	$audioData = json_decode($request->input('audio_data'), true);
		
		foreach ($audioData as $index => $audioItem) {
			if ($request->hasFile("audio_files.$index")) {
				$file = $request->file("audio_files.$index");

				if ($file->isValid()) {
					$fileName = time() . '_' . $file->getClientOriginalName();
					$language = $audioItem['languages'];
					
					$directory = "uploads/audio/{$language}";

					// Create directory if it doesn't exist
					if (!file_exists(public_path($directory))) {
						mkdir(public_path($directory), 0777, true);
					}

					// Move the file to the directory
					$filePath = $file->move(public_path($directory),$fileName);
					// Get relative file path
					$relativeFilePath = str_replace(public_path(), '', $filePath);
					$relativeFilePath = ltrim($relativeFilePath, '/');

					$found = false;
					foreach ($processedData as &$data) {
						if ($data['languages'] === $audioItem['languages']) {
							$data['file_path'] = $relativeFilePath;
							$data['audio_status'] = $audioItem['audio_status'];
							$found = true;
							break;
						}
					}

					if (!$found) {
						
						// Append processed data
						$processedData[] = [
							'languages' => $audioItem['languages'],
							'file_path' => $relativeFilePath,
							'audio_status' => $audioItem['audio_status']
						];
						
					}
				}
			}
    	}
		$updateData['file_path'] = json_encode($processedData,JSON_UNESCAPED_UNICODE);
		$updateData['file_path'] = str_replace('\\', '/', $updateData['file_path']);
		$updateData['file_path'] = str_replace('//', '/', $updateData['file_path']);

		// Handle image file upload if present in $data
	    if ($request->hasFile('image')) {
			// Get the old image path
			$oldImagePath = $audio->image;

			// Process the new image
			$image = $request->file('image');
			$fileNameWithExt = $image->getClientOriginalName();
			$fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
			$extension = $image->getClientOriginalExtension();
			$fileNameToStore = $fileName . '_' . time() . '.' . $extension;

			$year = date('Y');
			$month = date('m');
			$directory = "uploads/{$year}/{$month}";

			// Create directory if it doesn't exist
			if (!file_exists(public_path($directory))) {
				mkdir(public_path($directory), 0777, true);
			}

			// Move the file to the directory
			$path = $image->move(public_path($directory), $fileNameToStore);
			$newImagePath = "{$directory}/{$fileNameToStore}";

			// Update image path in updateData
			$updateData['image'] = $newImagePath;

			// Optionally delete old image file if it exists
			if ($oldImagePath && file_exists(public_path($oldImagePath))) {
				unlink(public_path($oldImagePath));
			}
		}
		
		// dd($updateData);
		$saved = $audio->update($updateData);
		
		if ($saved) {
			return response()->json(['success' => 'Audio updated successfully','message' => 'Audio updated successfully','status' => '200']);
		} else {
			return response()->json(['error' => 'Failed to update audio'], 500);
		}
	}
	
	public function removeAudioImage(Request $request){
		$validator = Validator::make($request->all(), [
			'id' => 'required',
		]);
		if($validator->fails()){
			$this->ajaxError($validator->errors(), trans('common.error'));
		}
		
		if(Audio::where('id',$request->id)->update(['image' => NULL ])){
			$this->sendResponse([], "Image deleted successfully");
		}else{
			$this->sendResponse([], trans('common.delete_error'));
		}
	}


	public function getGpsValidationStatus()
{
    $gpsValidation = GpsValidation::first();

    return response()->json([
        'gps_enabled' => $gpsValidation ? $gpsValidation->gps_enabled : 0
    ], 200); // Explicitly return status code 200
}



public function updateGps(Request $request)
{
    // Validate incoming request
    $request->validate([
        'gps_enabled' => 'required|in:1,0',
    ]);

    // Find the first record or create a new one if not found
    $gpsValidation = GpsValidation::first() ?? new GpsValidation();

    // Update the value and save it
    $gpsValidation->gps_enabled = $request->input('gps_enabled');
    $gpsValidation->save();

    // Log the update for tracking
    \Log::info('GPS validation updated to: ' . $gpsValidation->gps_enabled);

    // Return a successful response
    return response()->json([
        'message' => 'GPS validation setting updated successfully.',
        'gps_enabled' => $gpsValidation->gps_enabled
    ], 200);
}

public function triggerPointDetails(Request $request){
	dd($request->all());
}

}