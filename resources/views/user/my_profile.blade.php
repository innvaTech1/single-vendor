@extends('user.layout')
@section('title')
    <title>{{ __('My Profile') }}</title>
@endsection
@section('user-content')
    <div class="row">
        <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
            <div class="dashboard_content mt-2 mt-md-0">
                <h3><i class="far fa-user"></i> {{ __('My Profile') }}</h3>
                <div class="wsus__dashboard_profile">
                    <div class="wsus__dash_pro_area">
                        <form action="{{ route('user.update-profile') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-xl-9">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="wsus__dash_pro_single">
                                                <input type="text" placeholder="{{ __('Name') }}" name="name"
                                                    value="{{ $user->name }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="wsus__dash_pro_single">
                                                <input type="email" name="email"  value="{{ $user->email }}"
                                                    placeholder="{{ __('email') }}" @if ($user->email)
                                                        readonly
                                                    @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="wsus__dash_pro_single">
                                                <input type="text" name="phone" value="{{ $user->phone }}"
                                                    placeholder="{{ __('phone') }}" @if ($user->phone && !$user->email)
                                                        readonly
                                                    @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="wsus__add_address_single">
                                                <div class="wsus__topbar_select">
                                                    <select class="select_2" name="state" id="state_id">
                                                        <option value="0">{{ __('Select State') }}</option>
                                                        @foreach ($states as $state)
                                                            <option {{ $state->id == $user->state_id ? 'selected' : '' }}
                                                                value="{{ $state->id }}">{{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="wsus__add_address_single">
                                                <div class="wsus__topbar_select">
                                                    <select class="select_2" name="city" id="city_id">
                                                        <option value="0">{{ __('Select City') }}</option>
                                                        @foreach ($cities as $city)
                                                            <option {{ $city->id == $user->city_id ? 'selected' : '' }}
                                                                value="{{ $city->id }}">{{ $city->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="wsus__dash_pro_single">
                                                <input type="text" name="zip_code" value="{{ $user->zip_code }}"
                                                    placeholder="{{ __('Zip Code') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="wsus__dash_pro_single">
                                                <input type="text" name="address" value="{{ $user->address }}"
                                                    placeholder="{{ __('Address') }}">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-xl-3 col-sm-6 col-md-6">
                                    <div class="wsus__dash_pro_img">
                                        @if ($user->image)
                                            <img src="{{ asset($user->image) }}" alt="img" class="img-fluid w-100">
                                        @else
                                            <img src="{{ asset($defaultProfile->image) }}" alt="img"
                                                class="img-fluid w-100">
                                        @endif
                                        <input type="file" name="image">
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <button class="common_btn mb-4 mt-2" type="submit">{{ __('Update Profile') }}</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $("#state_id").on("change", function() {
                    const stateId = $(this).val();
                    $.ajax({
                        type: "get",
                        url: "{{ route('user.city-by-state', '') }}" + "/" + stateId,
                        success: function(response) {
                            console.log(response);
                            $("#city_id").html(response.cities);
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    })
                })


            });
        })(jQuery);
    </script>
@endsection
