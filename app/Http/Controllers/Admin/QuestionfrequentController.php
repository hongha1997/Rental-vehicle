<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Questionfrequent;

class QuestionfrequentController extends Controller
{
    public function __construct(){
        $this->middleware('jwt.auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $questionfrequents = Questionfrequent::all();
        $response = [
            'msg' => 'Danh sách tất cả các câu hỏi thường gặp.',
            'users' => $questionfrequents
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
                'title' => 'required',
                'content' => 'required'
            ],
            [
                'title.required'=>'Yều cầu nhập tiêu đề.',
                'content.required'=>'Yều cầu nhập nội dung.'
            ]
        );
        $title = $request->input('title');
        $content = $request->input('content');
        $questionfrequent = new Questionfrequent([
            'title' => $title,
            'content' => $content,
            'status' => 1
        ]);
        if($questionfrequent->save()) {
            $message = [
                'msg' => 'Câu hỏi thường gặp đã được tạo.',
                'user' => $questionfrequent
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
        $questionfrequent = Questionfrequent::findOrFail($id);
        $response = [
            'msg' => 'Chi tiết một câu hỏi thường gặp',
            'user' => $questionfrequent
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
                'title' => 'required',
                'content' => 'required'
            ],
            [
                'title.required'=>'Yều cầu nhập tiêu đề.',
                'content.required'=>'Yều cầu nhập nội dung.'
            ]
        );
        
        $title = $request->input('title');
        $content = $request->input('content');
        $status = $request->input('status');
        $questionfrequent = Questionfrequent::findOrFail($id);
        $questionfrequent->title = $title;
        $questionfrequent->content = $content;
        $questionfrequent->status = $status;
        if(!$questionfrequent->update()){
            return response()->json([
                'msg' => 'Error'
            ], 400);
        }
        $response = [
            'msg' => 'Sửa câu hỏi thường gặp thành công',
            'user' => $questionfrequent
        ];
        return response()->json($response,200);
    }

    public function destroy($id)
    {
        $questionfrequent = Questionfrequent::findOrFail($id);
        if(!$questionfrequent->delete()){
            return response()->json([
                'msg' => 'Xóa câu hỏi thường gặp không thành công.'
            ], 404);
        }
        $response = [
            'msg' => 'Xóa câu hỏi thường gặp thành công'
        ];
        return response()->json($response, 200);
    }
}
