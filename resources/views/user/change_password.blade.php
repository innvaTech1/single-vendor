@extends('user.layout')
@section('title')
    <title>{{ __('user.Change Password') }}</title>
@endsection
@section('user-content')
    <div class="row">
        <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
            <div class="dashboard_content mt-2 mt-md-0">
                <h3><i class="far fa-user"></i> {{ __('user.Change Password') }}</h3>
                <div class="wsus__dashboard_profile">
                    <div class="wsus__dash_pro_area">
                        <form action="{{ route('user.update-password') }}" method="POST">
                            @csrf
                            <div class="wsus__dash_pass_change mt-2">
                                <div class="row">
                                    <div class="col-xl-4 col-md-6">
                                        <div class="wsus__dash_pro_single">
                                            <i class="fas fa-unlock-alt"></i>
                                            <input type="password" placeholder="Current Password" name="current_password">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6">
                                        <div class="wsus__dash_pro_single">
                                            <i class="fas fa-lock-alt"></i>
                                            <input type="password" placeholder="New Password" name="password">
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="wsus__dash_pro_single">
                                            <i class="fas fa-lock-alt"></i>
                                            <input type="password" placeholder="Confirm Password" name="password_confirmation">
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <button class="common_btn" type="submit">{{ __('user.Change Password') }}</button>
                                    </div>
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
