<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BreadcrumbImage;
use Image;
use File;

class BreadcrumbController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $images = BreadcrumbImage::orderBy('id', 'asc')->get();

        return response()->json(['images' => $images], 200);
    }

    public function show($id)
    {
        $image = BreadcrumbImage::find($id);
        return response()->json(['image' => $image], 200);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'image' => 'required',
        ];
        $this->validate($request, $rules);
        $image = BreadcrumbImage::find($id);
        if ($request->image) {
            $exist_banner = $image->image;
            $banner_name = file_upload($request->banner_image, $exist_banner, '/uploads/custom-images/');
            $image->image = $banner_name;
            $image->save();
        }

        $notification = 'Updated Successfully';
        return response()->json(['notification' => $notification], 200);
    }
}
