<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\News;
use Illuminate\Support\Str;
class NewsController extends Controller
{
    public function __construct(){
        $this->middleware('jwt.auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $newss = News::all();
        $response = [
            'msg' => 'Danh sách tất cả các tin tức.',
            'newss' => $newss
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
                'description' => 'required',
                'content' => 'required',
                'picture' => 'required'
            ],
            [
                'title.required'=>'Yều cầu nhập tiêu đề.',
                'description.required'=>'Yều cầu nhập mô tả.',
                'content.required'=>'Yều cầu nhập nội dung.',
                'picture.required'=>'Yều cầu chọn ảnh.',
            ]
        );
        $title = $request->input('title');
        $description = $request->input('description');
        $content = $request->input('content');

        if($request->hasFile('picture')){
            $file = $request->file('picture');
            $name_pic = $file->getClientOriginalName();
            $random_pic = Str::random(4);
            $Hinh = $random_pic."_".$name_pic;
            while (file_exists("templates/picturenews/".$Hinh)) {
                $Hinh =  $random_pic."_".$name_pic;
            }
            $file->move('templates/picturenews',$Hinh);
            $picture = $Hinh;
        } else {
            $picture = "";
        }
        $news = new News([
            'title' => $title,
            'description' => $description,
            'content' => $content,
            'picture' => $picture,
            'status' => 1
        ]);
        if($news->save()) {
            $message = [
                'msg' => 'Tin tức đã được tạo.',
                'news' => $news
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
        $news = News::findOrFail($id);
        $response = [
            'msg' => 'Chi tiết một tin tức.',
            'news' => $news
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
                'description' => 'required',
                'content' => 'required'
            ],
            [
                'title.required'=>'Yều cầu nhập tiêu đề.',
                'description.required'=>'Yều cầu nhập mô tả.',
                'content.required'=>'Yều cầu nhập nội dung.'
            ]
        );
        $news = News::findOrFail($id);
        $oldPicture = $news->picture;
        $title = $request->input('title');
        $description = $request->input('description');
        $content = $request->input('content');
        $status = $request->input('status');
        if($request->hasFile('picture')){
            $file = $request->file('picture');
            $name_pic = $file->getClientOriginalName();
            $random_pic = Str::random(4);
            $Hinh = $random_pic."_".$name_pic;
            while (file_exists("templates/picturenews/".$Hinh)) {
                $Hinh = $random_pic."_".$name_pic;
            }
            $file->move('templates/picturenews',$Hinh);
            if ($oldPicture !="" && file_exists('templates/picturenews/'.$oldPicture)) {
                unlink('templates/picturenews/'.$oldPicture);
            }
            $picture = $Hinh;
        } else {
            $picture = $oldPicture;
        }
        $news->title = $title;
        $news->description = $description;
        $news->content = $content;
        $news->picture = $picture;
        $news->status = $status;
        if(!$news->update()){
            return response()->json([
                'msg' => 'Error'
            ], 400);
        }
        $response = [
            'msg' => 'Sửa tin tức thành công',
            'news' => $news
        ];
        return response()->json($response,200);
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        $oldPicture = $news->picture;
        if ($oldPicture !="" && file_exists('templates/picturenews/'.$oldPicture)) {
            unlink('templates/picturenews/'.$oldPicture);          
        }
        if(!$news->delete()){
            return response()->json([
                'msg' => 'Xóa tin tức không thành công.'
            ], 404);
        }
        $response = [
            'msg' => 'Xóa tin tức thành công'
        ];
        return response()->json($response, 200);
    }
}
