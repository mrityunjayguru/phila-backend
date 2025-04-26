<?php

namespace App\Models\Helpers;

use Illuminate\Support\Facades\Storage;
use DB;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Setting;
use Illuminate\Support\Facades\File;

trait CommonHelper
{
    //public variables
    public $media_path = 'uploads/';
	
    
	/**
	* GET Directory
	*/
	public function get_upload_directory($folder = ''){
	    $year 	= date("Y");
		$month 	= date("m");
		$folder = $folder ? $folder . '/' : '';
		
		$media_path1 = public_path($this->media_path . $folder . $year.'/');
		$media_path2 = public_path($this->media_path . $folder . $year .'/'. $month.'/');
		$media_path3 = $this->media_path . $folder . $year .'/'. $month.'/';
		// dd($media_path1);
		if(!is_dir($media_path1)){
			mkdir($media_path1, 0755, true);
		}
		if(!is_dir($media_path2)){
			mkdir($media_path2, 0755, true);
		}
		
		return $media_path3;
	}
	
	/**
	* Save different type of media into different folders
	*/
		public function saveMedia($file, $folder = '',  $type = '', $width = '', $height = ''){
		
		if(empty($file)){ return; }
		
		$upload_directory 	= $this->get_upload_directory($folder);
		$name 				= md5($file->getClientOriginalName() . time() . rand());
		// $extension 			= $file->getClientOriginalExtension();
        $extension          = $file->guessExtension();
		$fullname 			= $name . '.' . $extension;
		$thumbnail 			= $name .'150X150.'. $extension;
		
		// CREATE THUMBNAIL IMAGE
		// $img = Image::make(public_path($fullname))->resize(150, 150)->insert(public_path($thumbnail));
		
        if($type == ''){
			$file->move(public_path($upload_directory),$fullname);
            return $upload_directory . $fullname;
        } else if($type == 'image'){
            DB::beginTransaction();
            try{
                $path = Storage::disk('s3')->put('images/originals', $file,'public');
                DB::commit();
                return $path;
            } catch(\Exception $e){
                DB::rollback();
                $path = '-';
                return $path;
            } 
        } else if($type == 'audio'){
            DB::beginTransaction();
            try{
                $path = Storage::disk('s3')->put('images/originals', $file,'public');
                DB::commit();
                return $path;
            } catch(\Exception $e){
                DB::rollback();
                $path = '-';
                return $path;
            } 
        } else {
            return false;
        }
    }

