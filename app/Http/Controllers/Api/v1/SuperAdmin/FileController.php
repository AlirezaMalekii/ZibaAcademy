<?php

namespace App\Http\Controllers\Api\v1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileController extends UploadController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function index()
//    {
//        //
//    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'images' => 'required|mimes:jpeg,png,jpg|max:10240|dimensions:max_height=2000'
        ]);
        $file = $request->file('images');
        $filename = $file->getClientOriginalName();
        if (Str::contains($filename, [' ', '/', '\\'])) {
            return response([
                'message'=>'برای عدم خطا لطفا از فاصله یا / یا \\ پرهیز کنید',
                'status' => 'failed'
            ], 400);
        }
        $fileurl=$this->uploadImage($request->file('images'));
        return response([
            'images'=>$fileurl,
            'status' => 'success'
        ], 200);

    }
    public function store_video(Request $request)
    {
        $request->validate([
//            'video' => 'required|mimetypes:video/avi,video/mpeg,video/quicktime|max:102400'
            'video' => 'required|mimes:m4v,avi,flv,ogv,mkv,mp4,mov,qt|max:102400'
//            mimetypes:video/avi,video/mpeg,video/quicktime|max:102400
//        mimes:m4v,avi,flv,mp4,mov,qt
        ]);
//        return $request->file('video')->getMimeType();
        $fileurl=$this->saveFile($request->file('video'),'public');
        return $fileurl;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
