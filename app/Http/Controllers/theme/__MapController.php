<?php
namespace App\Http\Controllers\theme;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Auth,App;

use App\Models\Stop;
use App\Models\Bus;
use App\Models\Place;
use App\Models\Ticket;

class MapController extends CommonController
{
    /**
	*
	* @return void
	*/
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
	* Show the product list page.
	*/
	public function index(){
		try {
			$page       = 'mapPage';
			$page_title = 'Map View';
			
			return view('theme.trip', compact('page','page_title'));
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	/**
	* Show ticket validate page.
	*/
	public function ticketValidate(){
		try {
			$page       = 'ticketValidate';
			$page_title = 'Ticket Validate';
			
			return view('theme.ticket-validate', compact('page','page_title'));
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	/**
	* Track the Bus.
	*/
	public function track($ticket_number = ''){
		try {
			$page       = 'trackBus';
			$page_title = 'Track Bus';
			
			// Validate Ticket
			$data = Ticket::where(['status'=>'active'])->where('ticket_number', $ticket_number)->first();
			if(empty($data)){
				return redirect()->route('ticketValidate');
			}
			
			if(date("Y-m-d") > $data->end_date){
				return redirect()->route('ticketValidate');
			}
			
			return view('theme.track', compact('page','page_title'));
		} catch (Exception $e) {
			return redirect()->route('ticketValidate');
		}
	}
	
	/**
	* Track the Bus Backend.
	*/
	public function trackBackend(){
		
		$user = Auth()->user();
 		if(empty($user)){
			return redirect()->route('login');
		}
		
		try {
			$page       = 'trackBackend';
			$page_title = 'Track Vehicles';
			
			return view('theme.track-vehicles', compact('page','page_title'));
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	/**
	* Display a listing of the resource.
	* @return \Illuminate\Http\Response
	*/
	public function ajax_roots(Request $request){
		
		try {
			if($request->type == 'tour'){
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
					['id'=>'178', 'latitude'=>'39.946924', 'longitude'=>'-75.142753'],
					['id'=>'179', 'latitude'=>'39.948182', 'longitude'=>'-75.142454'],
					['id'=>'180', 'latitude'=>'39.947968', 'longitude'=>'-75.140720'],
					['id'=>'181', 'latitude'=>'39.947975', 'longitude'=>'-75.140601'],
					['id'=>'182', 'latitude'=>'39.948024', 'longitude'=>'-75.140519'],
					['id'=>'183', 'latitude'=>'39.948210', 'longitude'=>'-75.140410'],
					['id'=>'184', 'latitude'=>'39.949683', 'longitude'=>'-75.140071'],
					['id'=>'185', 'latitude'=>'39.949775', 'longitude'=>'-75.140789'],
					['id'=>'186', 'latitude'=>'39.949934', 'longitude'=>'-75.141994'],
					['id'=>'187', 'latitude'=>'39.949981', 'longitude'=>'-75.142348'],
					['id'=>'188', 'latitude'=>'39.949995', 'longitude'=>'-75.142713'],
					['id'=>'189', 'latitude'=>'39.949969', 'longitude'=>'-75.143624'],
					['id'=>'190', 'latitude'=>'39.950014', 'longitude'=>'-75.144180'],
					['id'=>'191', 'latitude'=>'39.950191', 'longitude'=>'-75.145569'],
					['id'=>'192', 'latitude'=>'39.950386', 'longitude'=>'-75.147160'],
					['id'=>'193', 'latitude'=>'39.950596', 'longitude'=>'-75.148790'],
					['id'=>'194', 'latitude'=>'39.950763', 'longitude'=>'-75.150363'],
					['id'=>'195', 'latitude'=>'39.949239', 'longitude'=>'-75.150774'],
					['id'=>'196', 'latitude'=>'39.949053', 'longitude'=>'-75.149234'],
					['id'=>'197', 'latitude'=>'39.949951', 'longitude'=>'-75.149060'],
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
					['id'=>'10', 'latitude'=>'39.964531', 'longitude'=>'-75.182361'],
					['id'=>'11', 'latitude'=>'39.964514', 'longitude'=>'-75.182707'],
					['id'=>'12', 'latitude'=>'39.964455', 'longitude'=>'-75.183161'],
					['id'=>'13', 'latitude'=>'39.964330', 'longitude'=>'-75.183701'],
					['id'=>'14', 'latitude'=>'39.964221', 'longitude'=>'-75.184050'],
					['id'=>'15', 'latitude'=>'39.963904', 'longitude'=>'-75.184851'],
					['id'=>'16', 'latitude'=>'39.963783', 'longitude'=>'-75.185207'],
					['id'=>'17', 'latitude'=>'39.963652', 'longitude'=>'-75.185669'],
					['id'=>'18', 'latitude'=>'39.963493', 'longitude'=>'-75.186523'],
					['id'=>'19', 'latitude'=>'39.963099', 'longitude'=>'-75.189954'],
					['id'=>'20', 'latitude'=>'39.966478', 'longitude'=>'-75.190589'],
					['id'=>'21', 'latitude'=>'39.966916', 'longitude'=>'-75.192102'],
					['id'=>'22', 'latitude'=>'39.967964', 'longitude'=>'-75.192320'],
					['id'=>'23', 'latitude'=>'39.973794', 'longitude'=>'-75.194683'],
					['id'=>'24', 'latitude'=>'39.974181', 'longitude'=>'-75.194765'],
					['id'=>'25', 'latitude'=>'39.974987', 'longitude'=>'-75.194929'],
					['id'=>'26', 'latitude'=>'39.975368', 'longitude'=>'-75.194999'],
					['id'=>'27', 'latitude'=>'39.975506', 'longitude'=>'-75.195122'],
					['id'=>'28', 'latitude'=>'39.975613', 'longitude'=>'-75.195287'],
					['id'=>'29', 'latitude'=>'39.975717', 'longitude'=>'-75.195506'],
					['id'=>'30', 'latitude'=>'39.975758', 'longitude'=>'-75.195704'],
					['id'=>'31', 'latitude'=>'39.975802', 'longitude'=>'-75.196079'],
					['id'=>'32', 'latitude'=>'39.976077', 'longitude'=>'-75.197350'],
					['id'=>'33', 'latitude'=>'39.976220', 'longitude'=>'-75.199178'],
					['id'=>'34', 'latitude'=>'39.976209', 'longitude'=>'-75.199538'],
					['id'=>'35', 'latitude'=>'39.976142', 'longitude'=>'-75.199795'],
					['id'=>'36', 'latitude'=>'39.975988', 'longitude'=>'-75.200215'],
					['id'=>'37', 'latitude'=>'39.975941', 'longitude'=>'-75.200459'],
					['id'=>'38', 'latitude'=>'39.975946', 'longitude'=>'-75.200723'],
					['id'=>'39', 'latitude'=>'39.975980', 'longitude'=>'-75.200915'],
					['id'=>'40', 'latitude'=>'39.976266', 'longitude'=>'-75.201773'],
					['id'=>'41', 'latitude'=>'39.976307', 'longitude'=>'-75.202033'],
					['id'=>'42', 'latitude'=>'39.976308', 'longitude'=>'-75.202608'],
					['id'=>'43', 'latitude'=>'39.975810', 'longitude'=>'-75.204379'],
					['id'=>'44', 'latitude'=>'39.976470', 'longitude'=>'-75.204759'],
					['id'=>'45', 'latitude'=>'39.976901', 'longitude'=>'-75.205227'],
					['id'=>'46', 'latitude'=>'39.977005', 'longitude'=>'-75.205359'],
					['id'=>'47', 'latitude'=>'39.977044', 'longitude'=>'-75.205383'],
					['id'=>'48', 'latitude'=>'39.977118', 'longitude'=>'-75.205388'],
					['id'=>'49', 'latitude'=>'39.977186', 'longitude'=>'-75.205396'],
					['id'=>'50', 'latitude'=>'39.977266', 'longitude'=>'-75.205431'],
					['id'=>'51', 'latitude'=>'39.977317', 'longitude'=>'-75.205509'],
					['id'=>'52', 'latitude'=>'39.977350', 'longitude'=>'-75.205648'],
					['id'=>'53', 'latitude'=>'39.977337', 'longitude'=>'-75.205740'],
					['id'=>'54', 'latitude'=>'39.977315', 'longitude'=>'-75.205845'],
					['id'=>'55', 'latitude'=>'39.977343', 'longitude'=>'-75.206045'],
					['id'=>'56', 'latitude'=>'39.977395', 'longitude'=>'-75.206249'],
					['id'=>'57', 'latitude'=>'39.978814', 'longitude'=>'-75.210105'],
					['id'=>'58', 'latitude'=>'39.977590', 'longitude'=>'-75.210872'],
					['id'=>'59', 'latitude'=>'39.977291', 'longitude'=>'-75.209986'],
					['id'=>'60', 'latitude'=>'39.978443', 'longitude'=>'-75.209240'],
					['id'=>'61', 'latitude'=>'39.977392', 'longitude'=>'-75.206408'],
					['id'=>'62', 'latitude'=>'39.977331', 'longitude'=>'-75.206213'],
					['id'=>'63', 'latitude'=>'39.977222', 'longitude'=>'-75.205940'],
					['id'=>'64', 'latitude'=>'39.977191', 'longitude'=>'-75.205891'],
					['id'=>'65', 'latitude'=>'39.977106', 'longitude'=>'-75.205884'],
					['id'=>'66', 'latitude'=>'39.977046', 'longitude'=>'-75.205848'],
					['id'=>'67', 'latitude'=>'39.976994', 'longitude'=>'-75.205783'],
					['id'=>'68', 'latitude'=>'39.976959', 'longitude'=>'-75.205680'],
					['id'=>'69', 'latitude'=>'39.976961', 'longitude'=>'-75.205597'],
					['id'=>'70', 'latitude'=>'39.976963', 'longitude'=>'-75.205528'],
					['id'=>'71', 'latitude'=>'39.976883', 'longitude'=>'-75.205284'],
					['id'=>'72', 'latitude'=>'39.976449', 'longitude'=>'-75.204793'],
					['id'=>'73', 'latitude'=>'39.976341', 'longitude'=>'-75.204708'],
					['id'=>'74', 'latitude'=>'39.975753', 'longitude'=>'-75.204396'],
					['id'=>'75', 'latitude'=>'39.976229', 'longitude'=>'-75.202752'],
					['id'=>'76', 'latitude'=>'39.976260', 'longitude'=>'-75.202553'],
					['id'=>'77', 'latitude'=>'39.976268', 'longitude'=>'-75.202381'],
					['id'=>'78', 'latitude'=>'39.976266', 'longitude'=>'-75.202074'],
					['id'=>'79', 'latitude'=>'39.976228', 'longitude'=>'-75.201835'],
					['id'=>'80', 'latitude'=>'39.975934', 'longitude'=>'-75.200961'],
					['id'=>'81', 'latitude'=>'39.975885', 'longitude'=>'-75.200737'],
					['id'=>'82', 'latitude'=>'39.975917', 'longitude'=>'-75.200267'],
					['id'=>'83', 'latitude'=>'39.976137', 'longitude'=>'-75.199633'],
					['id'=>'84', 'latitude'=>'39.976200', 'longitude'=>'-75.199303'],
					['id'=>'85', 'latitude'=>'39.976181', 'longitude'=>'-75.198491'],
					['id'=>'86', 'latitude'=>'39.976052', 'longitude'=>'-75.197391'],
					['id'=>'87', 'latitude'=>'39.975819', 'longitude'=>'-75.196337'],
					['id'=>'88', 'latitude'=>'39.975739', 'longitude'=>'-75.196062'],
					['id'=>'89', 'latitude'=>'39.975612', 'longitude'=>'-75.195595'],
					['id'=>'90', 'latitude'=>'39.975479', 'longitude'=>'-75.195325'],
					['id'=>'91', 'latitude'=>'39.975292', 'longitude'=>'-75.195155'],
					['id'=>'92', 'latitude'=>'39.974698', 'longitude'=>'-75.195018'],
					['id'=>'93', 'latitude'=>'39.973901', 'longitude'=>'-75.194800'],
					['id'=>'94', 'latitude'=>'39.968050', 'longitude'=>'-75.192450'],
					['id'=>'95', 'latitude'=>'39.962933', 'longitude'=>'-75.191401'],
					['id'=>'96', 'latitude'=>'39.963526', 'longitude'=>'-75.186083'],
					['id'=>'97', 'latitude'=>'39.963735', 'longitude'=>'-75.185225'],
					['id'=>'98', 'latitude'=>'39.963803', 'longitude'=>'-75.185046'],
					['id'=>'99', 'latitude'=>'39.964373', 'longitude'=>'-75.183246'],
					['id'=>'100', 'latitude'=>'39.964432', 'longitude'=>'-75.182883'],
					['id'=>'101', 'latitude'=>'39.964461', 'longitude'=>'-75.182446'],
					['id'=>'102', 'latitude'=>'39.964479', 'longitude'=>'-75.182229'],
					['id'=>'103', 'latitude'=>'39.964635', 'longitude'=>'-75.182186'],
					['id'=>'104', 'latitude'=>'39.964721', 'longitude'=>'-75.182184'],
					['id'=>'105', 'latitude'=>'39.964846', 'longitude'=>'-75.182224'],
					['id'=>'106', 'latitude'=>'39.965186', 'longitude'=>'-75.182612'],
					['id'=>'107', 'latitude'=>'39.965286', 'longitude'=>'-75.182663'],
					['id'=>'108', 'latitude'=>'39.965513', 'longitude'=>'-75.182669'],
					['id'=>'110', 'latitude'=>'39.965707', 'longitude'=>'-75.182637'],
					['id'=>'111', 'latitude'=>'39.965852', 'longitude'=>'-75.182578'],
					['id'=>'112', 'latitude'=>'39.966004', 'longitude'=>'-75.182471'],
					['id'=>'113', 'latitude'=>'39.966255', 'longitude'=>'-75.182183'],
					['id'=>'114', 'latitude'=>'39.966686', 'longitude'=>'-75.181643'],
					['id'=>'115', 'latitude'=>'39.966774', 'longitude'=>'-75.181494'],
					['id'=>'116', 'latitude'=>'39.966828', 'longitude'=>'-75.181357'],
					['id'=>'117', 'latitude'=>'39.966864', 'longitude'=>'-75.181212'],
					['id'=>'118', 'latitude'=>'39.966883', 'longitude'=>'-75.181064'],
					['id'=>'119', 'latitude'=>'39.966884', 'longitude'=>'-75.180875'],
					['id'=>'120', 'latitude'=>'39.966867', 'longitude'=>'-75.180762'],
					['id'=>'121', 'latitude'=>'39.966824', 'longitude'=>'-75.180597'],
					['id'=>'122', 'latitude'=>'39.966509', 'longitude'=>'-75.180070'],
					['id'=>'123', 'latitude'=>'39.966244', 'longitude'=>'-75.179756'],
					['id'=>'124', 'latitude'=>'39.966181', 'longitude'=>'-75.179643'],
					['id'=>'125', 'latitude'=>'39.966162', 'longitude'=>'-75.179501'],
					['id'=>'126', 'latitude'=>'39.966170', 'longitude'=>'-75.179449'],
					['id'=>'127', 'latitude'=>'39.966196', 'longitude'=>'-75.179322'],
					['id'=>'128', 'latitude'=>'39.966089', 'longitude'=>'-75.179267'],
					['id'=>'129', 'latitude'=>'39.965114', 'longitude'=>'-75.178925'],
					['id'=>'130', 'latitude'=>'39.965062', 'longitude'=>'-75.178923'],
					['id'=>'131', 'latitude'=>'39.965001', 'longitude'=>'-75.178952'],
					['id'=>'132', 'latitude'=>'39.964874', 'longitude'=>'-75.179081'],
					['id'=>'133', 'latitude'=>'39.964745', 'longitude'=>'-75.179203'],
					['id'=>'134', 'latitude'=>'39.964717', 'longitude'=>'-75.179206'],
				];
			}
			$this->sendResponse($data, trans('theme.data_found_success'));
		} catch (Exception $e) {
			
		}
	}
	
	/**
	* Display a listing of the resource.
	* @return \Illuminate\Http\Response
	*/
	public function ajax_stops(Request $request){
		$page	= $request->page ?? '1';
		$count	= $request->count ?? '100';
		
		if ($page <= 0){ $page = 1; }
		$start  = $count * ($page - 1);
		
		try {
			// Get Bus Data
			$query = Stop::where(['status'=>'active'])->offset($start)->limit($count)->get();
			if(count($query) > 0){
				$this->sendResponse($query, trans('theme.data_found_success'));
			}
			$this->sendResponse([], trans('theme.data_found_empty'));
		} catch (Exception $e) {
			
		}
	}
	
	/**
	* Display a listing of the resource.
	* @return \Illuminate\Http\Response
	*/
	public function ajax_places(Request $request){
		$page     = $request->page ?? '1';
		$count	= $request->count ?? '100';
		
		if ($page <= 0){ $page = 1; }
		$start  = $count * ($page - 1);
		
		try {
			// Get Bus Data
			$query = Place::where(['status'=>'active'])->offset($start)->limit($count)->get();
			if(count($query) > 0){
				$this->sendResponse($query, trans('theme.data_found_success'));
			}
			$this->sendResponse([], trans('theme.data_found_empty'));
		} catch (Exception $e) {
			
		}
	}
	
	/**
	* Display a listing of the resource.
	* @return \Illuminate\Http\Response
	*/
	public function ajax_track(Request $request){
		try {
			// Get Bus Data
			$query = Bus::query();
			if($request->status){
				$query->where('status', $request->status)->where('live_status','online');
			}
			$data = $query->whereIn('device_type', explode(",", $request->device_type))->get();
			if(count($data) > 0){
				$this->sendResponse($data, trans('theme.data_found_success'));
			}
			$this->sendResponse([], trans('theme.data_found_empty'));
		} catch (Exception $e) {
			
		}
	}
}