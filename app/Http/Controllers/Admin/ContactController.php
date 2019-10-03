<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contact;

class ContactController extends Controller
{
    public function __construct(){
        $this->middleware('jwt.auth', ['except' => ['index', 'show','store']]);
    }

    public function index()
    {
        $contacts = Contact::all();
        $response = [
            'msg' => 'Danh sách tất cả liên hệ.',
            'contacts' => $contacts
        ];
        return response()->json($response, 200);
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'number_phone' => 'required|unique:contact',
                'email' => 'required|unique:contact',
                'address' => 'required',
                'content' => 'required',
            ],
            [
                'name.required'=>'Yều cầu nhập tên đầy đủ.',
                'number_phone.required'=>'Yều cầu nhập số điện thoại.',
                'number_phone.unique'=>'Trùng số điện thoại.',
                'email.required'=>'Yều cầu nhập email.',
                'email.unique'=>'Trùng email.',
                'address.required'=>'Yều cầu nhập địa chỉ cụ thể.',
                'content.required'=>'Yều cầu nhập nội dung.'
            ]
        );
        $name = $request->input('name');
        $number_phone = $request->input('number_phone');
        $email = $request->input('email');
        $address = $request->input('address');
        $content = $request->input('content');
        $contact = new Contact([
            'name' => $name,
            'number_phone' => $number_phone,
            'email' => $email,
            'address' => $address,
            'content' => $content,
            'status' => 0
        ]);
        if($contact->save()) {
            $message = [
                'msg' => 'Đã gửi yêu cầu trở thành cộng tác.',
                'contact' => $contact
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
        $contact = Contact::findOrFail($id);
        $response = [
            'msg' => 'Chi tiết một liên hệ.',
            'contact' => $contact
        ];
        return response()->json($response, 200);
    }

    public function edit($id)
    {
        
    }

    public function update(Request $request, $id)
    {       
        $status = $request->input('status');
        $contact = Contact::findOrFail($id);
        $contact->status = $status;
        if(!$contact->update()){
            return response()->json([
                'msg' => 'Error'
            ], 400);
        }
        $response = [
            'msg' => 'Sửa trạng thái liên hệ thành công',
            'contact' => $contact
        ];
        return response()->json($response,200);
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        if(!$contact->delete()){
            return response()->json([
                'msg' => 'Xóa liên hệ không thành công.'
            ], 404);
        }
        $response = [
            'msg' => 'Xóa liên hệ thành công'
        ];
        return response()->json($response, 200);
    }
}
