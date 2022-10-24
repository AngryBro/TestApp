<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HasFactory;
    use SoftDeletes;

    function makeFromRequest($data, $role) {
        $exists = self::where('login',$data['login'])->first()!==null;
        if($exists) return false;
        $this->name = $data['name'];
        $this->password_hash = Hash::make($data['password']);
        $this->role_id = Role::where('name', $role)->first()->id;
        $this->remember_token = Str::random(80);
        $this->login = $data['login'];
        $this->group_id = array_key_exists('group_id',$data)?$data['group_id']:null;
        $this->save();
        return true;
    }

}
