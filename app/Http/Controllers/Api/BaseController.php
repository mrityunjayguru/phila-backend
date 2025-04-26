<?php
namespace App\Http\Controllers\Api;

use App;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Authy\AuthyApi;
use App\Models\User;


class BaseController extends Controller
{
	public function __construct()
	{
		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
			App::setLocale($_SERVER['HTTP_ACCEPT_LANGUAGE']);
		}
	}

	/**
	* success response method.
	*
	* @return \Illuminate\Http\Response
	*/
  
	public function sendResponse($message,$result = [],$status= '200'){
		$response = [
					  'success' => "1",
					  'status'  => $status,
					  'message' => $message,
					  'data' => new \stdClass(),
					];

		if(!empty($result)){
		  $response['data'] = $result;
		}
		return response()->json($response, 200);
	}
  
	/**
	* success response method.
	*
	* @return \Illuminate\Http\Response
	*/
	public function sendArrayResponse($message,$result = [],$status= '200'){
		$response = [
					  'success' => "1",
					  'status'  => $status,
					  'message' => $message,
					  'data' => [],
					];

		if(!empty($result)){
		  $response['data'] = $result;
		}
		return response()->json($response, 200);
	}

	/**
	* return error response.
	*
	* @return \Illuminate\Http\Response
	*/
	public function sendError($message,$result = [], $code = 200 , $status= '201'){
		$response = [
					  'success' => "0",
					  'status'  => $status,
					  'message' => $message,
					  'data' => new \stdClass(),
					];

		if(!empty($result)){ $response['data'] = $result; }
		return response()->json($response, $code);
	}

	/**
	* return error response.
	*
	* @return \Illuminate\Http\Response
	*/
	public function sendArrayError($message,$result = [], $code = 200 , $status= '201'){
		$response = [
					  'success' => "0",
					  'status'  => $status,
					  'message' => $message,
					  'data'    => array(),
					];

		if(!empty($result)){ $response['data'] = $result; }
		return response()->json($response, $code);
	}
	
	/**
	* return validation error response.
	*
	* @return \Illuminate\Http\Response
	*/
	public function sendValidationError($message,$result = [], $code = 200 , $status= '201'){
		$response = [
					  'success' => "0",
					  'status'  => $status,
					  'message' => $message,
					  'data' => new \stdClass(),
					];

		if(!empty($result)){ $response['data'] = $result; }
		return response()->json($response, $code);
	}
	/**
	* special conditions
	*
	* @return \Illuminate\Http\Response
	*/
	public function sendException($message,$result = [], $status= '201'){
		$response = [
			'success' => "1",
			'status'  => $status,
			'message' => $message,
			'data' => new \stdClass(),
		];

		if(!empty($result)){ $response['data'] = $result; }
		return response()->json($response, 200);
	}
}