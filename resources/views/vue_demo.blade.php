@extends('layouts.app')

@section('title')
{{ config('app.name', 'Laravel') }}
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-1">
    </div>
    <div class="col-10">
        <img src="{{ asset('images/banner.jpg') }}" style="width: 100%;" class="img-fluid">
    </div>
    <div class="col-1">
    </div>
</div>
<div class="row mb-3">
    <div class="col-1">
    </div>
    <div class="col-10">
        <!-- goods card sample -->
        <!-- <div class="col-3 mb-3 goods-card-sample" id="goods-card-sample" style="display: none;">
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
        </div> -->
        <!-- end of goods card sample -->
        <div class="row" id="goods-list">
            <goods-card
                v-for="goods in goodsList"
                v-bind:key="goods.id"
                v-bind="goods"
            ></goods-card>
        </div>
    </div>
    <div class="col-1">
    </div>
</div>
@endsection

@section('custom_js')
<script src="https://cdn.jsdelivr.net/npm/vue@3.2.26/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{ asset('js/vue_demo.js') }}"></script>
<script src="{{ asset('js/navigation.js') }}"></script>
@endsection