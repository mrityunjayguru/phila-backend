<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermissions extends Model
{
    protected $table = 'user_has_permissions';

    protected $fillable = [
  		'user_id','permission_id'
    ];
    public static function check($permission){
    	return UserPermissions::where('t2.name',$permission)->join('permissions as t2', 't2.id', '=', 'user_has_permissions.permission_id')->count();
    }
}