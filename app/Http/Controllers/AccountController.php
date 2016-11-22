<?php

namespace App\Http\Controllers;

use App\Starsystem;
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

        $user->starsystem_id = Starsystem::inRandomOrder()->first()->id;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        $ret = array(
            'status' => 'registered',
            'name' => $user->name
        );

        echo json_encode($ret);
        exit();
    }

    public function Login(Request $request)
    {
        //Attempt to authorize the player
        if (Auth::attempt(['name' => $request->input('name'), 'password' => $request->input('password')]))
        {
            //If the players device is already added authenticate the player
            //or add the device and authenticate
            if(UserDevices::where('device_unique_id', $request->input('device_unique_id'))->exists())
            {
                return "authenticated";
            }
            else
            {
                //Create new player device
                $userDevice = new UserDevices();
                $userDevice->device_unique_id = $request->input('device_unique_id');
                $userDevice->user_id = Auth::user()->id;
                $userDevice->save();

                //Return user, starsystem and star
                $user = User::find(Auth::user()->id);
                $starsystem = $user->starsystem()->first();
                $star = $starsystem->star()->first();

                //Get close by stars
                $currentId = $starsystem->id;
                $start = $currentId - 500;
                $amount = 1000;

                if($start < 1)
                    $start = 1;

                $results = Starsystem::query('SELECT * FROM starsystems LIMIT :start, :amount', ['start' => $start, 'amount' => $amount]);

                $ret = array(
                    'status' => 'authenticated',
                    'user' => $user->toArray(),
                    'starsystem' => $starsystem->toArray(),
                    'star' => $star->toArray(),
                    'neighnours' => $results->toArray()
                );

                echo json_encode($ret);
                exit();
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
            //Get user id
            $user_id = UserDevices::where('device_unique_id', $request->input('device_unique_id'))->first()->user_id;

            //Return user, starsystem and star
            $user = User::find($user_id);
            $starsystem = $user->starsystem()->first();
            $star = $starsystem->star()->first();

            $ret = array(
                'status' => 'authenticated',
                'user' => $user->toArray(),
                'starsystem' => $starsystem->toArray(),
                'star' => $star->toArray()
            );

            echo json_encode($ret);
            exit();
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
