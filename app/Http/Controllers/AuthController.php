<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

use JWTAuth;
use JWTAuthException;
use Illuminate\Support\Facades\Auth;



// use Laravel\Lumen\Routing\Controller as BaseController;
// use Tymon\JWTAuth\JWTAuth;



class AuthController extends Controller
{
    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }
    public function signin(Request $request)
    {
    	$request->validate(
            [
                'email' => 'required|email',
                'password' => 'required|min:5'
            ],
            [
                'email.required'=>'Yều cầu nhập email',
                'email.email'=>'Yều cầu nhập email đung',
                'password.required'=>'Yều cầu nhập password d',
                'password.min'=>'Yều cầu nhập password nho nhất 5'
            ]
        );
        $email = $request->input('email');
        $password = $request->input('password');

        if($user = User::where('email', $email)->first()) {
            $credentials = [
                'email' => $email,
                'password' => $password,
            ];

            $token = null;
            try {   
                if(!$token = JWTAuth::attempt($credentials)){
                    return response()->json(['msg'=>'Email or pass are incorrect'], 404);
                }
            } catch(JWTAuthException $e) {
                return response()->json(['msg'=>'Failed to create token'], 404);
            }

            $response = [
                'msg' => 'User signin',
                'user' => $user,
                'token' => $token
            ];
            return response()->json($response, 201);
        }
        $response = [
            'msg' => 'An error'
        ];

        return response()->json($response, 404);
    }

    public function logout()
    {
        JWTAuth::parseToken()->invalidate();
        return response([
            'msg' => 'Đăng xuất thành công.'
        ], 200);
    }
}
