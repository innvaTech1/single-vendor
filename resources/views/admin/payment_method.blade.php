@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Payment Methods')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Payment Methods')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
            </div>
          </div>

        <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-3">
                                    <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                        <li class="nav-item border rounded mb-1">
                                            <a class="nav-link active" id="sslcommerz-tab" data-toggle="tab" href="#sslcommerzTab" role="tab" aria-controls="sslcommerzTab" aria-selected="true">{{__('admin.SslCommerz')}}</a>
                                        </li>
                                        <li class="nav-item border rounded mb-1">
                                            <a class="nav-link" id="bank-account-tab" data-toggle="tab" href="#bankAccountTab" role="tab" aria-controls="bankAccountTab" aria-selected="true">{{__('admin.Bank Account')}}</a>
                                        </li>
                                        @if ($bank)
                                            <li class="nav-item border rounded mb-1">
                                                <a class="nav-link" id="cash-tab" data-toggle="tab" href="#cashTab" role="tab" aria-controls="cashTab" aria-selected="true">{{__('admin.Cash On Deliver')}}</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="col-12 col-sm-12 col-md-9">
                                    <div class="border rounded">
                                        <div class="tab-content no-padding" id="settingsContent">
                                            <div class="tab-pane fade active show" id="sslcommerzTab" role="tabpanel" aria-labelledby="sslcommerz-tab">
                                                <div class="card m-0">
                                                    <div class="card-body">
                                                        <form action="{{ route('admin.update-sslcommerz') }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="form-group">
                                                                <label for="">{{__('admin.SslCommerz Status')}}</label>
                                                                <div>
                                                                    @if ($sslcommerz?->status == 1)
                                                                        <input id="status_toggle" type="checkbox" checked data-toggle="toggle" data-on="{{__('admin.Enable')}}" data-off="{{__('admin.Disable')}}" data-onstyle="success" data-offstyle="danger" name="status">
                                                                        @else
                                                                        <input id="status_toggle" type="checkbox" data-toggle="toggle" data-on="{{__('admin.Enable')}}" data-off="{{__('admin.Disable')}}" data-onstyle="success" data-offstyle="danger" name="status">
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="">{{__('admin.Account Mode')}}</label>
                                                                <select name="account_mode" id="account_mode" class="form-control">
                                                                    <option {{ $sslcommerz?->mode == 'live' ? 'selected' : '' }} value="live">{{__('admin.Live')}}</option>
                                                                    <option {{ $sslcommerz?->mode == 'sandbox' ? 'selected' : '' }} value="sandbox">{{__('admin.Sandbox')}}</option>
                                                                </select>
                                                            </div>


                                                            <div class="form-group">
                                                                <label for="">{{__('admin.Store Id')}}</label>
                                                                <input type="text" class="form-control" name="store_id" value="{{ $sslcommerz?->store_id }}">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="">{{__('admin.Store Password')}}</label>
                                                                <input type="text" class="form-control" name="store_password" value="{{ $sslcommerz?->store_password }}">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="">{{__('admin.Currency Name')}}</label>
                                                                <select name="currency_name" id="" class="form-control select2">
                                                                    <option value="">{{__('admin.Select Currency')}}
                                                                  </option>
                                                                  @foreach ($currencies as $currency)
                                                                  <option {{ $sslcommerz?->currency_id == $currency->id ? 'selected' : '' }} value="{{ $currency->id }}">{{ $currency->currency_name }}
                                                                  </option>
                                                                  @endforeach
                                                                </select>
                                                            </div>

                                                            <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="bankAccountTab" role="tabpanel" aria-labelledby="bank-account-tab">
                                                <div class="card m-0">
                                                    <div class="card-body">
                                                        <form action="{{ route('admin.update-bank') }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="form-group">
                                                                <label for="">{{__('admin.Bank Payment Status')}}</label>
                                                                <div>
                                                                    @if ($bank?->status == 1)
                                                                        <input id="status_toggle" type="checkbox" checked data-toggle="toggle" data-on="{{__('admin.Enable')}}" data-off="{{__('admin.Disable')}}" data-onstyle="success" data-offstyle="danger" name="status">
                                                                        @else
                                                                        <input id="status_toggle" type="checkbox" data-toggle="toggle" data-on="{{__('admin.Enable')}}" data-off="{{__('admin.Disable')}}" data-onstyle="success" data-offstyle="danger" name="status">
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="">{{__('admin.Account Information')}}</label>
                                                                <textarea name="account_info" id="" cols="30" rows="10" class="text-area-5 form-control">{{ $bank?->account_info }}</textarea>
                                                            </div>

                                                            <button class="btn btn-primary">Update</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($bank)
                                                <div class="tab-pane fade" id="cashTab" role="tabpanel" aria-labelledby="cash-tab">
                                                    <div class="card m-0">
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <label for="">{{__('admin.Cash on delivery Status')}}</label>
                                                                <div>
                                                                    @if ($bank?->cash_on_delivery_status == 1)
                                                                        <a onclick="changeCashOnDeliveryStatus()" href="javascript:;">
                                                                            <input id="status_toggle" type="checkbox" checked data-toggle="toggle" data-on="{{__('admin.Enable')}}" data-off="{{__('admin.Disable')}}" data-onstyle="success" data-offstyle="danger" name="status">
                                                                        </a>
                                                                        @else
                                                                        <a onclick="changeCashOnDeliveryStatus()" href="javascript:;">
                                                                            <input id="status_toggle" type="checkbox" data-toggle="toggle" data-on="{{__('admin.Enable')}}" data-off="{{__('admin.Disable')}}" data-onstyle="success" data-offstyle="danger" name="status">
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>
      </div>

      <script>
        function changeCashOnDeliveryStatus(id){
            var isDemo = "{{ env('APP_VERSION') }}"
            if(isDemo == 0){
                toastr.error('This Is Demo Version. You Can Not Change Anything');
                return;
            }
            $.ajax({
                type:"put",
                data: { _token : '{{ csrf_token() }}' },
                url: "{{ route('admin.update-cash-on-delivery') }}",
                success:function(response){
                    toastr.success(response)
                },
                error:function(err){
                    console.log(err);

                }
            })
        }
    </script>
@endsection
