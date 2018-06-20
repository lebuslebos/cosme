@extends('layouts.app')
@section('title',$brand->name)

@section('content')
    {{--左边部分--}}
    <div class="{{$is_tablet ? 'offset-md-1 col-md-10' : 'col-md-7'}}" id="brand-products">
        {{--品牌--}}
        @include('common.brand')

        {{--这个品牌的商品--}}
        <div class="d-flex align-items-baseline mt-4 pt-2" id="brand-products">
            <div class="text-muted text-tiny">全部商品(按照点评数排序)({{$products->total()}})</div>
            {{--移动端--}}
            @if($is_mobile)
            <button type="button" class="ml-auto btn btn-pink rounded" data-toggle="modal"
                    data-target="#brandPageRanking">品牌排行榜
            </button>
            @endif
        </div>
        <ul class="list-unstyled mt-1 mt-md-2">
            @foreach($products as $product)
                <li class="py-3 border-top">
                    @include('common.list_product',['cat'=>$product->cat])
                    @include('common.list_review')
                </li>
            @endforeach
        </ul>

        <div class="d-flex justify-content-center pt-5">{{$products->fragment('brand-products')->links()}}</div>

    </div>


    {{--移动端ranking的modal--}}
    @if($is_mobile)
    <div class="modal fade" id="brandPageRanking" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-slide" role="document">
            <div class="modal-content rounded-0 border-0">
                {{--<div class="modal-header">
                    <h5 class="modal-title">排行榜</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>--}}
                <div class="modal-body">
                    @include('brands.ranking')
                </div>
            </div>
        </div>
    </div>
    @else
    {{--右边部分(pc端)--}}
    <div class="offset-md-1 col-md-4 pl-md-5">
        @include('brands.ranking')
    </div>
    @endif

@endsection
