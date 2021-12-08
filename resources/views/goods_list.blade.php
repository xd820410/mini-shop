@extends('layouts.app')

@section('title')
{{ config('app.name', 'Laravel') }}
@endsection

@section('content')
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
                <img src="{{ asset('images/coming_soon.jpg') }}" class="card-img-top goods-image" onError="this.src='/images/coming_soon.jpg';" style="width: 100%;">
                <div class="card-img-overlay" style="height: 50%;">
                    <small style="color: #084298; background-color: #cfe2ff; font-weight: bold; display: none;" class="goods-discount-title rounded"></small>
                </div>
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
@endsection

@section('custom_js')
<script src="{{ asset('js/goods_list.js') }}"></script>
@endsection