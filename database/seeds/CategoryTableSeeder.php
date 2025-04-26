<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i <= 10; $i++) { 
	    	Category::Create([
				'title:en'       => 'Category '.$i,
				'description:en' => 'Lorem Ipsum has been the industry standard dummy text ever since the 1500s '.$i,
				'image' 		 => 'default/demo/categories/category-'. $i .'.png',
				'priority' 		 => $i,
				'post_type'		 => 'Product',
				'status'		 => 'active'
			]);
		}
    }
}