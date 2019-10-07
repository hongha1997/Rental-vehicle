<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Str;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;


class UserController extends Controller
{
    public function __construct(){
        $this->middleware('jwt.auth', ['except' => ['index', 'show','checkEmail']]);
    }
    
    public function index()
    {
        $users = User::all();
        $response = [
            'msg' => 'Danh sách tất cả các User',
            'users' => $users
        ];
        return response()->json($response, 200);
    }

    public function create()
    {
        
    }
    // public function checkEmail(Request $request, $email){
    //     $users = User::all();
    //     $email = $email;
    //     foreach ($users as $key => $value) {
    //         $haha = $users[$key]->email;
    //         if($haha == $email) {
    //             $response = [
    //                 'error' => 'Email đã bị trùng.'
    //             ];
    //             return response()->json($response, 400);
    //         }
    //     }
    //     $response = [];
    //     return response()->json($response, 200);
    // }
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required',
                'picture' => 'required',
                'number_phone' => 'required',
                'address' => 'required'
            ],
            [
                'name.required'=>'Yều cầu nhập tên đầy đủ.',
                'email.required'=>'Yều cầu nhập email.',
                'email.unique'=>'Email đã bị trùng, vui lòng nhập email khác.',
                'password.required'=>'Yều cầu nhập password.',
                'picture.required'=>'Yều cầu chọn ảnh CMND.',
                'number_phone.required'=>'Yều cầu nhập số điện thoại.',
                'address.required'=>'Yều cầu nhập địa chỉ cụ thể.'
            ]
        );
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $number_phone = $request->input('number_phone');
        $address = $request->input('address');

        $file_src = $request->file('picture');
        $is_file_upload = Storage::disk('dropbox')->put('img-api', $file_src);
        $picture = Storage::disk('dropbox')->url($is_file_upload);
        // $file = $request->file('picture');
        // $name_pic = $file->getClientOriginalName();
        // return $name_pic;
        // if($request->hasFile('picture')){
        //     $file = $request->file('picture');
        //     $name_pic = $file->getClientOriginalName();
        //     $random_pic = Str::random(4);
        //     $Hinh = $random_pic."_".$name_pic;

        //     // $Hinh = $name_pic;
        //     while (file_exists("templates/pictureuser/".$Hinh)) {
        //         $Hinh =  $random_pic."_".$name_pic;
        //     }
        //     $file->move('templates/pictureuser',$Hinh);
        //     $picture = $Hinh;
        // } else {
        //     $picture = "";
        // }
        // $random_pic = Str::random(4);
        // $picture =$random_pic."_".$request->input('picture');
        //$path = $request->file('picture')->store('avatars');
        // $path = Storage::putFile('avatars', $request->file('picture'));

        // $picture = $request->file('picture');
        // Storage::put($request, 'avatars');
        // $file = $request->input('picture');
        // $random_pic = Str::random(4);
        // $Hinh = $random_pic."_".$file;
        // while (file_exists("templates/pictureuser/".$Hinh)) {
        //     $Hinh =  $random_pic."_".$name_pic;
        // }
        // $file->move('templates/pictureuser',$Hinh);
        // $picture = $Hinh;
        $user = new User([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'picture' => $picture,
            'number_phone' => $number_phone,
            'address' => $address
        ]);
        if($user->save()) {
            $message = [
                'msg' => 'User đã được tạo.',
                'user' => $user
            ];
            return response()->json($message, 201);
        }
        $response = [
            'msg' => 'Error'
        ];
        return response()->json($response, 404);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $response = [
            'msg' => 'Chi tiết một User',
            'user' => $user
        ];
        return response()->json($response, 200);
    }

    public function edit($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'number_phone' => 'required',
                'address' => 'required'
            ],
            [
                'name.required'=>'Yều cầu nhập tên đầy đủ.',
                'email.required'=>'Yều cầu nhập email.',
                'password.required'=>'Yều cầu nhập password.',
                'number_phone.required'=>'Yều cầu nhập số điện thoại.',
                'address.required'=>'Yều cầu nhập địa chỉ cụ thể.'
            ]
        );
        $user = User::findOrFail($id);
        $oldPicture = $user->picture;
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $number_phone = $request->input('number_phone');
        $address = $request->input('address');
        if($request->hasFile('picture')){
            $file = $request->file('picture');
            $name_pic = $file->getClientOriginalName();
            $random_pic = Str::random(4);
            $Hinh = $random_pic."_".$name_pic;
            while (file_exists("templates/pictureuser/".$Hinh)) {
                $Hinh = $random_pic."_".$name_pic;
            }
            $file->move('templates/pictureuser',$Hinh);
            if ($oldPicture !="" && file_exists('templates/pictureuser/'.$oldPicture)) {
                unlink('templates/pictureuser/'.$oldPicture);
            }
            $picture = $Hinh;
        } else {
            $picture = $oldPicture;
        }
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;
        $user->picture = $picture;
        $user->number_phone = $number_phone;
        $user->address = $address;
        if(!$user->update()){
            return response()->json([
                'msg' => 'Error'
            ], 400);
        }
        $response = [
            'msg' => 'Sửa User thành công',
            'user' => $user
        ];
        return response()->json($response,200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $oldPicture = $user->picture;
        if ($oldPicture !="" && file_exists('templates/pictureuser/'.$oldPicture)) {
            unlink('templates/pictureuser/'.$oldPicture);          
        }
        if(!$user->delete()){
            return response()->json([
                'msg' => 'Xóa User không thành công.'
            ], 404);
        }
        $response = [
            'msg' => 'Xóa User thành công'
        ];
        return response()->json($response, 200);
    }
}
