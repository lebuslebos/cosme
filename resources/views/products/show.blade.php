@extends('layouts.app')
@section('title',$product->name)

@section('content')
    {{--左边部分--}}
    <div class="{{$is_tablet ? 'offset-md-1 col-md-10' : 'col-md-7'}}">
        <div class="media align-items-center mb-3 mb-md-0">
            {{--商品的左边部分（移动端和pc端图片大小不一样）--}}
            @if($is_phone)
                <img src="{{Storage::url('products')}}/{{$product->id}}.jpg!product" class="product-size"
                     alt="{{$product->name}}">
            @else
                <img class="mr-3 product-l-size" src="{{Storage::url('products')}}/{{$product->id}}.jpg!product.l"
                     alt="{{$product->name}}">
            @endif
            {{--商品的右边部分--}}
            <div class="media-body">
                {{--品牌名--}}
                <div class="d-flex align-items-baseline pb-1 pb-md-2 pl-md-2 border-dotted">
                    <a class="text-secondary" href="{{route('brands.show',['id'=>$product->brand->id])}}">
                        {{$product->brand->name}} <span
                                class="font-italic text-tiny">{{$product->brand->common_name}}</span>
                    </a>
                    {{--移动端--}}
                    @if($is_mobile)
                        <button type="button" class="ml-auto btn btn-pink rounded"
                                data-toggle="modal"
                                data-target="#productPageRanking">{{$cat->name}}排行榜
                        </button>
                    @endif
                </div>
                {{--商品名+分类名--}}
                <div class="d-flex align-items-baseline py-2 pl-md-2 border-dotted">
                    <div class="text-main{{$is_phone?' text-normal':' h3 mb-0'}}">{{$product->name}}</div>&nbsp;
                    <div class="text-brown{{$is_phone?'':' text-xl'}}">{{$product->nick_name}}</div>&nbsp;
                    <div class=""><a href="{{route('cats.show',['id'=>$cat->id])}}"
                                                 class="text-secondary">[ {{$cat->name}} ]</a></div>
                </div>
                {{--商品评分+点评数--}}
                <div class="d-flex align-items-center py-1 pl-md-2 border-dotted">
                    <product-rate :rate="{{$product->rate}}" class="text-xl"></product-rate>

                    <div class="text-easy bg-easy d-inline-block pr-1 ml-1 ml-md-3">
                        @if($is_phone)
                            <span class="text-brown">{{$product->reviews_count}}</span>人用过
                        @else
                            有&nbsp;<span class="text-brown">{{$product->reviews_count}}</span>&nbsp;位小仙女用过了
                        @endif
                    </div>


                </div>
                {{--商品价格--}}
                <ul class="list-inline text-muted mb-0 py-2 pl-md-2 border-dotted">
                    @forelse($product->prices as $price)
                        <li class="list-inline-item">
                            <span class="text-brown">{{$price->volume ? $price->volume.' · ' : ''}}</span>
                            {{$price->price}}元
                        </li>
                    @empty
                        <li class="list-inline-item">
                            <span>暂无官方报价</span>
                        </li>
                    @endforelse
                </ul>

            </div>

        </div>

        {{--我要点评--}}
        @if(filled($my_review=optional(Auth::user())->my_review($product->id)))
            {{--用户已登录状态+用户已点评过--->就一种很少的情况，所以先判断--}}
            <review :is-login="true" :product-id="{{$product->id}}" :review="{{$my_review}}"></review>
        @else
            {{--用户已登录但未点+用户未登录（包括未点和已点）--->共三种情况--}}
            <review :is-login="@json(Auth::check())" :product-id="{{$product->id}}"></review>
        @endif



        {{--商品色号--}}
        @if(filled($product->colors))
            <product-color :colors="{{$product->colors}}" class="mt-4"></product-color>
        @endif

        @if($product->reviews_count>0)
            {{--点评分布--}}
            <div class="text-muted mt-3">点评分布</div>
            <div class="border-top py-3 mt-1">

                <buy-progress :buy-num="{{round(100*$product->buys_count/$product->reviews_count)}}"></buy-progress>
                <shop-progress :shop-nums="@json($shop_datas)"></shop-progress>

                @isset($skin_datas)
                    <skin-progress :skin-nums="@json($skin_datas)"></skin-progress>
                @endisset
            </div>

            {{--所有点评--}}
            <div class="text-muted mt-3" id="product-reviews">有内容的点评优先展示({{$product->reviews_count}})</div>
            <ul class="list-unstyled mt-1">
                @foreach($reviews as $review)
                    <li class="py-3 py-md-4 border-top">
                        @include('common.review')
                    </li>
                @endforeach
            </ul>

            <div class="d-flex justify-content-center pt-5">{{$reviews->fragment('product-reviews')->links()}}</div>
        @endif
    </div>


    {{--移动端ranking的modal--}}
    @if($is_mobile)
        <div class="modal fade" id="productPageRanking" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-slide" role="document">
                {{--这里改border和radius--}}
                <div class="modal-content rounded-0 border-0">
                    <div class="modal-body">
                        @include('products.ranking')
                    </div>
                </div>
            </div>
        </div>
    @else
        {{--右边部分(pc端)--}}
        <div class="offset-md-1 col-md-4 pl-md-5">
            @include('products.ranking')
        </div>
    @endif
@endsection
