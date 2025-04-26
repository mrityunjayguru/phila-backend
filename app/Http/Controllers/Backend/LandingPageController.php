<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Validator, Auth, Storage;
use App\Models\Helpers\CommonHelper;
use App\Models\LandingPage;
use App\Models\SampleAudio;
use App\Models\Language;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Log;
use App\Models\Setting;

class LandingPageController extends CommonController
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
  
	// LISTING DATA
	public function index()
    {
		$data = LandingPage::count();
		return view('backend.landing_page.list',compact('data'));
	}

	// CREATE
	public function create()
    {
		return view('backend.landing_page.add');
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
			'description'   => 'required',
			'route_length'  => 'required|numeric',
			'route_time'  => 'required|numeric',
			'number_of_stops'  => 'required|numeric',
			'status'     => 'required',
			'priority'     => 'required',
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()], 400);
		}
		try {
			$data = [
				'title'       => $request->title,
				'route_length'    => $request->route_length,
				'route_time'   => $request->route_time,
				'number_of_stops'   => $request->number_of_stops,
				'description' => $request->description,
				'status'      => $request->status,
				'priority'      => $request->priority,
				'is_code_dependency' => $request->is_code_dependecy
			];
			// dd($data);
			// Handle Audio Upload		
			
			
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
				$directory = "uploads/{$year}/{$month}/landing_page";

				// Create the directory if it doesn't exist
				if (!file_exists(public_path($directory))) {
					mkdir(public_path($directory), 0777, true);
				}

            	// Move the file to the newly created directory
				$path = $image->move(public_path($directory), $fileNameToStore);

				// Store the relative file path in the database
				$data['image'] = "{$directory}/{$fileNameToStore}";
			}
			
			// Create or update record in database
			if ($request->id) {
				$page = LandingPage::find($request->id);
				$page->fill($data);
				$saved = $page->save();
			} else {
				$saved = LandingPage::create($data);
			}

			// dd($saved->id);
			$audios = [
				asset('uploads/sample_audio/1722511566_russian.mp3'),
				asset('uploads/sample_audio/1721723843_music-2599.mp3'),
				asset('uploads/sample_audio/1721628203_battle-of-the-dragons-8037.mp3'),
				asset('uploads/sample_audio/1721125648_french alphabet.mp3'),
				asset('uploads/sample_audio/1721125665_japanese-conversation.mp3'),
				asset('uploads/sample_audio/1721125697_russian.mp3'),
				asset('uploads/sample_audio/1721125716_Italian.mp3'),
				asset('uploads/sample_audio/1721726712_tera-ban-jaunga-ringtone-45467-45516.mp3'),
				asset('uploads/sample_audio/1721125758_russian.mp3'),
				asset('uploads/sample_audio/1721125775_chinese.mp3'),
				asset('uploads/sample_audio/1721125792_portugese.mp3'),
				asset('uploads/sample_audio/1721125811_Italian.mp3'),
			];
			// dd($audio);
			$getLanguage = Language::all();
			foreach ($getLanguage as $index => $lang) {
				if (isset($audios[$index])) {
					$sampleAudio = new SampleAudio();
					$sampleAudio->page_id = $saved->id;
					$sampleAudio->languages = $lang->language;
					$sampleAudio->tag = $lang->tag;
					$sampleAudio->audio = $audios[$index];
					$sampleAudio->save();
				}
			}

			if ($saved) {
				return response()->json(['success' => 'Page saved successfully','message' => 'Page saved successfully','status' => '200']);
			} else {
				return response()->json(['error' =>  $validator->errors()->all()], 400);
			}
		} catch (Exception $e) {
			return response()->json(['error' => $e->getMessage()], 500);
		}
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
           $query = LandingPage::query();
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
			$data = $query->offset($start)->limit($count)->get();
			
			
			if($data){
				foreach($data as $key=> $list){
					$delete_btn = '';
					if(Setting::UserPermission('audio-delete')){
						// $delete_btn = '<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>';
					}
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("edit-page",$list->id) .'"><i class="fa fa-eye"></i></a>'. $delete_btn .'
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
	public function edit($id = null)
    {
		$data = LandingPage::find($id);
        return view('backend.landing_page.edit', compact('data'));
	}

	public function update(Request $request)
	{
        try {
			$data = $request->all();
		
			$id = $data['id'];
			// Find the audio record by ID
			$page = LandingPage::where('id', $id)->first();
			if (!$page) {
				return response()->json(['message' => 'Page not found', 'status' => false]);
			}

			// Update the attributes based on the request data
			$page->id = $request->input('id');
			$page->title = $request->input('title');
			$page->status = $request->input('status');
			$page->description = $request->input('description');
			$page->priority = $request->input('priority');
			$page->is_code_dependecy = $request->input('is_code_dependecy', 'no');
            $page->route_length = $request->input('route_length');
            $page->route_time = $request->input('route_time');
            $page->number_of_stops = $request->input('number_of_stops');
			
			// Handle image file upload if provided
			if(!empty($request->image) && $request->image != 'undefined'){
				$page->image  =  $this->saveMedia($request->file('image'),'image');
			}
			// dd($page);
			// Save the updated audio record
			$return = $page->save();
			
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
		
		 $directory = 'uploads/' . $currentYear . '/' . $currentMonth.'/landing_page';
    
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