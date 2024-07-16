<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use Image;
use File;

class AboutUsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $aboutUs = AboutUs::first();
        return view('admin.about-us', compact('aboutUs'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'description' => 'required',
        ];
        $customMessages = [
            'description.required' => trans('admin_validation.Description is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $aboutUs = AboutUs::find($id);
        if ($request->banner_image) {
            $exist_banner = $aboutUs->banner_image;
            $banner_name = file_upload($request->banner_image, $exist_banner, '/uploads/custom-images/');
            $aboutUs->banner_image = $banner_name;
            $aboutUs->save();
        }

        $aboutUs->description = $request->description;
        $aboutUs->save();

        $notification = trans('admin_validation.Updated Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function store(Request $request)
    {
        $rules = [
            'description' => 'required',
        ];
        $customMessages = [
            'banner_image.required' => trans('admin_validation.Banner is required'),
            'description.required' => trans('admin_validation.Description is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $aboutUs = new AboutUs();
        if ($request->banner_image) {
            $banner_name = file_upload($request->banner_image, null, '/uploads/custom-images/');
            $aboutUs->banner_image = $banner_name;
        }
        $aboutUs->description = $request->description;
        $aboutUs->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }
}
