@extends('manager.skeleton')

@section('title', '商品編輯')

@section('content')
<div class="row mb-3">
    <div class="col-1">
    </div>
    <div class="col-2">
        <ul class="list-group">
            <li class="list-group-item list-group-item-primary">Goods manage</li>
            <li class="list-group-item list-group-item-light">manager2</li>
            <li class="list-group-item list-group-item-light">manager3</li>
            <li class="list-group-item list-group-item-light">manager4</li>
            <li class="list-group-item list-group-item-light">manager5</li>
        </ul>
    </div>
    <div class="col-8">
        <div class="row">
            <div class="col-12">
                <div class="alert" role="alert" id="alert-block" style="display: none;">
                </div>
            </div>
        </div>

        <!-- goods card sample -->
        <div class="row mb-3">
            <div class="col-12 mb-3 goods-card-sample" id="goods-card-sample" style="display: none;">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">
                                <img src="{{ asset('images/coming_soon.jpg') }}" class="card-img-top goods-image" style="width: 100%;" onError="this.src='/images/coming_soon.jpg';">
                            </div>
                            <div class="col-10">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Name: </span>
                                    <input type="text" class="form-control name" placeholder="Goods' name">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Description: </span>
                                    <textarea class="form-control description"></textarea>
                                </div>
                                <div class="input-group mb-3">
                                    <label class="input-group-text">Image: </label>
                                    <input type="file" class="form-control image">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text">Price: </span>
                                    <input type="text" class="form-control price" placeholder="777">
                                    <button class="btn btn-outline-primary update" type="button">Update</button>
                                    <button class="btn btn-outline-primary delete" type="button">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of goods card sample -->

        <div class="row mb-3">
            <div class="col-12 mb-3 goods-card-sample">
                <div class="card" style="width: 100%;">
                    <div class="card-header">
                        New goods
                    </div>
                    <div class="card-body">
                        <div class="input-group mb-3">
                            <span class="input-group-text">Name: </span>
                            <input type="text" class="form-control" id="new-name" placeholder="Goods' name">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Description: </span>
                            <textarea class="form-control" id="new-description"></textarea>
                        </div>
                        <div class="input-group mb-3">
                            <label class="input-group-text">Image: </label>
                            <input type="file" class="form-control" id="new-image">
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">Price: </span>
                            <input type="text" class="form-control" id="new-price" placeholder="777">
                            <button class="btn btn-outline-primary" id="create-goods" type="button">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3" id="goods-list">
        </div>
    </div>
    <div class="col-1">
    </div>
</div>
@endsection

@section('custom_js')
<script src="{{ asset('js/manager/goods_manage.js') }}"></script>
@endsection