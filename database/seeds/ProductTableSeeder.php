<?php

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i <= 20; $i++) { 
	    	Product::Create([
				'title:en'       => 'Product '.$i,
				'description:en' => 'Lorem Ipsum has been the industry standard dummy text ever since the 1500s '.$i,
				'image' 		 => 'default/demo/products/product-'. rand(1, 10) .'.jpg',
				'price' 		 => rand(1111, 9999),
				'category_id'	 => rand(1, 10),
				'status'		 => 'active'
			]);
		}
    }
}