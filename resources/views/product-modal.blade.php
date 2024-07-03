<section class="product_popup_modal">
    <div class="modal fade" id="productModalView-{{ $product->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="far fa-times"></i></button>
                    <div class="row">
                        <div class="col-xl-6 col-12 col-sm-10 col-md-8 col-lg-6 m-auto display">
                            <div class="wsus__quick_view_img">
                                @if ($product->video_link)
                                    @php
                                        $video_id = explode('=', $product->video_link);
                                    @endphp
                                    <a class="venobox wsus__pro_det_video" data-autoplay="true" data-vbtype="video"
                                        href="https://youtu.be/{{ $video_id[1] }}">
                                        <i class="fas fa-play"></i>
                                    </a>
                                @endif

                                <div class="row modal_slider">
                                    @foreach ($product->gallery as $image)
                                        <div class="col-xl-12">
                                            <div class="modal_slider_img">
                                                <img src="{{ asset($image->image) }}" alt="product"
                                                    class="img-fluid w-100">
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-12 col-sm-12 col-md-12 col-lg-6">
                            @include('modal-content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
