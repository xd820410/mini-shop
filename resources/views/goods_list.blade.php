<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>商品一覽</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/font-awesome5/pro.min.css') }}" rel="stylesheet">
        <style>
            .fa-shopping-cart {
                display: inline-block;
                font-size: 20px;
                line-height: 40px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div>
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">

                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                                <li class="nav-item" id="mini-cart-icon">
                                    <span><i class="fas fa-shopping-cart"></i></span>
                                    <!-- style="display: flex; align-items: center;" &nbsp; -->
                                </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
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

        <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="mini-cart-block" aria-controls="offcanvasRight" aria-labelledby="mini-cart-blockLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="mini-cart-blockLabel">What you want</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <p>Try scrolling the rest of the page to see this option in action.</p>
            </div>
        </div>
    </body>

    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    <!-- <script src="{{ asset('js/bootstrap/popper.min.js') }}"></script> -->
    <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>
    <!-- <script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js') }}"></script> -->
    <script src="{{ asset('js/helper.js') }}"></script>
    <script>
        var baseUrl = "{{ url('/') }}"

    </script>
    <script src="{{ asset('js/goods_list.js') }}"></script>
</html>
