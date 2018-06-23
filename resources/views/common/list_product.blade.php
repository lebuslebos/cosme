{{--商品列表（单个商品）--品牌页列表，分类页列表，搜索页列表，个人页--}}

{{--商品-->左边为图，右边为信息--}}
<div class="media align-items-center mb-2">

    {{--商品的左边部分--}}
    <a href="{{route('products.show',[$product])}}" target="{{Route::currentRouteName()=='users.show'?'_blank':''}}">
        <img class="mr-md-3 product-size" src="{{config('app.url')}}/products/{{$product->id}}.jpg!product"
             alt="{{$product->name}}">
    </a>
    {{--商品的右边部分--}}
    <div class="media-body">
        {{--品牌名--}}
        <div>
            <a class="text-muted" target="{{Route::currentRouteName()=='brands.show'?'':'_blank'}}"
               href="{{Route::currentRouteName()=='brands.show'?'#app':route('brands.show',[$brand])}}">
                {{$brand->name}} {{$brand->common_name}}
            </a>
        </div>
        {{--商品名+分类名--}}
        <div>
            <a href="{{route('products.show',[$product])}}" target="{{Route::currentRouteName()=='users.show'?'_blank':''}}" class="text-main">
                {{$product->name}} {{$product->nick_name}}
            </a>
            &nbsp;
            <a class="text-muted text-tiny" target="{{Route::currentRouteName()=='cats.show'?'':'_blank'}}"
               href="{{Route::currentRouteName()=='cats.show'?'#app':route('cats.show',[$cat])}}">
                [ {{$cat->name}} ]
            </a>
        </div>
        {{--商品评分+点评数--}}
        <div class="d-flex align-items-center">
            <product-rate class="mr-2 mr-md-3 text-normal" :rate="{{$product->rate}}"></product-rate>
            <div class="text-muted text-tiny">
                有<span class="text-main">{{$product->reviews_count}}</span>人用过
            </div>
        </div>
        {{--商品价格--}}
        <ul class="list-inline text-muted mb-0">
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
        <div class="text-easy bg-easy text-tiny d-inline-block px-1 mt-1">
            {{$product->buys_count==0 ? 0 : round(100*$product->buys_count/$product->reviews_count)}}%的人会再次购买
        </div>

    </div>
</div>



