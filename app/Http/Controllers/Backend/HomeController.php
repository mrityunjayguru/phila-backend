<?php
namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Stop;
use App\Models\Place;
use App\Models\Offer;

class HomeController extends Controller
{
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
	* Show the application dashboard.
	*
	* @return \Illuminate\Contracts\Support\Renderable
	*/
	public function index(){
        
		$user = Auth()->user();
        if(empty($user)){ exit('Unauthorized access'); }
		
		$page		= 'dashboard';
		$page_title = trans('backend.dashboard');

		// Superadmin
        if(in_array($user->user_type, ['superAdmin','Editor'])){
			
			// Statistics
			$data 						= new \stdClass();
            $data->stops				= Stop::count();
            $data->landmark				= Place::where(['type'=>'landmark'])->count();
            $data->shopping				= Place::where(['type'=>'shopping'])->count();
            $data->dining				= Place::where(['type'=>'dining'])->count();
            $data->attraction			= Place::where(['type'=>'attraction'])->count();
            $data->offers				= Offer::where(['status'=>'active'])->where('end_date', '>=', date('Y-m-d'))->count();
            $data->attraction_chat		= '';
            return view('backend.dashboard.'.$user->user_type,compact('page', 'page_title','data'));

        }else{
            return redirect()->route('firstPage');
        }
    }
	
	public function dashboard(){
		return redirect()->route('home');
    }
}