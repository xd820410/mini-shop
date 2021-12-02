<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="mini-cart-block" aria-controls="offcanvasRight" aria-labelledby="mini-cart-blockLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mini-cart-blockLabel">Items in your cart</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- cart item sample -->
        <div class="card mb-3 cart-item-card-sample" id="cart-item-card-sample" style="display: none;">
            <div class="row">
                <div class="col-4">
                    <img src="{{ asset('images/coming_soon.jpg') }}" class="cart-item-image img-fluid rounded-start" style="width: 100%;" onError="this.src='/images/coming_soon.jpg';">
                </div>
                <div class="col-8 clearfix">
                    <div class="card-body">
                        <button type="button" class="btn-close float-end delete-cart-item" aria-label="Close"></button>
                        <h5 class="card-title cart-item-title">Goods name</h5>
                        <p class="card-text cart-item-price">$81,000</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of cart item sample -->

        <div id="cart-item-list">
        </div>

        <div class="row" id="cart-total-block">
            <div class="col-12">
                <table style="width: 100%;">
                    <tr>
                        <th>Total: </th>
                        <th><span id="cart-total"></span></th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>