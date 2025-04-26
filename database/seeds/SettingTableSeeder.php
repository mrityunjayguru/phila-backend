<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;
class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $settingArray = [
        	['name'=>'site_name', 'value' => 'City Sightseeing Philadelphia', 'status' => 'active' ],
        	['name'=>'contact_no', 'value' => '+91 1234567890', 'status' => 'active' ],
        	['name'=>'site_email', 'value' => 'mail@citysightseeingphila.com', 'status' => 'active' ],
            ['name'=>'site_short_name', 'value' => 'CitySightseeing', 'status' => 'active' ],
            ['name'=>'chat_url', 'value' => 'www.google.com', 'status' => 'active' ],
            ['name'=>'ticket_url', 'value' => 'www.google.com', 'status' => 'active' ],
            ['name'=>'app_version', 'value' => '1.0.0', 'status' => 'active' ],
            ['name'=>'copy_rights_credit_line', 'value' => 'City Sightseeing Philadelphia', 'status' => 'active' ],
            ['name'=>'copy_rights_year', 'value' => '2020-2021', 'status' => 'active' ],
            ['name'=>'google_map_api_key', 'value' => '111', 'status' => 'active' ],
            ['name'=>'moderation_queue', 'value' => 'on', 'status' => 'active' ],
            ['name'=>'fcm_server_key', 'value' => 'AAAAdLAgSe0:APA91bGpyzT-TXhNfoRZkGBPxSPOmXAlZqZAstUvaH3jf_MOl0anvTsM3kw7M_rllwbAo12Bk3X3_GxIuWsrgk_khgD-4_F1WKvoIS5HsORNiu1Lluexq_vwO1ZDWqHNU3vXASwptmA4', 'status' => 'active' ],
            ['name'=>'address', 'value' => '123 main street', 'status' => 'active' ],
            ['name'=>'logo', 'value' => 'uploads/2022/02/b98dce38e96775f6cc8404824a37d7d3.png', 'status' => 'active' ],
        ];
		foreach ($settingArray as $key => $value) {
			Setting::create($value);
        }
    }
}