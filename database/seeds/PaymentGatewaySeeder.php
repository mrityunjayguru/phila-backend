<?php

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;
class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $DataArray = [
        	['title'=>'COD', 'slug' => 'COD', 'status' => 'active' ],
        	['title'=>'Razorpay', 'slug' => 'Razorpay', 'status' => 'active' ],
        ];
		foreach ($DataArray as $key => $value) {
			PaymentGateway::create($value);
        }
    }
}