@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Advertisement')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Advertisement')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Advertisement')}}</div>
            </div>
          </div>

          <div class="section-body">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-4">
                                <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                    <li class="nav-item border rounded mb-1">
                                        <a class="nav-link active" id="error-tab-1" data-toggle="tab" href="#errorTab-1" role="tab" aria-controls="errorTab-1" aria-selected="true">{{__('admin.Slider Sidebar Banner One')}}</a>
                                    </li>

                                    <li class="nav-item border rounded mb-1">
                                        <a class="nav-link" id="one-column-banner-tab" data-toggle="tab" href="#oneColumnBanner" role="tab" aria-controls="oneColumnBanner" aria-selected="true">{{__('admin.Slider Sidebar Banner Two')}}</a>
                                    </li>

                                    <li class="nav-item border rounded mb-1">
                                        <a class="nav-link" id="two-column-banner-first" data-toggle="tab" href="#twoColumnBannerFirst" role="tab" aria-controls="twoColumnBannerFirst" aria-selected="true">{{__('admin.Popular Category Sidebar Banner')}}</a>
                                    </li>

                                    <li class="nav-item border rounded mb-1">
                                        <a class="nav-link" id="two-column-banner-second" data-toggle="tab" href="#twoColumnBannerSecond" role="tab" aria-controls="twoColumnBannerSecond" aria-selected="true">{{__('admin.Homepage Two Column First Banner')}}</a>
                                    </li>

                                    <li class="nav-item border rounded mb-1">
                                        <a class="nav-link" id="two-column-banner-third" data-toggle="tab" href="#twoColumnBannerThird" role="tab" aria-controls="twoColumnBannerThird" aria-selected="true">{{__('admin.Homepage Two Column Second Banner')}}</a>
                                    </li>


                                    <li class="nav-item border rounded mb-1">
                                        <a class="nav-link" id="product-details" data-toggle="tab" href="#productDetails" role="tab" aria-controls="productDetails" aria-selected="true">{{__('admin.Homepage Single Banner Two')}}</a>
                                    </li>

                                    <li class="nav-item border rounded mb-1">
                                        <a class="nav-link" id="megamanu-banner" data-toggle="tab" href="#megaMenuBanner" role="tab" aria-controls="megaMenuBanner" aria-selected="true">{{__('admin.Mega Menu Banner')}}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-12 col-sm-12 col-md-8">
                                <div class="border rounded">
                                    <div class="tab-content no-padding" id="settingsContent">
                                        <div class="tab-pane fade show active" id="errorTab-1" role="tabpanel" aria-labelledby="error-tab-1">
                                            <div class="card m-0">
                                                <div class="card-body">
                                                    <form action="{{ route('admin.slider-banner-one') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Current Banner')}}</label>
                                                                <div>
                                                                    <img src="{{ asset($sliderSidebarBannerOne?->image) }}" alt="" width="200px">
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.New Banner')}}</label>
                                                                <input type="file" name="banner_image" class="form-control-file">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title One')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_one" class="form-control" value="{{ $sliderSidebarBannerOne?->title_one }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title Two')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_two" class="form-control" value="{{ $sliderSidebarBannerOne?->title_two }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Badge')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="badge" class="form-control" value="{{ $sliderSidebarBannerOne?->badge }}">
                                                            </div>




                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Category Link')}} <span class="text-danger">*</span></label>
                                                                <select name="product_slug" id="" class="form-control select2">
                                                                    <option value="">{{__('admin.Select Product')}}</option>
                                                                    @foreach ($products as $product)
                                                                        <option {{ $sliderSidebarBannerOne?->product_slug == $product->slug ? 'selected' : '' }} value="{{ $product->slug }}">{{ $product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>



                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                                                <select name="status" class="form-control">
                                                                    <option {{ $sliderSidebarBannerOne?->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                                                    <option {{ $sliderSidebarBannerOne?->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                                                </select>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="oneColumnBanner" role="tabpanel" aria-labelledby="one-column-banner-tab">
                                            <div class="card m-0">
                                                <div class="card-body">
                                                    <form action="{{ route('admin.slider-banner-two') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Current Banner')}}</label>
                                                                <div>
                                                                    <img src="{{ asset($sliderSidebarBannerTwo?->image) }}" alt="" width="200px">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.New Banner')}}</label>
                                                                <input type="file" name="banner_image" class="form-control-file">
                                                            </div>


                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title One')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_one" class="form-control" value="{{ $sliderSidebarBannerTwo?->title_one }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title Two')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_two" class="form-control" value="{{ $sliderSidebarBannerTwo?->title_two }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Badge')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="badge" class="form-control" value="{{ $sliderSidebarBannerTwo?->badge }}">
                                                            </div>


                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Category Link')}} <span class="text-danger">*</span></label>
                                                                <select name="product_slug" id="" class="form-control select2">
                                                                    <option value="">{{__('admin.Select Product')}}</option>
                                                                    @foreach ($products as $product)
                                                                        <option {{ $sliderSidebarBannerTwo?->product_slug == $product->slug ? 'selected' : '' }} value="{{ $product->slug }}">{{ $product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                                                <select name="status" class="form-control">
                                                                    <option {{ $sliderSidebarBannerTwo?->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                                                    <option {{ $sliderSidebarBannerTwo?->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="twoColumnBannerFirst" role="tabpanel" aria-labelledby="two-column-banner-first">
                                            <div class="card m-0">
                                                <div class="card-body">
                                                    <form action="{{ route('admin.popular-category-sidebar') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Banner One')}}</label>
                                                                <div>
                                                                    <img src="{{ asset($popularCategorySidebarBanner?->image) }}" alt="" width="200px">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.New Banner')}}</label>
                                                                <input type="file" name="banner_image" class="form-control-file">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Category Link')}} <span class="text-danger">*</span></label>
                                                                <select name="product_slug" id="" class="form-control select2">
                                                                    <option value="">{{__('admin.Select Product')}}</option>
                                                                    @foreach ($products as $product)
                                                                        <option {{ $popularCategorySidebarBanner?->product_slug == $product->slug ? 'selected' : '' }} value="{{ $product->slug }}">{{ $product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="tab-pane fade" id="twoColumnBannerSecond" role="tabpanel" aria-labelledby="two-column-banner-second">
                                            <div class="card m-0">
                                                <div class="card-body">
                                                    <form action="{{ route('admin.homepage-two-col-first-banner') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Banner One')}}</label>
                                                                <div>
                                                                    <img src="{{ asset($homepageTwoColumnBannerOne?->image) }}" alt="" width="200px">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.New Banner')}}</label>
                                                                <input type="file" name="banner_image" class="form-control-file">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title One')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_one" class="form-control" value="{{ $homepageTwoColumnBannerOne?->title_one }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title Two')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_two" class="form-control" value="{{ $homepageTwoColumnBannerOne?->title_two }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Badge')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="badge" class="form-control" value="{{ $homepageTwoColumnBannerOne?->badge }}">
                                                            </div>


                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Category Link')}} <span class="text-danger">*</span></label>
                                                                <select name="product_slug" id="" class="form-control select2">
                                                                    <option value="">{{__('admin.Select Product')}}</option>
                                                                    @foreach ($products as $product)
                                                                        <option {{ $homepageTwoColumnBannerOne?->product_slug == $product->slug ? 'selected' : '' }} value="{{ $product->slug }}">{{ $product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                                                <select name="status" class="form-control">
                                                                    <option {{ $homepageTwoColumnBannerOne?->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                                                    <option {{ $homepageTwoColumnBannerOne?->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                                                </select>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="twoColumnBannerThird" role="tabpanel" aria-labelledby="two-column-banner-third">
                                            <div class="card m-0">
                                                <div class="card-body">
                                                    <form action="{{ route('admin.homepage-two-col-second-banner') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Banner One')}}</label>
                                                                <div>
                                                                    <img src="{{ asset($homepageTwoColumnBannerTwo?->image) }}" alt="" width="200px">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.New Banner')}}</label>
                                                                <input type="file" name="banner_image" class="form-control-file">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title One')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_one" class="form-control" value="{{ $homepageTwoColumnBannerTwo?->title_one }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title Two')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_two" class="form-control" value="{{ $homepageTwoColumnBannerTwo?->title_two }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Badge')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="badge" class="form-control" value="{{ $homepageTwoColumnBannerTwo?->badge }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Category Link')}} <span class="text-danger">*</span></label>
                                                                <select name="product_slug" id="" class="form-control select2">
                                                                    <option value="">{{__('admin.Select Product')}}</option>
                                                                    @foreach ($products as $product)
                                                                        <option {{ $homepageTwoColumnBannerTwo?->product_slug == $product->slug ? 'selected' : '' }} value="{{ $product->slug }}">{{ $product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                                                <select name="status" class="form-control">
                                                                    <option {{ $homepageTwoColumnBannerTwo?->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                                                    <option {{ $homepageTwoColumnBannerTwo?->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="productDetails" role="tabpanel" aria-labelledby="product-details">
                                            <div class="card m-0">
                                                <div class="card-body">
                                                    <form action="{{ route('admin.homepage-single-second-banner') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Existing Banner')}}</label>
                                                                <div>
                                                                    <img src="{{ asset($homepageSingleBannerTwo?->image) }}" alt="" width="200px">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.New Banner')}}</label>
                                                                <input type="file" name="banner_image" class="form-control-file">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title" class="form-control" value="{{ $homepageSingleBannerTwo?->title_one }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Category Link')}} <span class="text-danger">*</span></label>
                                                                <select name="product_slug" id="" class="form-control select2">
                                                                    <option value="">{{__('admin.Select Product')}}</option>
                                                                    @foreach ($products as $product)
                                                                        <option {{ $homepageSingleBannerTwo?->product_slug == $product->slug ? 'selected' : '' }} value="{{ $product->slug }}">{{ $product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                                                <select name="status" class="form-control">
                                                                    <option {{ $homepageSingleBannerTwo?->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                                                    <option {{ $homepageSingleBannerTwo?->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="megaMenuBanner" role="tabpanel" aria-labelledby="megamenu-banner">
                                            <div class="card m-0">
                                                <div class="card-body">
                                                    <form action="{{ route('admin.mega-menu-banner-update') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Banner One')}}</label>
                                                                <div>
                                                                    <img src="{{ asset($megaMenuBanner?->image) }}" alt="" width="200px">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.New Banner')}}</label>
                                                                <input type="file" name="banner_image" class="form-control-file">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title One')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_one" class="form-control" value="{{ $megaMenuBanner?->title_one }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title Two')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_two" class="form-control" value="{{ $megaMenuBanner?->title_two }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Category Link')}} <span class="text-danger">*</span></label>
                                                                <select name="product_slug" id="" class="form-control select2">
                                                                    <option value="">{{__('admin.Select Product')}}</option>
                                                                    @foreach ($products as $product)
                                                                        <option {{ $megaMenuBanner?->product_slug == $product->slug ? 'selected' : '' }} value="{{ $product->slug }}">{{ $product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>


                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                                                <select name="status" class="form-control">
                                                                    <option {{ $megaMenuBanner?->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                                                    <option {{ $megaMenuBanner?->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                                                </select>
                                                            </div>

                                                        </div>


                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
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
@endsection
