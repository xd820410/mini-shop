<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="mini-cart-block" aria-controls="offcanvasRight" aria-labelledby="mini-cart-blockLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mini-cart-blockLabel">Items in your cart</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- discount sample -->
        <div class="row discount-sample" id="discount-sample" style="display: none;">
            <div class="col-8">
                &ensp;<button type="button" class="btn btn-outline-success" disabled>Event</button><span class="discount-title" style="font-style: italic; color: #dc3545; font-weight: bold;">折折折</span>
            </div>
            <div class="col-4">
                <span class="discount" style="color: #dc3545; font-weight: bold;">-$500</span>
            </div>
        </div>
        <!-- end of discount sample -->
        <!-- cart item sample -->
        <div class="card mb-3 cart-item-card-sample" id="cart-item-card-sample" style="display: none;">
            <div class="row">
                <div class="col-4">
                    <img src="{{ asset('images/coming_soon.jpg') }}" class="cart-item-image img-fluid rounded-start" style="width: 100%;" onError="this.src='/images/coming_soon.jpg';">
                </div>
                <div class="col-8 clearfix">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <button type="button" class="btn-close float-end delete-cart-item" aria-label="Close"></button>
                                <h5 class="card-title cart-item-title">Goods name</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="card-text cart-item-price-text" style="font-size: 0.9rem;"></p>
                            </div>
                            <div class="col-6">
                                <span class="cart-item-subtotal" style="font-size: 0.9rem;"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text">Quantity: </span>
                                    <div class="dropdown">
                                        <button class="btn btn-light dropdown-toggle quantity-dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">0</button>
                                        <ul class="dropdown-menu">
                                            @for ($i = 1; $i <= 20; $i++)
                                                <li><a class="dropdown-item" data-goods-id="" data-quantity="{{ $i }}" href="#">{{ $i }}</a></li>
                                            @endfor
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="discount-block">
                </div>
            </div>
        </div>
        <!-- end of cart item sample -->

        <div id="cart-item-list">
        </div>

        <div class="row" id="cart-total-block">
            <div class="col-8">
                <label>Total: </label>
            </div>
            <div class="col-4">
                <span id="cart-total"></span>
            </div>
        </div>
    </div>
</div>