<?php

namespace App\Http\Controllers;

use App\UserDevices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\User;

class AccountController extends Controller
{
    public function Register(Request $request)
    {

        //Check if username exists
        if(User::where('name', $request->input('name'))->exists())
        {
            return response('Username already exists!', 500)->header('Content-Type', 'text/plain');
        }

        //Check if email exists
        if(User::where('email', $request->input('email'))->exists())
        {
            return response('Email already exists!', 500)->header('Content-Type', 'text/plain');
        }

        $user = new User;

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return 'Account created - you can now login!';
    }

    public function Login(Request $request)
    {
        if (Auth::attempt(['name' => $request->input('name'), 'password' => $request->input('password')]))
        {
            if(UserDevices::where('device_unique_id', $request->input('device_unique_id'))->exists())
            {
                return "authenticated";
            }
            else
            {
                $userDevice = new UserDevices();
                $userDevice->device_unique_id = $request->input('device_unique_id');
                $userDevice->user_id = Auth::user()->id;
                $userDevice->save();

                return "authenticated";
            }
        }
        else
        {
            return response('Login failed!', 500)->header('Content-Type', 'text/plain');
        }
    }

    public function CheckSession(Request $request)
    {
        if(UserDevices::where('device_unique_id', $request->input('device_unique_id'))->exists())
        {
            return response('authenticated', 200)->header('Content-Type', 'text/plain');
        }
        else
        {
            return response('', 500)->header('Content-Type', 'text/plain');
        }
    }

    public function Logout(Request $request)
    {
        UserDevices::where('device_unique_id', '=', $request->input('device_unique_id'))->delete();
        return "unauthenticated";
    }
}
