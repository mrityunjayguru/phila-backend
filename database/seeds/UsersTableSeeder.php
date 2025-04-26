<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 2; $i++) { 
	    	if($i == 0){
	    		$role = Role::where('name','developer')->first();
	    		$country_code = '+91';
	    		$phone_number = '1234567890';
	    		$password = '11111111';
	    	} else if($i == 1){
	    		$role = Role::where('name','superAdmin')->first();
	    		$country_code = '+91';
	    		$phone_number = '1234569078';
				$password = '11111111';
	    	}
	    	$user = User::firstOrCreate([
			            'name' 				=> $role->name,
			            'email' 			=> $role->name.'@mail.com',
			            'country_code' 		=> $country_code,
			            'phone_number' 		=> $phone_number,
			            'password' 			=> bcrypt($password),
			            'user_type' 		=> $role->name,
			            'profile_image' 	=> '',
			            'status' 			=> 'active',
			            'email_verified_at' => date('Y-m-d H:i:s'),
			        ]);
   			$user->assignRole([$role->id]);
    	}
    }
}