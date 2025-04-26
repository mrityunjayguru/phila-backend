<?php

use Illuminate\Database\Seeder;
use App\Models\State;

class StateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stateArray = array(array(1, 'Andaman and Nicobar Islands', 53),
								array(2, 'Andhra Pradesh', 53),
								array(3, 'Arunachal Pradesh', 53),
								array(4, 'Assam', 53),
								array(5, 'Bihar', 53),
								array(6, 'Chandigarh', 53),
								array(7, 'Chhattisgarh', 53),
								array(8, 'Dadra and Nagar Haveli', 53),
								array(9, 'Daman and Diu', 53),
								array(10, 'Delhi', 53),
								array(11, 'Goa', 53),
								array(12, 'Gujarat', 53),
								array(13, 'Haryana', 53),
								array(14, 'Himachal Pradesh', 53),
								array(15, 'Jammu and Kashmir', 53),
								array(16, 'Jharkhand', 53),
								array(17, 'Karnataka', 53),
								array(18, 'Kenmore', 53),
								array(19, 'Kerala', 53),
								array(20, 'dasds', 53),
								array(21, 'Madhya Pradesh', 53),
								array(22, 'Maharashtra', 53),
								array(23, 'Manipur', 53),
								array(24, 'Meghalaya', 53),
								array(25, 'Mizoram', 53),
								array(26, 'Nagaland', 53),
								array(27, 'Narora', 53),
								array(28, 'Natwar', 53),
								array(29, 'Odisha', 53),
								array(30, 'Paschim Medinipur', 53),
								array(31, 'Pondicherry', 53),
								array(32, 'Punjab', 53),
								array(33, 'Rajasthan', 53),
								array(34, 'Sikkim', 53),
								array(35, 'Tamil Nadu', 53),
								array(36, 'Telangana', 53),
								array(37, 'Tripura', 53),
								array(38, 'Uttar Pradesh', 53),
								array(39, 'Uttarakhand', 53),
								array(40, 'Vaishali', 53),
								array(41, 'West Bengal', 53));
		foreach ($stateArray as $key => $value) {
            $createArray = array();
            
            $createArray['name']            = $value[1];
            $createArray['country_id']      = $value[2];
            $createArray['status']          = 'active';

            State::create($createArray);
        }
    }
}