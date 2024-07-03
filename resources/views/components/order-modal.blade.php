{{-- <div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="row">
                    <div class="col-xl-6 col-12 col-sm-10 col-md-8 col-lg-6 m-auto display">
                        <div class="wsus__quick_view_img">

                            <div class="row modal_slider">
                                hello
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-12 col-sm-12 col-md-12 col-lg-6">
                        <div class="wsus__pro_details_text">

                            hello
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}



<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content modal_wrapper">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    অর্ডার করার জন্য ফর্মটি পূরণ করুন...
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="order.php" method="post">
                    <!-- Product Items -->
                    <div class="mb-3">
                        <div class="row">
                            <div class="wsus__order_wrapper">
                                <div class="col-md-2">
                                    <div class="wsus__order_img">
                                        <img src="images/product1.jpg" alt="Product 1" class="img-fluid" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p>Electronics Black Wrist Watch</p>
                                </div>
                                <div class="col-md-2">
                                    <div class="wsus__quentity_order">
                                        <form class="select_number">
                                            <input class="number_area" type="text" min="1" max="100"
                                                value="1" />
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <p>$50.00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Delivery Cost -->
                    <div class="mb-3">
                        <p class="mb-2">ডেলিভারি চার্জ সিলেক্ট করুন..</p>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="delivery_cost_free" class="form-label wsus__delivery_fee">
                                    <span>
                                        <input type="radio" id="delivery_cost_free" name="delivery_cost"
                                            value="0" />
                                        Free Delivery
                                    </span>
                                    <span>00.00</span>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="delivery_cost" class="form-label wsus__delivery_fee">
                                    <span>
                                        <input type="radio" id="delivery_cost_inside" name="delivery_cost"
                                            value="0" checked />
                                        Inside Dhaka
                                    </span>
                                    <span>70.00</span>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="delivery_cost_outside" class="form-label wsus__delivery_fee">
                                    <span>
                                        <input type="radio" id="delivery_cost_outside" name="delivery_cost"
                                            value="50" />
                                        Outside Dhaka
                                    </span>
                                    <span>120.00</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Total and sub total -->
                    <div class="mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="wsus__order_price wsus__order_price_bb">
                                            <h5>Sub Total</h5>
                                            <p>200.00</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="wsus__order_price">
                                            <h5>Total</h5>
                                            <p>200.00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name <span>*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Your Name" required />
                            </div>
                            <div class="col-md-6">
                                <label for="mobile_number" class="form-label">Mobile Number <span>*</span></label>
                                <input type="text" class="form-control" id="mobile_number" name="mobile_number"
                                    placeholder="01..." required />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="district" class="form-label">District <span>*</span></label>
                                <select name="" id="" class="form-control">
                                    <option value="">Select District</option>
                                    <option value="Dhaka">Dhaka</option>
                                    <option value="Chittagong">Chittagong</option>
                                    <option value="Rajshahi">Rajshahi</option>
                                    <option value="Sylhet">Sylhet</option>
                                    <option value="Mymensingh">Mymensingh</option>
                                    <option value="Barisal">Barisal</option>
                                    <option value="Rangpur">Rangpur</option>
                                    <option value="Kushtia">Kushtia</option>
                                    <option value="Comilla">Comilla</option>
                                    <option value="Dhulikhel">Dhulikhel</option>
                                    <option value="Jamalpur">Jamalpur</option>
                                    <option value="Munshiganj">Munshiganj</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="thana" class="form-label">Thana <span>*</span></label>
                                <select name="" id="" class="form-control">
                                    <option value="">Select Thana</option>
                                    <option value="Barguna">Barguna</option>
                                    <option value="Barisal">Barisal</option>
                                    <option value="Bhola">Bhola</option>
                                    <option value="Jhalokati">Jhalokati</option>
                                    <option value="Patuakhali">Patuakhali</option>
                                    <option value="Pirojpur">Pirojpur</option>
                                    <option value="Rajbari">Rajbari</option>
                                    <option value="Shariatpur">Shariatpur</option>
                                    <option value="Tangail">Tangail</option>
                                    <option value="Thakurgaon">Thakurgaon</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="address" class="form-label">Address <span>*</span></label>
                            <textarea class="form-control" id="address" name="address" required rows="1"
                                placeholder="Enter your address..."></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="order_note" class="form-label">Order Note</label>
                            <textarea class="form-control" id="order_note" name="order_note" rows="3"
                                placeholder="Enter any additional notes..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary">
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>
