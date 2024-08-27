<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return view('welcome');
});

Route::get('setup', function() {
    $credentials = [
        'email' => 'admin@mail.com',
        'password' => 'admin12345',
    ];

    if(!Auth::attempt($credentials)) {
        $user = new User();
        $user->name = 'Admin';
        $user->email = $credentials['email'];
        $user->password = Hash::make($credentials['password']);
        $user->save();
    } else {
        $user = Auth::user();
        $adminToken = $user->createToken('admin-token', ['create', 'read', 'update', 'delete']);
        $updateToken = $user->createToken('update-token', ['create', 'update']);
        $basicToken = $user->createToken('basic-token', ['none']);
        return [
            'adminToken' => $adminToken->plainTextToken,
            'updateToken' => $updateToken->plainTextToken,
            'basicToken' => $basicToken->plainTextToken
        ];
    }
});
