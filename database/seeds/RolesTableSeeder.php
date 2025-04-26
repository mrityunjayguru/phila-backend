<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		// Developer
        $role1 = Role::firstOrCreate(['name' => 'developer','guard_name' => 'web']);
       	$permissions1 = Permission::whereIn('name',['role-list','role-create','role-edit','role-delete','permission-list','permission-create','permission-delete','developer-dashboard'])->get();
       	$role1->syncPermissions($permissions1);
        
		// superAdmin
        $role2 = Role::firstOrCreate(['name' => 'superAdmin','guard_name' => 'web']);
        $permissions2 = Permission::whereIn('name',[
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
        ])->get();
       	$role2->syncPermissions($permissions2);
    }
}