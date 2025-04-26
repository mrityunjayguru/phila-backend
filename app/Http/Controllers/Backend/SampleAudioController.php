<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Validator, Auth, Storage;
use App\Models\Helpers\CommonHelper;
use App\Models\SampleAudio;
use App\Models\Setting;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\Language;

class SampleAudioController extends CommonController
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
  
	// ADD NEW
	public function index($page_id)
    {
		return view('backend.sample_audio.list',compact('page_id'));
	}

	// CREATE
	public function create()
    {
		$getLanguage = Language::select('language','tag')->get();
		return view('backend.sample_audio.add',compact('getLanguage'));
	}

  // STORE
	public function store(Request $request){
		$user = Auth()->user();
 		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}

		$validator = Validator::make($request->all(), [
			'language'	=> 'required',
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		$data =  $request->all();
		
		try{
			$data = [
				'languages'       => $request->language,
				'status'	  => $request->status,
			];
		
			// Audio UPLOAD
			if(!empty($request->audio) && $request->audio != 'undefined'){
				$data['audio'] =  $this->saveMedia($request->file('audio'));
			}
			
			if($request->id){
				// UPDATE
				$sample  = SampleAudio::find($request->id);
				
				$sample->fill($data);
				$return  =  $sample->save();
				if($return){
					$this->sendResponse([], trans('common.updated_success'));
				}
			} else{
				// CREATE
				$return = SampleAudio::create($data);
				if($return){
					$this->sendResponse([], trans('common.added_success'));
				}
			}
			$this->ajaxError([], trans('common.try_again'));

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
  
	public function ajax_list(Request $request)
    {
		// dd($request->all());
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
           $query = SampleAudio::query();
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
				$query->where('languages','like','%'.$request->search.'%');
			}
			$query->where('page_id',$request->page_id);
			$data = $query->orderBy('id', 'ASC')->offset($start)->limit($count)->get();
			
			
			if($data){
				foreach($data as $key=> $list){
					$delete_btn = '';
				// 	if(Setting::UserPermission('audio-delete')){
				// 		$delete_btn = '<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>';
				// 	}
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route('edit-sample-audio', ['page_id' => $request->page_id, 'id' => $list->id]) .'"><i class="fa fa-eye"></i></a>'. $delete_btn .'
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
			$audio = SampleAudio::find($request->item_id);
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
	public function edit($page_id,$id)
    {
		$data = SampleAudio::find($id);
		$getLanguage = Language::select('language','tag')->get();
		$selectedLanguage = $data->languages; // assuming 'languages' holds the selected language tag
        return view('backend.sample_audio.edit', compact('data','getLanguage','selectedLanguage','page_id'));
	}

	// Update the audio file
    public function update(Request $request)
    {
		try {
			$data = $request->all();
			$id = $data['id'];
			// Find the audio record by ID
			$audio = SampleAudio::where('id', $id)->first();
			
			if (!$audio) {
				// Handle case where audio record with $id is not found
				return response()->json(['message' => 'Audio not found', 'status' => false]);
			}

			// Update the attributes based on the request data
			$audio->id = $request->input('id');
			$audio->languages = $request->input('languages');
			// 'languages'       => $request->language,
			$audio->status = $request->input('status');

			// Handle audio file upload if provided
			if ($request->hasFile('audio')) {
				$audio->audio = $this->saveMedia($request->file('audio'),'audio');
			}
			// Save the updated audio record
			$return = $audio->save();
			
			if ($return) {	
				$this->sendResponse([], trans('common.updated_success'));
			}
		} 
		catch (\Exception $e) {
			// Handle any exceptions that occur during the update process
			return response()->json(['message' => $e->getMessage(), 'status' => false]);
		}
    }

	protected function saveMedia($file)
	{
		$currentYear = date('Y');
    	$currentMonth = date('m');
		
		$directory = 'uploads/sample_audio';
    
		// Ensure the directory exists
		if (!file_exists(public_path($directory))) {
			mkdir(public_path($directory), 0777, true);
		}
		
		// Generate the file name
		$fileName = time() . '_' . $file->getClientOriginalName();
		
		// Move the file to the public directory
		$filePath = $file->move(public_path($directory), $fileName);
		
		if (!$filePath) {
			throw new \Exception('Error saving media file');
		}
		
		return $directory . '/' . $fileName;
	}
}