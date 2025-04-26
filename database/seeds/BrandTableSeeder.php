<?php

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i <= 10; $i++) { 
	    	Brand::Create([
				'title'			=> 'Brand '.$i,
				'description'	=> 'Lorem Ipsum has been the industry standard dummy text ever since the 1500s '.$i,
				'image'			=> 'default/demo/brands/brand-'. $i .'.jpg',
				'priority'		=> $i,
				'status'		=> 'active'
			]);
		}
    }
}