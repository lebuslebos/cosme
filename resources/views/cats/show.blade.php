@extends('layouts.app')
@section('title','分类页')

@section('content')

    {{--移动端allCats的modal--}}
    @if($is_mobile)
    <div class="modal fade" id="allCats" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-slide w-50" role="document">
            <div class="modal-content rounded-0 border-0">
                <div class="modal-body">
                    @include('cats.index_mobile')
                </div>
            </div>
        </div>
    </div>
    @else
    {{--pc端全部分类的导航--}}
    <div class="col-md-2 pr-md-5">
        @include('cats.index')
    </div>
    @endif

    <div class="{{$is_tablet ? 'offset-md-1 col-md-10' : 'col-md-6'}}" id="cat-products">
        {{--分类的简介--}}
        @include('common.cat')

        {{--这个分类的商品--}}
        <div class="d-flex align-items-baseline text-muted mt-4 pt-2" >
            <div>按照点评数排序({{$products->total()}})</div>
            {{--移动端--}}
            @if($is_mobile)
            <button type="button" class="btn btn-pink rounded ml-auto"
                    data-toggle="modal"
                    data-target="#catPageRanking">排行榜
            </button>
            @endif
        </div>
        <ul class="list-unstyled mt-1 mt-md-2">
            @foreach($products as $product)
                <li class="py-3 border-top">
                    @include('common.list_product',['brand'=>$product->brand])
                    @include('common.list_review')
                </li>
            @endforeach
        </ul>

        <div class="d-flex justify-content-center pt-5">{{$products->fragment('cat-products')->links()}}</div>
    </div>

    {{--移动端ranking的modal--}}
    @if($is_mobile)
    <div class="modal fade" id="catPageRanking" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-slide" role="document">
            <div class="modal-content rounded-0 border-0">
                <div class="modal-body">
                    @include('cats.ranking')
                </div>
            </div>
        </div>
    </div>
    @else
    {{--右边部分（pc端）--}}
    <div class="col-md-4 pl-md-5">
        @include('cats.ranking')
    </div>
    @endif

@endsection
