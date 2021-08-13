<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>商品一覽</title>
        <link href="{{ asset('css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    </head>
    <body>
        <br />
        <div class="row mb-3">
            <div class="col-1">
            </div>
            <div class="col-10">
                <img src="{{ asset('images/banner.jpg') }}" style="width: 100%;" class="img-fluid" alt="Laravel conf">
            </div>
            <div class="col-1">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-1">
            </div>
            <div class="col-2">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-primary">Category</li>
                    <li class="list-group-item list-group-item-light">An item</li>
                    <li class="list-group-item list-group-item-light">A second item</li>
                    <li class="list-group-item list-group-item-light">A third item</li>
                    <li class="list-group-item list-group-item-light">A fourth item</li>
                    <li class="list-group-item list-group-item-light">And a fifth one</li>
                </ul>
            </div>
            <div class="col-8">
                <!-- goods card sample -->
                <div class="col-4 mb-3 goods-card-sample" id="goods-card-sample" style="display: none;">
                    <div class="card" style="width: 100%;">
                        <img src="{{ asset('images/coming_soon.jpg') }}" class="card-img-top goods-image">
                        <div class="card-body">
                            <h5 class="card-title goods-title">Goods Name</h5>
                            <p class="card-text goods-price">$81,000</p>
                            <button class="btn btn-primary add-to-cart">Add to cart</button>
                        </div>
                    </div>
                </div>
                <!-- end of goods card sample -->
                <div class="row" id="goods-list">
                </div>
            </div>
            <div class="col-1">
            </div>
        </div>
    </body>

    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/helper.js') }}"></script>
    <script>
        var baseUrl = "{{ url('/') }}"
    </script>
    <script src="{{ asset('js/goods_list.js') }}"></script>
</html>