    // CREATE ORDER HESABE
    public static function gateway_create_order($data = array())
    {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_URL, '');
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 80);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
		$posts_result = json_decode(curl_exec($ch), TRUE);
        curl_close($ch);
        return $posts_result;
    }


    // HESABE ENCRIPT ORDER
    public static function verify_order($data = array())
    {
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_URL, '');
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 80);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
		$posts_result = json_decode(curl_exec($ch), TRUE);
        curl_close($ch);
        return $posts_result;
    }
	
	// After Verify Order Run this Function
	public static function order_finalization($order_id = 0, $user = []){
		
		$payment_method	= 'Cash';
		$templateItems 	= [];
		
		// GET ORDER DATA
		$order_data = Order::where(['id'=>$order_id])->first();
		if($order_data){
			
			// GET Order Items
			$order_items = OrderItem::where(['order_id'=>$order_data->id])->get();
			foreach($order_items as $key=> $list){
				
				// validate Items
				$product = Product::where(['id'=> $list->product_id])->first();
				if(!empty($product)){
					$templateItems[] = [
					  'image' => $product->image ? asset('public/'. $product->image) : asset('public/'.env("DEFAULT_IMAGE")),
					  'title' => $product->title,
					  'price' => $product->price,
					];
				}
			}
			
			// Delete existing cart
			Cart::where(['user_id'=>$user->id])->delete();
			
			$order_data->status = 'New';
			if($order_data->payment_method_id == 2){
				$payment_method	= 'Card';
				$order_data->payment_status = '1';
			}
            $order_data->update();
            DB::commit();
			
			// send SMS
			//$message = trans('customer_api.dear'). ' '. $user->name .',\r\n '. trans('customer_api.your_order_has_been_successfully_created') .'\r\n'. trans('customer_api.thank_you_for_choosing_amen_inch');
			//CommonHelper::sendMessage($user->country_code. $user->phone_number, $message);
			
			
			// Send Push Notification
			$message = trans('customer_api.you_have_new_order_from_customer') .' '. $user->name;
			//$restaurant = User::where('id', $order_data->owner_id)->first();
			//CommonHelper::send_notification($restaurant, 'New Order', $message, '1', $order_id, $order_data);
			

			// send Mail
			$template_data = new \stdClass();
			$template_data->user				= $user;
			$template_data->date				= date('F d, Y');
			$template_data->time				= date('h: i');
			$template_data->order_id			= $order_id;
			$template_data->order_items			= $templateItems;
			$template_data->restaurant_logo		= asset(env("DEFAULT_IMAGE"));
			$template_data->shipping_address	= $order_data->shipping_address;
			$template_data->shipping_charge		= $order_data->shipping_charge;
			$template_data->payment_method 		= $payment_method;
			$template_data->total				= $order_data->total;
			$template_data->grand_total			= $order_data->grand_total;
			$template_data->delivery_fee		= '0.00';
			
			CommonHelper::sendMail($user->email, 'Order created Successfully', 'email-templates.create-order-customer', $template_data);
		}
	}

	// SEND OTP
	public static function sendOTP($user, $otp='', $message = ''){
		
		// send SMS
		return CommonHelper::sendSMS($user->country_code. $user->phone_number, $message);
    }
	
	//SEND MESSAGE
	public static function sendMessage($to, $message = ""){
		
		// send SMS
		return CommonHelper::sendSMS($to, $message);
		
		// send WhatsApp
		return CommonHelper::sendWhatsApp($to, $message);
	}
	
	//SEND SMS
	public static function sendSMS($to, $message = ""){
		if(empty($to)){ return; }
		if(empty($message)){ return; }
		
		try {

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"http://sms.hspsms.com/sendSMS?username=". config('constants.SMS_USERNAME') ."&message=". urlencode($message) ."&sendername=". config('constants.SMS_SENDER') ."&smstype=TRANS&numbers=$to&apikey=". config('constants.SMS_TOKEN'));
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close ($ch);
			return $response;
			
		} catch (Exception $e) {
			return false;
		}
	}
	
	//SEND MAIL
	public static function sendMail($email = '', $subject = '', $template = '', $template_data = null){
		$data = array('email'=>$email,'subject'=>$subject, 'template_data'=>$template_data);
		
		try {
			$url = 'https://api.sendgrid.com/v3/mail/send';
			$body = view($template, compact('template_data'))->render();
			$authorization = config('constants.MAIL_TOKEN');
			
			$data = array (
			  'personalizations' => array (0 => array ('to' => array (0 => array ('email' => $email,),),),),
			  'from' => array ('name' => config('constants.APP_NAME'),'email' => config('constants.MAIL_FROM'),),
			  'subject' => $subject,
			  'content' => array (0 => array ('type' => 'text/html','value' => $body,),),
			);
			$postdata = json_encode($data);
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_TIMEOUT, 80);
			$response = curl_exec($ch);
			
			if(curl_error($ch)){
				$return = false;
			} else{
				$return = $response;
			}
			curl_close($ch);
			return $return;
		} catch (Exception $e) {
			return false;
		}
	}
	
	//SEND SMS
	public static function sendWhatsApp($to, $message = ""){
		if(empty($to)){ return; }
		if(empty($message)){ return; }
		
		try {

			
			
		} catch (Exception $e) {
			return false;
		}
	}
	
	//SEND MAIL
	public static function send_contactMail($email = '', $subject = '', $template = '', $template_data = null){
		$data = array('email'=>$email,'subject'=>$subject, 'template_data'=>$template_data);
		
		try {
			Mail::send($template, $data, function($mail) use ($data) {
			  $mail->to($data['email'], '')->subject($data['subject']);
			  $mail->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
			  //$mail->htmlData = $data;
			});
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	
	// SEND MOBILE NOTIFICATION
	public static function send_notification($user = '', $title = '', $message = '', $type = '', $type_id = '', $token = ''){
		$fcm_server_key	= Setting::get('fcm_server_key');
		$data = [
		  //"user_id"   	=> (string) $user->id,
		  "title"      		=> (string) $title,
		  "message"			=> (string) $message,
		  "type"      		=> (string) $type,
		  "type_id"   		=> (string) $type_id,
		  "date_time" 		=> date('Y-m-d H:i:s'),
		  "token" 			=> $token,
		];
		
		try {
		  $insert = Notification::create($data);
		  if($insert){
			if($insert->id && $token){
			  $data['notification_id'] = (string) $insert->id;
			  
			  $sendArray = [
				"to" => $token,
				"notification" => ["title"=>$title,"body"=>$message],
				"data"=>$data,
			  ];
			  
			  $sendArray__ = [
					"to" => $token, 
					"notification" => [
						"body" => $message,
						"title" => $title,
					], 
					"android" => [
						"priority" => "high",
					],
					"apns" => [
						"headers" => ["apns-priority" => "10",],
						"payload" => ["aps" => ["sound" => "default",],],
					],
					"data" => [
						"click_action" => "FLUTTER_NOTIFICATION_CLICK",
						"sound" => "default",
						"content_available" => true,
						"mutable_content" => true,
					],
			  ];

			  $headers = array ( 'Authorization: key=' . $fcm_server_key, 'Content-Type: application/json' );
			  $ch = curl_init(); curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' ); 
			  curl_setopt( $ch,CURLOPT_POST, true ); 
			  curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers ); 
			  curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true ); 
			  curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($sendArray));
			  $result = json_decode(curl_exec($ch), TRUE);
			  curl_close ($ch);
			  if(!empty($result) && $result['success'] == 1){
				$insert->is_sent = 1;
				$insert->update();
				return $result;
			  }else{
				return FALSE;
			  }
			}
		  }
		} catch (\Exception $e) { 
		  return FALSE;
		}
	}
	
	// MAP ROUTES
	public static function mapRouts($type = ''){
		if($type == 'tour'){
			$data = [
					['id'=>'2', 'latitude'=>'39.950138', 'longitude'=>'-75.148990'],
					['id'=>'3', 'latitude'=>'39.950402', 'longitude'=>'-75.148939'],
					['id'=>'4', 'latitude'=>'39.950797', 'longitude'=>'-75.148870'],
					['id'=>'5', 'latitude'=>'39.951952', 'longitude'=>'-75.148534'],
					['id'=>'6', 'latitude'=>'39.952756', 'longitude'=>'-75.148470'],
					['id'=>'7', 'latitude'=>'39.953332', 'longitude'=>'-75.148336'],
					['id'=>'8', 'latitude'=>'39.953492', 'longitude'=>'-75.148247'],
					['id'=>'9', 'latitude'=>'39.953710', 'longitude'=>'-75.148014'],
					['id'=>'10', 'latitude'=>'39.954304', 'longitude'=>'-75.147870'],
					['id'=>'11', 'latitude'=>'39.954369', 'longitude'=>'-75.147647'],
					['id'=>'12', 'latitude'=>'39.954233', 'longitude'=>'-75.146561'],
					['id'=>'13', 'latitude'=>'39.954194', 'longitude'=>'-75.146258'],
					['id'=>'14', 'latitude'=>'39.954134', 'longitude'=>'-75.145837'],
					['id'=>'15', 'latitude'=>'39.954124', 'longitude'=>'-75.145696'],
					['id'=>'16', 'latitude'=>'39.954034', 'longitude'=>'-75.144976'],
					['id'=>'17', 'latitude'=>'39.953991', 'longitude'=>'-75.144661'],
					['id'=>'18', 'latitude'=>'39.953922', 'longitude'=>'-75.144064'],
					['id'=>'19', 'latitude'=>'39.953906', 'longitude'=>'-75.143959'],
					['id'=>'20', 'latitude'=>'39.953873', 'longitude'=>'-75.143686'],
					['id'=>'21', 'latitude'=>'39.953796', 'longitude'=>'-75.143086'],
					['id'=>'22', 'latitude'=>'39.953666', 'longitude'=>'-75.142942'],
					['id'=>'23', 'latitude'=>'39.953323', 'longitude'=>'-75.143016'],
					['id'=>'24', 'latitude'=>'39.953141', 'longitude'=>'-75.143048'],
					['id'=>'25', 'latitude'=>'39.953068', 'longitude'=>'-75.143069'],
					['id'=>'26', 'latitude'=>'39.952079', 'longitude'=>'-75.143271'],
					['id'=>'27', 'latitude'=>'39.952014', 'longitude'=>'-75.143292'],
					['id'=>'28', 'latitude'=>'39.951946', 'longitude'=>'-75.143306'],
					['id'=>'29', 'latitude'=>'39.952111', 'longitude'=>'-75.144639'],
					['id'=>'30', 'latitude'=>'39.951961', 'longitude'=>'-75.143416'],
					['id'=>'31', 'latitude'=>'39.952061', 'longitude'=>'-75.144201'],
					['id'=>'32', 'latitude'=>'39.952734', 'longitude'=>'-75.149679'],
					['id'=>'33', 'latitude'=>'39.953903', 'longitude'=>'-75.159111'],
					['id'=>'34', 'latitude'=>'39.953941', 'longitude'=>'-75.159348'],
					['id'=>'35', 'latitude'=>'39.953905', 'longitude'=>'-75.159457'],
					['id'=>'36', 'latitude'=>'39.953905', 'longitude'=>'-75.159457'],
					['id'=>'37', 'latitude'=>'39.952100', 'longitude'=>'-75.159859'],
					['id'=>'38', 'latitude'=>'39.951955', 'longitude'=>'-75.159886'],
					['id'=>'39', 'latitude'=>'39.951998', 'longitude'=>'-75.160274'],
					['id'=>'40', 'latitude'=>'39.952259', 'longitude'=>'-75.162308'],
					['id'=>'41', 'latitude'=>'39.952430', 'longitude'=>'-75.162522'],
					['id'=>'42', 'latitude'=>'39.952492', 'longitude'=>'-75.162577'],
					['id'=>'43', 'latitude'=>'39.952985', 'longitude'=>'-75.162477'],
					['id'=>'44', 'latitude'=>'39.953122', 'longitude'=>'-75.162510'],
					['id'=>'45', 'latitude'=>'39.953249', 'longitude'=>'-75.162638'],
					['id'=>'46', 'latitude'=>'39.953310', 'longitude'=>'-75.162911'],
					['id'=>'47', 'latitude'=>'39.953362', 'longitude'=>'-75.163307'],
					['id'=>'48', 'latitude'=>'39.953450', 'longitude'=>'-75.163825'],
					['id'=>'49', 'latitude'=>'39.953601', 'longitude'=>'-75.164603'],
					['id'=>'50', 'latitude'=>'39.953690', 'longitude'=>'-75.165339'],
					['id'=>'51', 'latitude'=>'39.953826', 'longitude'=>'-75.166255'],
					['id'=>'52', 'latitude'=>'39.954004', 'longitude'=>'-75.166493'],
					['id'=>'53', 'latitude'=>'39.954197', 'longitude'=>'-75.166508'],
					['id'=>'54', 'latitude'=>'39.954651', 'longitude'=>'-75.166414'],
					['id'=>'55', 'latitude'=>'39.954911', 'longitude'=>'-75.166369'],
					['id'=>'56', 'latitude'=>'39.955075', 'longitude'=>'-75.166589'],
					['id'=>'57', 'latitude'=>'39.955750', 'longitude'=>'-75.167504'],
					['id'=>'58', 'latitude'=>'39.956300', 'longitude'=>'-75.168226'],
					['id'=>'59', 'latitude'=>'39.956797', 'longitude'=>'-75.168950'],
					['id'=>'60', 'latitude'=>'39.957166', 'longitude'=>'-75.169419'],
					['id'=>'61', 'latitude'=>'39.957299', 'longitude'=>'-75.169623'],
					['id'=>'62', 'latitude'=>'39.957405', 'longitude'=>'-75.169739'],
					['id'=>'63', 'latitude'=>'39.957508', 'longitude'=>'-75.169812'],
					['id'=>'64', 'latitude'=>'39.957638', 'longitude'=>'-75.169847'],
					['id'=>'65', 'latitude'=>'39.957756', 'longitude'=>'-75.169851'],
					['id'=>'66', 'latitude'=>'39.957874', 'longitude'=>'-75.169820'],
					['id'=>'67', 'latitude'=>'39.957986', 'longitude'=>'-75.169822'],
					['id'=>'68', 'latitude'=>'39.958092', 'longitude'=>'-75.169839'],
					['id'=>'69', 'latitude'=>'39.958214', 'longitude'=>'-75.169884'],
					['id'=>'70', 'latitude'=>'39.958336', 'longitude'=>'-75.169959'],
					['id'=>'71', 'latitude'=>'39.958421', 'longitude'=>'-75.170073'],
					['id'=>'72', 'latitude'=>'39.958492', 'longitude'=>'-75.170240'],
					['id'=>'73', 'latitude'=>'39.958535', 'longitude'=>'-75.170339'],
					['id'=>'74', 'latitude'=>'39.958569', 'longitude'=>'-75.170471'],
					['id'=>'75', 'latitude'=>'39.958577', 'longitude'=>'-75.170560'],
					['id'=>'76', 'latitude'=>'39.958570', 'longitude'=>'-75.170666'],
					['id'=>'77', 'latitude'=>'39.958539', 'longitude'=>'-75.170848'],
					['id'=>'78', 'latitude'=>'39.958528', 'longitude'=>'-75.170966'],
					['id'=>'79', 'latitude'=>'39.958558', 'longitude'=>'-75.171145'],
					['id'=>'80', 'latitude'=>'39.958617', 'longitude'=>'-75.171302'],
					['id'=>'81', 'latitude'=>'39.958747', 'longitude'=>'-75.171540'],
					['id'=>'82', 'latitude'=>'39.958871', 'longitude'=>'-75.171711'],
					['id'=>'83', 'latitude'=>'39.958961', 'longitude'=>'-75.171841'],
					['id'=>'84', 'latitude'=>'39.959008', 'longitude'=>'-75.171930'],
					['id'=>'85', 'latitude'=>'39.959062', 'longitude'=>'-75.171981'],
					['id'=>'86', 'latitude'=>'39.959146', 'longitude'=>'-75.171955'],
					['id'=>'87', 'latitude'=>'39.959262', 'longitude'=>'-75.171922'],
					['id'=>'88', 'latitude'=>'39.959330', 'longitude'=>'-75.171906'],
					['id'=>'89', 'latitude'=>'39.959412', 'longitude'=>'-75.172092'],
					['id'=>'90', 'latitude'=>'39.959498', 'longitude'=>'-75.172211'],
					['id'=>'91', 'latitude'=>'39.959709', 'longitude'=>'-75.172504'],
					['id'=>'92', 'latitude'=>'39.959929', 'longitude'=>'-75.172791'],
					['id'=>'93', 'latitude'=>'39.960126', 'longitude'=>'-75.173060'],
					['id'=>'94', 'latitude'=>'39.960316', 'longitude'=>'-75.173317'],
					['id'=>'95', 'latitude'=>'39.960445', 'longitude'=>'-75.173495'],
					['id'=>'96', 'latitude'=>'39.960587', 'longitude'=>'-75.173680'],
					['id'=>'97', 'latitude'=>'39.960755', 'longitude'=>'-75.173936'],
					['id'=>'98', 'latitude'=>'39.960870', 'longitude'=>'-75.174094'],
					['id'=>'99', 'latitude'=>'39.961197', 'longitude'=>'-75.174541'],
					['id'=>'100', 'latitude'=>'39.961465', 'longitude'=>'-75.174900'],
					['id'=>'101', 'latitude'=>'39.961551', 'longitude'=>'-75.175006'],
					['id'=>'102', 'latitude'=>'39.961788', 'longitude'=>'-75.174977'],
					['id'=>'103', 'latitude'=>'39.961939', 'longitude'=>'-75.174944'],
					['id'=>'104', 'latitude'=>'39.962504', 'longitude'=>'-75.174818'],
					['id'=>'105', 'latitude'=>'39.962964', 'longitude'=>'-75.174708'],
					['id'=>'106', 'latitude'=>'39.963501', 'longitude'=>'-75.174590'],
					['id'=>'107', 'latitude'=>'39.964469', 'longitude'=>'-75.174369'],
					['id'=>'108', 'latitude'=>'39.965755', 'longitude'=>'-75.174084'],
					['id'=>'109', 'latitude'=>'39.967177', 'longitude'=>'-75.173781'],
					['id'=>'110', 'latitude'=>'39.967313', 'longitude'=>'-75.173766'],
					['id'=>'111', 'latitude'=>'39.967314', 'longitude'=>'-75.174023'],
					['id'=>'112', 'latitude'=>'39.967333', 'longitude'=>'-75.174732'],
					['id'=>'113', 'latitude'=>'39.967367', 'longitude'=>'-75.176570'],
					['id'=>'114', 'latitude'=>'39.967408', 'longitude'=>'-75.178507'],
					['id'=>'115', 'latitude'=>'39.967419', 'longitude'=>'-75.179311'],
					['id'=>'116', 'latitude'=>'39.967402', 'longitude'=>'-75.179869'],
					['id'=>'117', 'latitude'=>'39.967091', 'longitude'=>'-75.179540'],
					['id'=>'118', 'latitude'=>'39.967089', 'longitude'=>'-75.179533'],
					['id'=>'119', 'latitude'=>'39.966882', 'longitude'=>'-75.179285'],
					['id'=>'120', 'latitude'=>'39.966678', 'longitude'=>'-75.179060'],
					['id'=>'121', 'latitude'=>'39.966443', 'longitude'=>'-75.178838'],
					['id'=>'122', 'latitude'=>'39.966068', 'longitude'=>'-75.178446'],
					['id'=>'123', 'latitude'=>'39.965506', 'longitude'=>'-75.177716'],
					['id'=>'124', 'latitude'=>'39.965233', 'longitude'=>'-75.177461'],
					['id'=>'125', 'latitude'=>'39.964810', 'longitude'=>'-75.176971'],
					['id'=>'126', 'latitude'=>'39.964378', 'longitude'=>'-75.176422'],
					['id'=>'127', 'latitude'=>'39.964392', 'longitude'=>'-75.176636'],
					['id'=>'128', 'latitude'=>'39.964531', 'longitude'=>'-75.177645'],
					['id'=>'129', 'latitude'=>'39.964524', 'longitude'=>'-75.177903'],
					['id'=>'129', 'latitude'=>'39.964653', 'longitude'=>'-75.178127'],
					['id'=>'129', 'latitude'=>'39.964768', 'longitude'=>'-75.178287'],
					['id'=>'129', 'latitude'=>'39.964899', 'longitude'=>'-75.178598'],
					['id'=>'129', 'latitude'=>'39.964817', 'longitude'=>'-75.178995'],
					['id'=>'130', 'latitude'=>'39.964668', 'longitude'=>'-75.179273'],
					['id'=>'131', 'latitude'=>'39.964497', 'longitude'=>'-75.179455'],
					['id'=>'132', 'latitude'=>'39.964249', 'longitude'=>'-75.179739'],
					['id'=>'133', 'latitude'=>'39.964137', 'longitude'=>'-75.179852'],
					['id'=>'134', 'latitude'=>'39.964032', 'longitude'=>'-75.179924'],
					['id'=>'135', 'latitude'=>'39.963948', 'longitude'=>'-75.179941'],
					['id'=>'136', 'latitude'=>'39.963810', 'longitude'=>'-75.179950'],
					['id'=>'137', 'latitude'=>'39.963680', 'longitude'=>'-75.179899'],
					['id'=>'138', 'latitude'=>'39.963610', 'longitude'=>'-75.179851'],
					['id'=>'139', 'latitude'=>'39.963526', 'longitude'=>'-75.179760'],
					['id'=>'140', 'latitude'=>'39.963411', 'longitude'=>'-75.179587'],
					['id'=>'141', 'latitude'=>'39.963195', 'longitude'=>'-75.179240'],
					['id'=>'142', 'latitude'=>'39.962961', 'longitude'=>'-75.178748'],
					['id'=>'143', 'latitude'=>'39.962876', 'longitude'=>'-75.178464'],
					['id'=>'144', 'latitude'=>'39.962794', 'longitude'=>'-75.178155'],
					['id'=>'145', 'latitude'=>'39.962710', 'longitude'=>'-75.177984'],
					['id'=>'146', 'latitude'=>'39.962630', 'longitude'=>'-75.177626'],
					['id'=>'147', 'latitude'=>'39.962592', 'longitude'=>'-75.177461'],
					['id'=>'148', 'latitude'=>'39.962547', 'longitude'=>'-75.177322'],
					['id'=>'149', 'latitude'=>'39.962471', 'longitude'=>'-75.177204'],
					['id'=>'150', 'latitude'=>'39.962170', 'longitude'=>'-75.176801'],
					['id'=>'151', 'latitude'=>'39.961062', 'longitude'=>'-75.175321'],
					['id'=>'152', 'latitude'=>'39.960004', 'longitude'=>'-75.173869'],
					['id'=>'153', 'latitude'=>'39.959159', 'longitude'=>'-75.172742'],
					['id'=>'154', 'latitude'=>'39.959073', 'longitude'=>'-75.172633'],
					['id'=>'155', 'latitude'=>'39.958812', 'longitude'=>'-75.172135'],
					['id'=>'156', 'latitude'=>'39.958804', 'longitude'=>'-75.172094'],
					['id'=>'157', 'latitude'=>'39.958588', 'longitude'=>'-75.172145'],
					['id'=>'158', 'latitude'=>'39.957556', 'longitude'=>'-75.172380'],
					['id'=>'159', 'latitude'=>'39.953605', 'longitude'=>'-75.173208'],
					['id'=>'160', 'latitude'=>'39.952608', 'longitude'=>'-75.165417'],
					['id'=>'161', 'latitude'=>'39.952576', 'longitude'=>'-75.165201'],
					['id'=>'162', 'latitude'=>'39.952315', 'longitude'=>'-75.165268'],
					['id'=>'163', 'latitude'=>'39.952106', 'longitude'=>'-75.165252'],
					['id'=>'164', 'latitude'=>'39.952009', 'longitude'=>'-75.165237'],
					['id'=>'165', 'latitude'=>'39.951913', 'longitude'=>'-75.165200'],
					['id'=>'166', 'latitude'=>'39.951830', 'longitude'=>'-75.165101'],
					['id'=>'167', 'latitude'=>'39.951773', 'longitude'=>'-75.164915'],
					['id'=>'168', 'latitude'=>'39.951624', 'longitude'=>'-75.164005'],
					['id'=>'169', 'latitude'=>'39.951600', 'longitude'=>'-75.163946'],
					['id'=>'170', 'latitude'=>'39.951554', 'longitude'=>'-75.163890'],
					['id'=>'171', 'latitude'=>'39.951483', 'longitude'=>'-75.163875'],
					['id'=>'172', 'latitude'=>'39.945573', 'longitude'=>'-75.165149'],
					['id'=>'173', 'latitude'=>'39.942885', 'longitude'=>'-75.143655'],
					['id'=>'174', 'latitude'=>'39.944318', 'longitude'=>'-75.143325'],
					['id'=>'175', 'latitude'=>'39.944140', 'longitude'=>'-75.141970'],
					['id'=>'176', 'latitude'=>'39.945121', 'longitude'=>'-75.141828'],
					['id'=>'177', 'latitude'=>'39.945285', 'longitude'=>'-75.143117'],
					['id'=>'178', 'latitude'=>'39.945357', 'longitude'=>'-75.143814'],
					['id'=>'179', 'latitude'=>'39.945781', 'longitude'=>'-75.143870'],
					['id'=>'180', 'latitude'=>'39.946181', 'longitude'=>'-75.144099'],
					['id'=>'181', 'latitude'=>'39.946488', 'longitude'=>'-75.144508'],
					['id'=>'182', 'latitude'=>'39.946552', 'longitude'=>'-75.145075'],  
					['id'=>'183', 'latitude'=>'39.946971', 'longitude'=>'-75.145306'], 
					['id'=>'184', 'latitude'=>'39.947127', 'longitude'=>'-75.146357'],
					['id'=>'185', 'latitude'=>'39.948645', 'longitude'=>'-75.146035'], 
					['id'=>'186', 'latitude'=>'39.950179', 'longitude'=>'-75.145687'],
					['id'=>'187', 'latitude'=>'39.950179', 'longitude'=>'-75.145687'],
					['id'=>'188', 'latitude'=>'39.950386', 'longitude'=>'-75.147160'],
					['id'=>'189', 'latitude'=>'39.950596', 'longitude'=>'-75.148790'],
					['id'=>'190', 'latitude'=>'39.950763', 'longitude'=>'-75.150363'],
					['id'=>'191', 'latitude'=>'39.949239', 'longitude'=>'-75.150774'],
					['id'=>'192', 'latitude'=>'39.949053', 'longitude'=>'-75.149234'],
					['id'=>'193', 'latitude'=>'39.949951', 'longitude'=>'-75.149060'],
				];
		}else{
			$data = [
					['id'=>'1', 'latitude'=>'39.964717', 'longitude'=>'-75.179206'],
					['id'=>'2', 'latitude'=>'39.964281', 'longitude'=>'-75.179792'],
					['id'=>'3', 'latitude'=>'39.964191', 'longitude'=>'-75.179980'],
					['id'=>'4', 'latitude'=>'39.964138', 'longitude'=>'-75.180135'],
					['id'=>'5', 'latitude'=>'39.964111', 'longitude'=>'-75.180269'],
					['id'=>'6', 'latitude'=>'39.964092', 'longitude'=>'-75.180446'],
					['id'=>'7', 'latitude'=>'39.964093', 'longitude'=>'-75.180652'],
					['id'=>'8', 'latitude'=>'39.964165', 'longitude'=>'-75.181016'],
					['id'=>'9', 'latitude'=>'39.964477', 'longitude'=>'-75.182026'],										
					['id'=>'10', 'latitude'=>'39.964552', 'longitude'=>'-75.182251'],
					['id'=>'11', 'latitude'=>'39.964707', 'longitude'=>'-75.182189'],										
					['id'=>'12', 'latitude'=>'39.964824', 'longitude'=>'-75.182238'],										
					['id'=>'13', 'latitude'=>'39.965164', 'longitude'=>'-75.182594'],										
					['id'=>'14', 'latitude'=>'39.965449', 'longitude'=>'-75.182685'],										
					['id'=>'15', 'latitude'=>'39.965791', 'longitude'=>'-75.182631'],										
					['id'=>'16', 'latitude'=>'39.966080', 'longitude'=>'-75.182400'],										
					['id'=>'17', 'latitude'=>'39.966654', 'longitude'=>'-75.181701'],										
					['id'=>'18', 'latitude'=>'39.966841', 'longitude'=>'-75.181386'],										
					['id'=>'19', 'latitude'=>'39.966898', 'longitude'=>'-75.180923'],										
					['id'=>'20', 'latitude'=>'39.966784', 'longitude'=>'-75.180493'],										
					['id'=>'21', 'latitude'=>'39.966657', 'longitude'=>'-75.180240'],										
					['id'=>'22', 'latitude'=>'39.966378', 'longitude'=>'-75.179905'],										
					['id'=>'23', 'latitude'=>'39.966225', 'longitude'=>'-75.179709'],										
					['id'=>'24', 'latitude'=>'39.966190', 'longitude'=>'-75.179583'],										
					['id'=>'25', 'latitude'=>'39.966200', 'longitude'=>'-75.179390'],										
					['id'=>'26', 'latitude'=>'39.966291', 'longitude'=>'-75.179221'],										
					['id'=>'27', 'latitude'=>'39.966588', 'longitude'=>'-75.179467'],										
					['id'=>'28', 'latitude'=>'39.966917', 'longitude'=>'-75.179805'],										
					['id'=>'29', 'latitude'=>'39.967074', 'longitude'=>'-75.180024'],										
					['id'=>'30', 'latitude'=>'39.967281', 'longitude'=>'-75.180416'],										
					['id'=>'31', 'latitude'=>'39.967373', 'longitude'=>'-75.180628'],										
					['id'=>'32', 'latitude'=>'39.967495', 'longitude'=>'-75.180967'],										
					['id'=>'33', 'latitude'=>'39.967533', 'longitude'=>'-75.181203'],										
					['id'=>'34', 'latitude'=>'39.967620', 'longitude'=>'-75.181481'],										
					['id'=>'35', 'latitude'=>'39.967759', 'longitude'=>'-75.181708'],										
					['id'=>'36', 'latitude'=>'39.968042', 'longitude'=>'-75.182055'],										
					['id'=>'37', 'latitude'=>'39.968483', 'longitude'=>'-75.182685'],										
					['id'=>'38', 'latitude'=>'39.969023', 'longitude'=>'-75.183739'],										
					['id'=>'39', 'latitude'=>'39.969360', 'longitude'=>'-75.184634'],										
					['id'=>'40', 'latitude'=>'39.969510', 'longitude'=>'-75.185199'],										
					['id'=>'41', 'latitude'=>'39.969618', 'longitude'=>'-75.185908'],										
					['id'=>'42', 'latitude'=>'39.969914', 'longitude'=>'-75.188272'],										
					['id'=>'43', 'latitude'=>'39.970025', 'longitude'=>'-75.189120'],										
					['id'=>'44', 'latitude'=>'39.970214', 'longitude'=>'-75.189548'],										
					['id'=>'45', 'latitude'=>'39.970413', 'longitude'=>'-75.189795'],										
					['id'=>'46', 'latitude'=>'39.970765', 'longitude'=>'-75.190025'],										
					['id'=>'47', 'latitude'=>'39.971244', 'longitude'=>'-75.190093'],										
					['id'=>'48', 'latitude'=>'39.971648', 'longitude'=>'-75.190023'],										
					['id'=>'49', 'latitude'=>'39.972018', 'longitude'=>'-75.189935'],										
					['id'=>'50', 'latitude'=>'39.972338', 'longitude'=>'-75.189962'],										
					['id'=>'51', 'latitude'=>'39.974149', 'longitude'=>'-75.190861'],										
					['id'=>'52', 'latitude'=>'39.975346', 'longitude'=>'-75.191699'],										
					['id'=>'53', 'latitude'=>'39.975722', 'longitude'=>'-75.191985'],										
					['id'=>'54', 'latitude'=>'39.976296', 'longitude'=>'-75.190712'],										
					['id'=>'55', 'latitude'=>'39.976330', 'longitude'=>'-75.190262'],										
					['id'=>'56', 'latitude'=>'39.976299', 'longitude'=>'-75.189951'],										
					['id'=>'57', 'latitude'=>'39.976200', 'longitude'=>'-75.189765'],										
					['id'=>'58', 'latitude'=>'39.976023', 'longitude'=>'-75.189736'],										
					['id'=>'59', 'latitude'=>'39.975904', 'longitude'=>'-75.189831'],										
					['id'=>'60', 'latitude'=>'39.975774', 'longitude'=>'-75.190171'],										
					['id'=>'61', 'latitude'=>'39.975719', 'longitude'=>'-75.190342'],										
					['id'=>'62', 'latitude'=>'39.975565', 'longitude'=>'-75.190607'],										
					['id'=>'63', 'latitude'=>'39.975608', 'longitude'=>'-75.191058'],										
					['id'=>'64', 'latitude'=>'39.975617', 'longitude'=>'-75.191339'],										
					['id'=>'65', 'latitude'=>'39.975616', 'longitude'=>'-75.191447'],										
					['id'=>'66', 'latitude'=>'39.975456', 'longitude'=>'-75.192981'],										
					['id'=>'67', 'latitude'=>'39.975234', 'longitude'=>'-75.194938'],										
					['id'=>'68', 'latitude'=>'39.975397', 'longitude'=>'-75.195025'],										
					['id'=>'69', 'latitude'=>'39.975631', 'longitude'=>'-75.195335'],										
					['id'=>'70', 'latitude'=>'39.975739', 'longitude'=>'-75.195569'],										
					['id'=>'71', 'latitude'=>'39.975763', 'longitude'=>'-75.195714'],										
					['id'=>'72', 'latitude'=>'39.975803', 'longitude'=>'-75.196104'],										
					['id'=>'73', 'latitude'=>'39.976086', 'longitude'=>'-75.197354'],										
					['id'=>'74', 'latitude'=>'39.976226', 'longitude'=>'-75.198397'],										
					['id'=>'75', 'latitude'=>'39.976221', 'longitude'=>'-75.199354'],										
					['id'=>'76', 'latitude'=>'39.976178', 'longitude'=>'-75.199611'],										
					['id'=>'77', 'latitude'=>'39.975928', 'longitude'=>'-75.200293'],										
					['id'=>'78', 'latitude'=>'39.975942', 'longitude'=>'-75.200837'],										
					['id'=>'79', 'latitude'=>'39.976242', 'longitude'=>'-75.201811'],										
					['id'=>'80', 'latitude'=>'39.976306', 'longitude'=>'-75.202260'],										
					['id'=>'81', 'latitude'=>'39.976141', 'longitude'=>'-75.203286'],										
					['id'=>'82', 'latitude'=>'39.975832', 'longitude'=>'-75.204382'],										
					['id'=>'83', 'latitude'=>'39.976504', 'longitude'=>'-75.204741'],										
					['id'=>'84', 'latitude'=>'39.976890', 'longitude'=>'-75.205212'],										
					['id'=>'85', 'latitude'=>'39.977005', 'longitude'=>'-75.205353'],										
					['id'=>'86', 'latitude'=>'39.977081', 'longitude'=>'-75.205397'],										
					['id'=>'87', 'latitude'=>'39.977186', 'longitude'=>'-75.205393'],										
					['id'=>'88', 'latitude'=>'39.977271', 'longitude'=>'-75.205439'],										
					['id'=>'89', 'latitude'=>'39.977338', 'longitude'=>'-75.205545'],										
					['id'=>'90', 'latitude'=>'39.977341', 'longitude'=>'-75.205651'],										
					['id'=>'91', 'latitude'=>'39.977314', 'longitude'=>'-75.205804'],										
					['id'=>'92', 'latitude'=>'39.977346', 'longitude'=>'-75.206084'],										
					['id'=>'93', 'latitude'=>'39.978822', 'longitude'=>'-75.210142'],										
					['id'=>'94', 'latitude'=>'39.977626', 'longitude'=>'-75.210901'],										
					['id'=>'95', 'latitude'=>'39.975804', 'longitude'=>'-75.206097'],										
					['id'=>'96', 'latitude'=>'39.975652', 'longitude'=>'-75.205676'],										
					['id'=>'97', 'latitude'=>'39.975644', 'longitude'=>'-75.205024'],										
					['id'=>'98', 'latitude'=>'39.976208', 'longitude'=>'-75.202823'],										
					['id'=>'99', 'latitude'=>'39.976255', 'longitude'=>'-75.202577'],										
					['id'=>'100', 'latitude'=>'39.976272', 'longitude'=>'-75.202328'],										
					['id'=>'101', 'latitude'=>'39.976246', 'longitude'=>'-75.202013'],										
					['id'=>'102', 'latitude'=>'39.975929', 'longitude'=>'-75.200986'],										
					['id'=>'103', 'latitude'=>'39.975875', 'longitude'=>'-75.200673'],										
					['id'=>'104', 'latitude'=>'39.975870', 'longitude'=>'-75.200437'],										
					['id'=>'105', 'latitude'=>'39.976075', 'longitude'=>'-75.199831'],										
					['id'=>'106', 'latitude'=>'39.976201', 'longitude'=>'-75.199296'],										
					['id'=>'107', 'latitude'=>'39.976219', 'longitude'=>'-75.198662'],										
					['id'=>'108', 'latitude'=>'39.974744', 'longitude'=>'-75.198400'],										
					['id'=>'109', 'latitude'=>'39.975056', 'longitude'=>'-75.195203'],										
					['id'=>'110', 'latitude'=>'39.975202', 'longitude'=>'-75.194074'],										
					['id'=>'111', 'latitude'=>'39.975500', 'longitude'=>'-75.191229'],										
					['id'=>'112', 'latitude'=>'39.975434', 'longitude'=>'-75.190628'],										
					['id'=>'113', 'latitude'=>'39.975542', 'longitude'=>'-75.190492'],										
					['id'=>'114', 'latitude'=>'39.975641', 'longitude'=>'-75.190428'],										
					['id'=>'115', 'latitude'=>'39.975713', 'longitude'=>'-75.190264'],										
					['id'=>'116', 'latitude'=>'39.975828', 'longitude'=>'-75.189906'],										
					['id'=>'117', 'latitude'=>'39.975909', 'longitude'=>'-75.189758'],										
					['id'=>'118', 'latitude'=>'39.976037', 'longitude'=>'-75.189689'],										
					['id'=>'119', 'latitude'=>'39.976124', 'longitude'=>'-75.189692'],										
					['id'=>'120', 'latitude'=>'39.976240', 'longitude'=>'-75.189774'],										
					['id'=>'121', 'latitude'=>'39.976311', 'longitude'=>'-75.189937'],										
					['id'=>'122', 'latitude'=>'39.976345', 'longitude'=>'-75.190127'],										
					['id'=>'123', 'latitude'=>'39.976343', 'longitude'=>'-75.190519'],										
					['id'=>'124', 'latitude'=>'39.976272', 'longitude'=>'-75.190841'],										
					['id'=>'125', 'latitude'=>'39.976012', 'longitude'=>'-75.191336'],										
					['id'=>'126', 'latitude'=>'39.975665', 'longitude'=>'-75.191916'],										
					['id'=>'127', 'latitude'=>'39.973765', 'longitude'=>'-75.190620'],										
					['id'=>'128', 'latitude'=>'39.972373', 'longitude'=>'-75.189953'],										
					['id'=>'129', 'latitude'=>'39.972011', 'longitude'=>'-75.189915'],										
					['id'=>'130', 'latitude'=>'39.971444', 'longitude'=>'-75.190059'],										
					['id'=>'131', 'latitude'=>'39.970839', 'longitude'=>'-75.190037'],										
					['id'=>'132', 'latitude'=>'39.970651', 'longitude'=>'-75.189976'],										
					['id'=>'133', 'latitude'=>'39.970315', 'longitude'=>'-75.189704'],										
					['id'=>'134', 'latitude'=>'39.970070', 'longitude'=>'-75.189275'],										
					['id'=>'135', 'latitude'=>'39.969962', 'longitude'=>'-75.188845'],										
					['id'=>'136', 'latitude'=>'39.969515', 'longitude'=>'-75.185790'],										
					['id'=>'137', 'latitude'=>'39.969280', 'longitude'=>'-75.184673'],										
					['id'=>'138', 'latitude'=>'39.968706', 'longitude'=>'-75.183283'],										
					['id'=>'139', 'latitude'=>'39.968108', 'longitude'=>'-75.182306'],										
					['id'=>'140', 'latitude'=>'39.967540', 'longitude'=>'-75.181574'],										
					['id'=>'141', 'latitude'=>'39.967419', 'longitude'=>'-75.181215'],										
					['id'=>'142', 'latitude'=>'39.967342', 'longitude'=>'-75.180842'],										
					['id'=>'143', 'latitude'=>'39.967147', 'longitude'=>'-75.180349'],										
					['id'=>'144', 'latitude'=>'39.967000', 'longitude'=>'-75.180084'],										
					['id'=>'145', 'latitude'=>'39.966805', 'longitude'=>'-75.179821'],										
					['id'=>'146', 'latitude'=>'39.966210', 'longitude'=>'-75.179332'],										
					['id'=>'147', 'latitude'=>'39.966548', 'longitude'=>'-75.178923'],										
					['id'=>'148', 'latitude'=>'39.964367', 'longitude'=>'-75.176401'],										
					['id'=>'149', 'latitude'=>'39.964558', 'longitude'=>'-75.177980'],										
					['id'=>'150', 'latitude'=>'39.964773', 'longitude'=>'-75.178281'],										
					['id'=>'151', 'latitude'=>'39.964884', 'longitude'=>'-75.178595'],										
					['id'=>'152', 'latitude'=>'39.964829', 'longitude'=>'-75.178977'],										
					['id'=>'153', 'latitude'=>'39.964717', 'longitude'=>'-75.179206'],					
				];
		}
		return $data;
	}


	public function get_upload_mp3_directory($file)
	{
		// Get current year and month
		$year = date('Y');
		$month = date('m');
		
		// Define the base upload directory
		$baseDirectory = public_path('uploads');
		
		// Construct the full directory path
		$uploadDirectory = $baseDirectory . DIRECTORY_SEPARATOR . $year . DIRECTORY_SEPARATOR . $month;

		// Ensure the directory exists
		if (!File::exists($uploadDirectory)) {
			File::makeDirectory($uploadDirectory, 0755, true);
		}

		return $uploadDirectory;
	}
}