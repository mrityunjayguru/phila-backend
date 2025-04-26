<?php


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $permissions = [
            // DEVELOPER 
            //'role-list',
            //'role-create',
            //'role-edit',
            //'role-delete',
            //'permission-list',
            //'permission-create',
            //'permission-delete',
            
			// SUPER ADMIN 
            'ticket-list',
            'ticket-create',
            'ticket-edit',
            'ticket-delete',
			
			'slider-list',
            'slider-create',
            'slider-edit',
            'slider-delete',
			
			'place-list',
            'place-create',
            'place-edit',
            'place-delete',
			
            'offer-list',
            'offer-create',
            'offer-edit',
            'offer-delete',
			
			'stop-list',
            'stop-create',
            'stop-edit',
            'stop-delete',
			
			'bus-list',
            'bus-create',
            'bus-edit',
            'bus-delete',
			
			'timing-management',
			
			'custom-map-management',
			
			'notification-management',
			
			'cms-management',
			'faq-list',
            'faq-create',
            'faq-edit',
            'faq-delete',
			
			'user-list',
            'user-create',
            'user-edit',
            'user-delete',
			
			'general-settings-list',
			'general-settings-edit',
			'change-password',
			
			'audio-list',
            'audio-create',
            'audio-edit',
            'audio-delete',
        ];
        foreach ($permissions as $permission) {
          Permission::firstOrCreate(['name' => $permission]);
        }
    }
}