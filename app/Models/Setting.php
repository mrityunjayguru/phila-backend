<?php

namespace App\Models;

use Auth;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'setting';

    protected $fillable = [
  		'name','value','status'
    ];
	
    public static function get($name){
    	$value = Setting::where('name',$name)->first();
    	return $value->value; 
    }
	
    public static function has($name){
    	$value = Setting::where('name',$name)->first();
    	if($value){
    		return true;
    	} else {
    		return false;
    	}
    }
	
	// Check User Permissions
	public static function UserPermission($permission){
    	return $data = UserPermissions::where('t2.name',$permission)->where('user_has_permissions.user_id',Auth::user()->id)->join('permissions as t2', 't2.id', '=', 'user_has_permissions.permission_id')->count();
    }
}