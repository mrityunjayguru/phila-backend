<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use DB,Validator,Auth;
use App\Models\Stop;
use App\Models\Distance;

class DistanceController extends CommonController
{
    public function distance($stop = null){
		$distance = ['1','2','3','4','5','6','7','8','9','10','11','12','17','18','19','19','20','21','22','23','24','25','26','27','28'];
	  
		$stop_distance = [
			'1'=>'0',
			'2'=>'1.1373033422089',
			'3'=>'0.40594237445058',
			'4'=>'0.40594237445058',
			'5'=>'0.44691589311549',
			'6'=>'0.47241385072757',
			'7'=>'0.273060255671',
			'8'=>'0.34031323019348',
			'9'=>'0.48531391625396',
			'10'=>'0.67399176798792',
			'11'=>'0.45447153061292',
			'12'=>'0.3125442028643',
			'17'=>'0.99191379226295',
			'18'=>'0.47935514685389',
			'19'=>'0.18164215760414',
			'20'=>'0.95287394119643',
			'21'=>'0.83911660157373',
			'22'=>'0.54803799351261',
			'23'=>'0.58695450394313',
			'24'=>'0.33719297427547',
			'25'=>'0.32459245202848',
			'26'=>'0.30324666090083',
			'27'=>'0.46327908150264',
			'28'=>'0.59348557913654',
		];
	  
		$data = [];
		foreach($distance as $row){
				
				if($stop == 1){
					if($row == 1) { $data['1:'.$row] = '0'; }
					if($row == 2) { $data['1:'.$row] = $stop_distance[1] + $stop_distance[2]; }
					if($row == 3) { $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3]; }
					if($row == 4) { $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4]; }
					if($row == 5) { $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5]; }
					if($row == 6) { $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6]; }
					if($row == 7) { $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7]; }
					if($row == 8) { $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8]; }
					if($row == 9) { $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9]; }
					if($row == 10){ $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10]; }
					if($row == 11){ $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11]; }
					if($row == 12){ $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12]; }
					if($row == 17){ $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 18){ $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 19){ $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 20){ $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 21){ $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 22){ $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 23){ $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 24){ $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 25){ $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['1:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 2){
					if($row == 1) { $data['2:'.$row] = $stop_distance[1] + $stop_distance[2]; }
					if($row == 2) { $data['2:'.$row] = '0'; }
					if($row == 3) { $data['2:'.$row] = $stop_distance[3]; }
					if($row == 4) { $data['2:'.$row] = $stop_distance[3] + $stop_distance[4]; }
					if($row == 5) { $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5]; }
					if($row == 6) { $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6]; }
					if($row == 7) { $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7]; }
					if($row == 8) { $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8]; }
					if($row == 9) { $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9]; }
					if($row == 10){ $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10]; }
					if($row == 11){ $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11]; }
					if($row == 12){ $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12]; }
					if($row == 17){ $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 18){ $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 19){ $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 20){ $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 21){ $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 22){ $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 23){ $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 24){ $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 25){ $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['2:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 3){
					if($row == 1) { $data['3:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3]; }
					if($row == 2) { $data['3:'.$row] = $stop_distance[3]; }
					if($row == 3) { $data['3:'.$row] = '0'; }
					if($row == 4) { $data['3:'.$row] = $stop_distance[4]; }
					if($row == 5) { $data['3:'.$row] = $stop_distance[4] + $stop_distance[5]; }
					if($row == 6) { $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6]; }
					if($row == 7) { $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7]; }
					if($row == 8) { $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8]; }
					if($row == 9) { $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9]; }
					if($row == 10){ $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10]; }
					if($row == 11){ $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11]; }
					if($row == 12){ $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12]; }
					if($row == 17){ $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 18){ $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 19){ $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 20){ $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 21){ $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 22){ $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 23){ $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 24){ $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 25){ $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['3:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 4){
					if($row == 1) { $data['4:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4]; }
					if($row == 2) { $data['4:'.$row] = $stop_distance[3] + $stop_distance[4]; }
					if($row == 3) { $data['4:'.$row] = $stop_distance[4]; }
					if($row == 4) { $data['4:'.$row] = '0'; }
					if($row == 5) { $data['4:'.$row] = $stop_distance[5]; }
					if($row == 6) { $data['4:'.$row] = $stop_distance[5] + $stop_distance[6]; }
					if($row == 7) { $data['4:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7]; }
					if($row == 8) { $data['4:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8]; }
					if($row == 9) { $data['4:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9]; }
					if($row == 10){ $data['4:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10]; }
					if($row == 11){ $data['4:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11]; }
					if($row == 12){ $data['4:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12]; }
					if($row == 17){ $data['4:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 18){ $data['4:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 19){ $data['4:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 20){ $data['4:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 21){ $data['4:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 22){ $data['4:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 23){ $data['4:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 24){ $data['4:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 25){ $data['4:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['4:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['4:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['4:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 5){
					if($row == 1) { $data['5:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4]+ $stop_distance[5]; }
					if($row == 2) { $data['5:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5]; }
					if($row == 3) { $data['5:'.$row] = $stop_distance[4] + $stop_distance[5]; }
					if($row == 4) { $data['5:'.$row] = $stop_distance[5]; }
					if($row == 5) { $data['5:'.$row] = '0'; }
					if($row == 6) { $data['5:'.$row] = $stop_distance[6]; }
					if($row == 7) { $data['5:'.$row] = $stop_distance[6] + $stop_distance[7]; }
					if($row == 8) { $data['5:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8]; }
					if($row == 9) { $data['5:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9]; }
					if($row == 10){ $data['5:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10]; }
					if($row == 11){ $data['5:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11]; }
					if($row == 12){ $data['5:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12]; }
					if($row == 17){ $data['5:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 18){ $data['5:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 19){ $data['5:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 20){ $data['5:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 21){ $data['5:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 22){ $data['5:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 23){ $data['5:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 24){ $data['5:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 25){ $data['5:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['5:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['5:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['5:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 6){
					if($row == 1) { $data['6:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6]; }
					if($row == 2) { $data['6:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6]; }
					if($row == 3) { $data['6:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6]; }
					if($row == 4) { $data['6:'.$row] = $stop_distance[5] + $stop_distance[6]; }
					if($row == 5) { $data['6:'.$row] = $stop_distance[6]; }
					if($row == 6) { $data['6:'.$row] = '0'; }
					if($row == 7) { $data['6:'.$row] = $stop_distance[7]; }
					if($row == 8) { $data['6:'.$row] = $stop_distance[7] + $stop_distance[8]; }
					if($row == 9) { $data['6:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9]; }
					if($row == 10){ $data['6:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10]; }
					if($row == 11){ $data['6:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11]; }
					if($row == 12){ $data['6:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12]; }
					if($row == 17){ $data['6:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 18){ $data['6:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 19){ $data['6:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 20){ $data['6:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 21){ $data['6:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 22){ $data['6:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 23){ $data['6:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 24){ $data['6:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 25){ $data['6:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['6:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['6:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['6:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 7){
					if($row == 1) { $data['7:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7]; }
					if($row == 2) { $data['7:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7]; }
					if($row == 3) { $data['7:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7]; }
					if($row == 4) { $data['7:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7]; }
					if($row == 5) { $data['7:'.$row] = $stop_distance[6] + $stop_distance[7]; }
					if($row == 6) { $data['7:'.$row] = $stop_distance[7]; }
					if($row == 7) { $data['7:'.$row] = '0'; }
					if($row == 8) { $data['7:'.$row] = $stop_distance[8]; }
					if($row == 9) { $data['7:'.$row] = $stop_distance[8] + $stop_distance[9]; }
					if($row == 10){ $data['7:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10]; }
					if($row == 11){ $data['7:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11]; }
					if($row == 12){ $data['7:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12]; }
					if($row == 17){ $data['7:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 18){ $data['7:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 19){ $data['7:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 20){ $data['7:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 21){ $data['7:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 22){ $data['7:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 23){ $data['7:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 24){ $data['7:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 25){ $data['7:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['7:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['7:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['7:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 8){
					if($row == 1) { $data['8:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8]; }
					if($row == 2) { $data['8:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8]; }
					if($row == 3) { $data['8:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8]; }
					if($row == 4) { $data['8:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8]; }
					if($row == 5) { $data['8:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8]; }
					if($row == 6) { $data['8:'.$row] = $stop_distance[7] + $stop_distance[8]; }
					if($row == 7) { $data['8:'.$row] = $stop_distance[8]; }
					if($row == 8) { $data['8:'.$row] = '0'; }
					if($row == 9) { $data['8:'.$row] = $stop_distance[9]; }
					if($row == 10){ $data['8:'.$row] = $stop_distance[9] + $stop_distance[10]; }
					if($row == 11){ $data['8:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11]; }
					if($row == 12){ $data['8:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12]; }
					if($row == 17){ $data['8:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 18){ $data['8:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 19){ $data['8:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 20){ $data['8:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 21){ $data['8:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 22){ $data['8:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 23){ $data['8:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 24){ $data['8:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 25){ $data['8:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['8:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['8:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['8:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 9){
					if($row == 1) { $data['9:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9]; }
					if($row == 2) { $data['9:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9]; }
					if($row == 3) { $data['9:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9]; }
					if($row == 4) { $data['9:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9]; }
					if($row == 5) { $data['9:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9]; }
					if($row == 6) { $data['9:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9]; }
					if($row == 7) { $data['9:'.$row] = $stop_distance[8] + $stop_distance[9]; }
					if($row == 8) { $data['9:'.$row] = $stop_distance[9]; }
					if($row == 9) { $data['9:'.$row] = '0'; }
					if($row == 10){ $data['9:'.$row] = $stop_distance[10]; }
					if($row == 11){ $data['9:'.$row] = $stop_distance[10] + $stop_distance[11]; }
					if($row == 12){ $data['9:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12]; }
					if($row == 17){ $data['9:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 18){ $data['9:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 19){ $data['9:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 20){ $data['9:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 21){ $data['9:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 22){ $data['9:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 23){ $data['9:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 24){ $data['9:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 25){ $data['9:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['9:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['9:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['9:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 10){
					if($row == 1) { $data['10:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10]; }
					if($row == 2) { $data['10:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10]; }
					if($row == 3) { $data['10:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10]; }
					if($row == 4) { $data['10:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10]; }
					if($row == 5) { $data['10:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10]; }
					if($row == 6) { $data['10:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10]; }
					if($row == 7) { $data['10:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10]; }
					if($row == 8) { $data['10:'.$row] = $stop_distance[9] + $stop_distance[10]; }
					if($row == 9) { $data['10:'.$row] = $stop_distance[10]; }
					if($row == 10){ $data['10:'.$row] = '0'; }
					if($row == 11){ $data['10:'.$row] = $stop_distance[11]; }
					if($row == 12){ $data['10:'.$row] = $stop_distance[11] + $stop_distance[12]; }
					if($row == 17){ $data['10:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 18){ $data['10:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 19){ $data['10:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 20){ $data['10:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 21){ $data['10:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 22){ $data['10:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 23){ $data['10:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 24){ $data['10:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 25){ $data['10:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['10:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['10:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['10:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 11){
					if($row == 1) { $data['11:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11]; }
					if($row == 2) { $data['11:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11]; }
					if($row == 3) { $data['11:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11]; }
					if($row == 4) { $data['11:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11]; }
					if($row == 5) { $data['11:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11]; }
					if($row == 6) { $data['11:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11]; }
					if($row == 7) { $data['11:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11]; }
					if($row == 8) { $data['11:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11]; }
					if($row == 9) { $data['11:'.$row] = $stop_distance[10] + $stop_distance[11]; }
					if($row == 10){ $data['11:'.$row] = $stop_distance[11]; }
					if($row == 11){ $data['11:'.$row] = '0'; }
					if($row == 12){ $data['11:'.$row] = $stop_distance[12]; }
					if($row == 17){ $data['11:'.$row] = $stop_distance[12] + $stop_distance[17]; }
					if($row == 18){ $data['11:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 19){ $data['11:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 20){ $data['11:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 21){ $data['11:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 22){ $data['11:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 23){ $data['11:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 24){ $data['11:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 25){ $data['11:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['11:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['11:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['11:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 12){
					if($row == 1) { $data['12:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12]; }
					if($row == 2) { $data['12:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12]; }
					if($row == 3) { $data['12:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12]; }
					if($row == 4) { $data['12:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12]; }
					if($row == 5) { $data['12:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12]; }
					if($row == 6) { $data['12:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12]; }
					if($row == 7) { $data['12:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12]; }
					if($row == 8) { $data['12:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12]; }
					if($row == 9) { $data['12:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12]; }
					if($row == 10){ $data['12:'.$row] = $stop_distance[11] + $stop_distance[12]; }
					if($row == 11){ $data['12:'.$row] = $stop_distance[12]; }
					if($row == 12){ $data['12:'.$row] = '0'; }
					if($row == 17){ $data['12:'.$row] = $stop_distance[17]; }
					if($row == 18){ $data['12:'.$row] = $stop_distance[17] + $stop_distance[18]; }
					if($row == 19){ $data['12:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 20){ $data['12:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 21){ $data['12:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 22){ $data['12:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 23){ $data['12:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 24){ $data['12:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 25){ $data['12:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['12:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['12:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['12:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 17){
					if($row == 1) { $data['17:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 2) { $data['17:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 3) { $data['17:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 4) { $data['17:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 5) { $data['17:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 6) { $data['17:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 7) { $data['17:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 8) { $data['17:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 9) { $data['17:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 10){ $data['17:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17]; }
					if($row == 11){ $data['17:'.$row] = $stop_distance[12] + $stop_distance[17]; }
					if($row == 12){ $data['17:'.$row] = $stop_distance[17]; }
					if($row == 17){ $data['17:'.$row] = '0'; }
					if($row == 18){ $data['17:'.$row] = $stop_distance[18]; }
					if($row == 19){ $data['17:'.$row] = $stop_distance[18] + $stop_distance[19]; }
					if($row == 20){ $data['17:'.$row] = $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 21){ $data['17:'.$row] = $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 22){ $data['17:'.$row] = $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 23){ $data['17:'.$row] = $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 24){ $data['17:'.$row] = $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 25){ $data['17:'.$row] = $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['17:'.$row] = $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['17:'.$row] = $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['17:'.$row] = $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 18){
					if($row == 1) { $data['18:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 2) { $data['18:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 3) { $data['18:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 4) { $data['18:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 5) { $data['18:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 6) { $data['18:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 7) { $data['18:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 8) { $data['18:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 9) { $data['18:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 10){ $data['18:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 11){ $data['18:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18]; }
					if($row == 12){ $data['18:'.$row] = $stop_distance[17] + $stop_distance[18]; }
					if($row == 17){ $data['18:'.$row] = $stop_distance[18]; }
					if($row == 18){ $data['18:'.$row] = '0'; }
					if($row == 19){ $data['18:'.$row] = $stop_distance[19]; }
					if($row == 20){ $data['18:'.$row] = $stop_distance[19] + $stop_distance[20]; }
					if($row == 21){ $data['18:'.$row] = $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 22){ $data['18:'.$row] = $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 23){ $data['18:'.$row] = $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 24){ $data['18:'.$row] = $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 25){ $data['18:'.$row] = $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['18:'.$row] = $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['18:'.$row] = $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['18:'.$row] = $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 19){
					if($row == 1) { $data['19:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 2) { $data['19:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 3) { $data['19:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 4) { $data['19:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 5) { $data['19:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 6) { $data['19:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 7) { $data['19:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 8) { $data['19:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 9) { $data['19:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 10){ $data['19:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 11){ $data['19:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 12){ $data['19:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19]; }
					if($row == 17){ $data['19:'.$row] = $stop_distance[18] + $stop_distance[19]; }
					if($row == 18){ $data['19:'.$row] = $stop_distance[19]; }
					if($row == 19){ $data['19:'.$row] = '0'; }
					if($row == 20){ $data['19:'.$row] = $stop_distance[20]; }
					if($row == 21){ $data['19:'.$row] = $stop_distance[20] + $stop_distance[21]; }
					if($row == 22){ $data['19:'.$row] = $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 23){ $data['19:'.$row] = $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 24){ $data['19:'.$row] = $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 25){ $data['19:'.$row] = $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['19:'.$row] = $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['19:'.$row] = $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['19:'.$row] = $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 20){
					if($row == 1) { $data['20:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 2) { $data['20:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 3) { $data['20:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 4) { $data['20:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 5) { $data['20:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 6) { $data['20:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 7) { $data['20:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 8) { $data['20:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 9) { $data['20:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 10){ $data['20:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 11){ $data['20:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 12){ $data['20:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 17){ $data['20:'.$row] = $stop_distance[18] + $stop_distance[19] + $stop_distance[20]; }
					if($row == 18){ $data['20:'.$row] = $stop_distance[19] + $stop_distance[20]; }
					if($row == 19){ $data['20:'.$row] = $stop_distance[20]; }
					if($row == 20){ $data['20:'.$row] = '0'; }
					if($row == 21){ $data['20:'.$row] = $stop_distance[21]; }
					if($row == 22){ $data['20:'.$row] = $stop_distance[21] + $stop_distance[22]; }
					if($row == 23){ $data['20:'.$row] = $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 24){ $data['20:'.$row] = $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 25){ $data['20:'.$row] = $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['20:'.$row] = $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['20:'.$row] = $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['20:'.$row] = $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 21){
					if($row == 1) { $data['21:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 2) { $data['21:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 3) { $data['21:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 4) { $data['21:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 5) { $data['21:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 6) { $data['21:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 7) { $data['21:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 8) { $data['21:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 9) { $data['21:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 10){ $data['21:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 11){ $data['21:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 12){ $data['21:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 17){ $data['21:'.$row] = $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 18){ $data['21:'.$row] = $stop_distance[19] + $stop_distance[20] + $stop_distance[21]; }
					if($row == 19){ $data['21:'.$row] = $stop_distance[20] + $stop_distance[21]; }
					if($row == 20){ $data['21:'.$row] = $stop_distance[21]; }
					if($row == 21){ $data['21:'.$row] = '0'; }
					if($row == 22){ $data['21:'.$row] = $stop_distance[22]; }
					if($row == 23){ $data['21:'.$row] = $stop_distance[22] + $stop_distance[23]; }
					if($row == 24){ $data['21:'.$row] = $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 25){ $data['21:'.$row] = $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['21:'.$row] = $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['21:'.$row] = $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['21:'.$row] = $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 22){
					if($row == 1) { $data['22:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 2) { $data['22:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 3) { $data['22:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 4) { $data['22:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 5) { $data['22:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 6) { $data['22:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 7) { $data['22:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 8) { $data['22:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 9) { $data['22:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 10){ $data['22:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 11){ $data['22:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 12){ $data['22:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 17){ $data['22:'.$row] = $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 18){ $data['22:'.$row] = $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 19){ $data['22:'.$row] = $stop_distance[20] + $stop_distance[21] + $stop_distance[22]; }
					if($row == 20){ $data['22:'.$row] = $stop_distance[21] + $stop_distance[22]; }
					if($row == 21){ $data['22:'.$row] = $stop_distance[22]; }
					if($row == 22){ $data['22:'.$row] = '0'; }
					if($row == 23){ $data['22:'.$row] = $stop_distance[23]; }
					if($row == 24){ $data['22:'.$row] = $stop_distance[23] + $stop_distance[24]; }
					if($row == 25){ $data['22:'.$row] = $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['22:'.$row] = $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['22:'.$row] = $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['22:'.$row] = $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				if($stop == 23){
					if($row == 1) { $data['23:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 2) { $data['23:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 3) { $data['23:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 4) { $data['23:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 5) { $data['23:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 6) { $data['23:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 7) { $data['23:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 8) { $data['23:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 9) { $data['23:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 10){ $data['23:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 11){ $data['23:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 12){ $data['23:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 17){ $data['23:'.$row] = $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 18){ $data['23:'.$row] = $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 19){ $data['23:'.$row] = $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 20){ $data['23:'.$row] = $stop_distance[21] + $stop_distance[22] + $stop_distance[23]; }
					if($row == 21){ $data['23:'.$row] = $stop_distance[22] + $stop_distance[23]; }
					if($row == 22){ $data['23:'.$row] = $stop_distance[23]; }
					if($row == 23){ $data['23:'.$row] = '0'; }
					if($row == 24){ $data['23:'.$row] = $stop_distance[24]; }
					if($row == 25){ $data['23:'.$row] = $stop_distance[24] + $stop_distance[25]; }
					if($row == 26){ $data['23:'.$row] = $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['23:'.$row] = $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['23:'.$row] = $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 24){
					if($row == 1) { $data['24:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 2) { $data['24:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 3) { $data['24:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 4) { $data['24:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 5) { $data['24:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 6) { $data['24:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 7) { $data['24:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 8) { $data['24:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 9) { $data['24:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 10){ $data['24:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 11){ $data['24:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 12){ $data['24:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 17){ $data['24:'.$row] = $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 18){ $data['24:'.$row] = $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 19){ $data['24:'.$row] = $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 20){ $data['24:'.$row] = $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 21){ $data['24:'.$row] = $stop_distance[22] + $stop_distance[23] + $stop_distance[24]; }
					if($row == 22){ $data['24:'.$row] = $stop_distance[23] + $stop_distance[24]; }
					if($row == 23){ $data['24:'.$row] = $stop_distance[24]; }
					if($row == 24){ $data['24:'.$row] = '0'; }
					if($row == 25){ $data['24:'.$row] = $stop_distance[25]; }
					if($row == 26){ $data['24:'.$row] = $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['24:'.$row] = $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['24:'.$row] = $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 25){
					if($row == 1) { $data['25:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 2) { $data['25:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 3) { $data['25:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 4) { $data['25:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 5) { $data['25:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 6) { $data['25:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 7) { $data['25:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 8) { $data['25:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 9) { $data['25:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 10){ $data['25:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 11){ $data['25:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 12){ $data['25:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 17){ $data['25:'.$row] = $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 18){ $data['25:'.$row] = $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 19){ $data['25:'.$row] = $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 20){ $data['25:'.$row] = $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 21){ $data['25:'.$row] = $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 22){ $data['25:'.$row] = $stop_distance[23] + $stop_distance[24] + $stop_distance[25]; }
					if($row == 23){ $data['25:'.$row] = $stop_distance[24] + $stop_distance[25]; }
					if($row == 24){ $data['25:'.$row] = $stop_distance[25]; }
					if($row == 25){ $data['25:'.$row] = '0'; }
					if($row == 26){ $data['25:'.$row] = $stop_distance[25] + $stop_distance[26]; }
					if($row == 27){ $data['25:'.$row] = $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 28){ $data['25:'.$row] = $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 26){
					if($row == 1) { $data['26:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 2) { $data['26:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 3) { $data['26:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 4) { $data['26:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 5) { $data['26:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 6) { $data['26:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 7) { $data['26:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 8) { $data['26:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 9) { $data['26:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 10){ $data['26:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 11){ $data['26:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 12){ $data['26:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 17){ $data['26:'.$row] = $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 18){ $data['26:'.$row] = $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 19){ $data['26:'.$row] = $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 20){ $data['26:'.$row] = $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 21){ $data['26:'.$row] = $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 22){ $data['26:'.$row] = $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 23){ $data['26:'.$row] = $stop_distance[24] + $stop_distance[25] + $stop_distance[26]; }
					if($row == 24){ $data['26:'.$row] = $stop_distance[25] + $stop_distance[26]; }
					if($row == 25){ $data['26:'.$row] = $stop_distance[26]; }
					if($row == 26){ $data['26:'.$row] = '0'; }
					if($row == 27){ $data['26:'.$row] = $stop_distance[27]; }
					if($row == 28){ $data['26:'.$row] = $stop_distance[27] + $stop_distance[28]; }
				}
				
				if($stop == 27){
					if($row == 1) { $data['27:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 2) { $data['27:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 3) { $data['27:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 4) { $data['27:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 5) { $data['27:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 6) { $data['27:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 7) { $data['27:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 8) { $data['27:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 9) { $data['27:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 10){ $data['27:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 11){ $data['27:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 12){ $data['27:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 17){ $data['27:'.$row] = $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 18){ $data['27:'.$row] = $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 19){ $data['27:'.$row] = $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 20){ $data['27:'.$row] = $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 21){ $data['27:'.$row] = $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 22){ $data['27:'.$row] = $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 23){ $data['27:'.$row] = $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 24){ $data['27:'.$row] = $stop_distance[25] + $stop_distance[26] + $stop_distance[27]; }
					if($row == 25){ $data['27:'.$row] = $stop_distance[26] + $stop_distance[27]; }
					if($row == 26){ $data['27:'.$row] = $stop_distance[27]; }
					if($row == 27){ $data['27:'.$row] = '0'; }
					if($row == 28){ $data['27:'.$row] = $stop_distance[28]; }
				}
				
				if($stop == 28){
					if($row == 1) { $data['28:'.$row] = $stop_distance[1] + $stop_distance[2] + $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 2) { $data['28:'.$row] = $stop_distance[3] + $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 3) { $data['28:'.$row] = $stop_distance[4] + $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 4) { $data['28:'.$row] = $stop_distance[5] + $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 5) { $data['28:'.$row] = $stop_distance[6] + $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 6) { $data['28:'.$row] = $stop_distance[7] + $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 7) { $data['28:'.$row] = $stop_distance[8] + $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 8) { $data['28:'.$row] = $stop_distance[9] + $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 9) { $data['28:'.$row] = $stop_distance[10] + $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 10){ $data['28:'.$row] = $stop_distance[11] + $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 11){ $data['28:'.$row] = $stop_distance[12] + $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 12){ $data['28:'.$row] = $stop_distance[17] + $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 17){ $data['28:'.$row] = $stop_distance[18] + $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 18){ $data['28:'.$row] = $stop_distance[19] + $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 19){ $data['28:'.$row] = $stop_distance[20] + $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 20){ $data['28:'.$row] = $stop_distance[21] + $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 21){ $data['28:'.$row] = $stop_distance[22] + $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 22){ $data['28:'.$row] = $stop_distance[23] + $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 23){ $data['28:'.$row] = $stop_distance[24] + $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 24){ $data['28:'.$row] = $stop_distance[25] + $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 25){ $data['28:'.$row] = $stop_distance[26] + $stop_distance[27] + $stop_distance[28]; }
					if($row == 26){ $data['28:'.$row] = $stop_distance[27] + $stop_distance[28]; }
					if($row == 27){ $data['28:'.$row] = $stop_distance[28]; }
					if($row == 28){ $data['28:'.$row] = '0'; }
				}
		}
		
		foreach($data as $key=> $list){
			//Distance::create(['stop'=>$key, 'distance_in_km'=>$list]);
		}
		echo'<pre>'; print_r($data);
	}
	
	public function calculate($stop1 = null, $stop2 = null){
		try{
			$query = Distance::where(['stop'=> $stop1.':'.$stop2])->first();
			
			if(empty($query)){
				echo 'N/A'; exit;
			}
			
			echo $query->distance_in_km;
			
		}catch (\Exception $e) {
			return $this->sendError('', $e->getMessage());
		}
	}
}