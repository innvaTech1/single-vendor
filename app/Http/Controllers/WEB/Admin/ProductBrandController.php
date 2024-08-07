<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use  Image;
use File;
use Str;

class ProductBrandController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $brands = Brand::all();

        return view('admin.product_brand', compact('brands'));
    }

    public function create()
    {
        return view('admin.create_product_brand');
    }


    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:brands',
            'slug' => 'required|unique:brands',
            'status' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
        ];
        $this->validate($request, $rules, $customMessages);

        $brand = new Brand();
        if ($request->has('logo')) {
            $logo = $request->logo;
            $logo_name = file_upload($logo, null, '/uploads/custom-images/');
            $brand->logo = $logo_name;
        }
        $brand->name = $request->name;
        $brand->slug = $request->slug;
        $brand->status = $request->status;
        $brand->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.product-brand.index')->with($notification);
    }



    public function show($id)
    {
        $brand = Brand::find($id);
        return response()->json(['brand' => $brand], 200);
    }

    public function edit($id)
    {
        $brand = Brand::find($id);
        return view('admin.edit_product_brand', compact('brand'));
    }


    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);
        $rules = [
            'name' => 'required|unique:brands,name,' . $brand->id,
            'slug' => 'required|unique:brands,slug,' . $brand->id,
            'status' => 'required'
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
        ];
        $this->validate($request, $rules, $customMessages);

        if ($request->logo) {
            $old_logo = $brand->logo;
            $logo_name = file_upload($request->logo, $old_logo, 'uploads/custom-images/');
            $brand->logo = $logo_name;
            $brand->save();
        }

        $brand->name = $request->name;
        $brand->slug = $request->slug;
        $brand->status = $request->status;
        $brand->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.product-brand.index')->with($notification);
    }


    public function destroy($id)
    {
        $brand = Brand::find($id);
        $old_logo = $brand->logo;
        $brand->delete();
        if ($old_logo) {
            if (File::exists(public_path() . '/' . $old_logo)) unlink(public_path() . '/' . $old_logo);
        }

        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.product-brand.index')->with($notification);
    }

    public function changeStatus($id)
    {
        $brand = Brand::find($id);
        if ($brand->status == 1) {
            $brand->status = 0;
            $brand->save();
            $message = trans('admin_validation.InActive Successfully');
        } else {
            $brand->status = 1;
            $brand->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }
}
