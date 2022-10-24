<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use App\Enums\Roles;
use App\Models\Role;

class AdminDataController extends Controller
{
    function updateUserData(Request $request) {
        $validatingRules = [
            'id' => 'required|integer'
        ];
        foreach(array_diff(array_keys($request->all()),['id']) as $field) {
            $validatingRules[$field] = 'min:3';
        }
        $validator = Validator::make($request->all(),$validatingRules);
        if($validator->fails()) return response()->json([
            'message' => 'no id or data length < 3'
        ],422);
        $data = $validator->validated();
        $user = User::find($data['id']);
        if($user === null) return response()->json([
            'message' => 'no user with id = '.$data['id']
        ],400);
        if(array_key_exists('name', $data)) {
            $user->name = $data['name'];
        }
        if(array_key_exists('password', $data)) {
            $user->password_hash = Hash::make($data['password']);
        }
        if(array_key_exists('login', $data)) {
            $user->login = $data['login'];
        }
        $user->save();
        return response()->json([]);
    }

    function getUserData(Request $request) {
        $validator = Validator::make($request->all(),[
            'id' => 'required|integer'
        ]);
        if(($validator->fails())) return response()->json([],422);
        $data = $validator->validated();
        $user = User::find($data['id']);
        if($user===null) return response()->json([
            'message' => 'no user with id = '.$data['id']
        ],404);
        return response()->json([
            'id' => $user->id,
            'login' => $user->login,
            'name' => $user->name,
            'role' => Role::find($user->role_id)->name
        ]);
    }

    function teacherDelete(Request $request) {
        $validator = Validator::make($request->all(),[
            'id' => 'required|integer'
        ]);
        if(($validator->fails())) return response()->json([],422);
        $data = $validator->validated();
        if($data['id']===1) return response()
            ->json(['message' => 'you cant delete admin'],400);
        $user = User::find($data['id']);
        if($user === null) return response()
            ->json(['message' => 'no user'],400);
        $user->delete();
        return response()->json([]);
    }

    function teacherNew(Request $request) {
        $validator = Validator::make($request->all(),[
            'login' => 'required|min:3',
            'password' => 'required|min:3',
            'name' => 'required|min:3'
        ]);
        if($validator->fails()) return response()->json([],422);
        $data = $validator->validated();
        $user = new User;
        $success = $user->makeFromRequest($data, Roles::TEACHER);
        if($success) return response()->json([]);
        return response()->json([],400);
    }
}
