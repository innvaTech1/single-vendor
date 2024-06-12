<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\CountryState;
use App\Models\City;
use Auth;

class AddressCotroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $user = Auth::guard('api')->user();
        $addresses = Address::with('countryState','city')->where(['user_id' => $user->id])->get();

        return response()->json(['addresses' => $addresses]);
    }

    public function create(){
    }

    public function store(Request $request){
        $rules = [
            'name'=>'required',
            'phone'=>'required',
            'state'=>'required',
            'city'=>'required',
            'address'=>'required',
            'type'=>'required',
        ];
        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'phone.required' => trans('user_validation.Phone is required'),
            'state.required' => trans('user_validation.State is required'),
            'city.required' => trans('user_validation.City is required'),
            'address.required' => trans('user_validation.Address is required'),
            'type.required' => trans('user_validation.Address type is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('api')->user();
        $isExist = $user ? Address::where(['user_id' => $user?->id])->count() : 0;
        $address = new Address();
        $address->user_id = $user?->id;
        $address->name = $request->name;
        $address->email = $request->email;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->state_id = $request->state;
        $address->city_id = $request->city;
        $address->type = $request->type;
        if($isExist == 0){
            $address->default_billing = 1;
            $address->default_shipping = 1;
        }
        $address->save();

        $notification = trans('user_validation.Create Successfully');
        return response()->json(['notification' => $notification]);

    }


    public function show($id){
        $user = Auth::guard('api')->user();
        $address = Address::with('countryState','city')->where(['user_id' => $user->id, 'id' => $id])->first();
        if(!$address){
            $notification = trans('user_validation.Something went wrong');
            return response()->json(['notification' => $notification],403);
        }

        return response()->json(['address' => $address]);

    }


    public function edit($id){
        $user = Auth::guard('api')->user();
        $address = Address::where(['user_id' => $user->id, 'id' => $id])->first();
        if(!$address){
            $notification = trans('user_validation.Something went wrong');
            return response()->json(['notification' => $notification],403);
        }
        $states = CountryState::orderBy('name','asc')->where(['status' => 1])->get();
        $cities = City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => $address->state_id])->get();

        return response()->json([
            'address' => $address,
            'states' => $states,
            'cities' => $cities
        ]);
    }


    public function update(Request $request, $id){
        $user = Auth::guard('api')->user();
        $address = Address::where(['user_id' => $user->id, 'id' => $id])->first();
        if(!$address){
            $notification = trans('user_validation.Something went wrong');
            return response()->json(['notification' => $notification],403);
        }

        $rules = [
            'name'=>'required',
            'phone'=>'required',
            'state'=>'required',
            'city'=>'required',
            'address'=>'required',
            'type'=>'required',
        ];
        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'phone.required' => trans('user_validation.Phone is required'),
            'state.required' => trans('user_validation.State is required'),
            'city.required' => trans('user_validation.City is required'),
            'address.required' => trans('user_validation.Address is required'),
            'type.required' => trans('user_validation.Address type is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('api')->user();
        $address->name = $request->name;
        $address->email = $request->email;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->state_id = $request->state;
        $address->city_id = $request->city;
        $address->city_id = $request->city;
        $address->type = $request->type;
        $address->save();

        $notification = trans('user_validation.Update Successfully');
        return response()->json(['notification' => $notification]);
    }

    public function destroy($id){
        $user = Auth::guard('api')->user();
        $address = Address::where(['user_id' => $user->id, 'id' => $id])->first();
        if(!$address){
            $notification = trans('user_validation.Something went wrong');
            return response()->json(['notification' => $notification],403);
        }

        if($address->default_billing == 1 && $address->default_shipping == 1){
            $notification = trans('user_validation.Opps!! Default address can not be delete.');
            return response()->json(['notification' => $notification],403);
        }else{
            $address->delete();
            $notification = trans('user_validation.Delete Successfully');
            return response()->json(['notification' => $notification]);
        }
    }
}
