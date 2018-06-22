{{--品牌信息：用于品牌详情、搜索页面--}}
<div class="media align-items-center">
    {{--品牌图片--}}
    @if(Route::currentRouteName()=='brands.show')
        <img class="mr-md-3 product-size" src="{{config('app.url')}}/brands/{{$brand->id}}.jpg!product"
             alt="{{$brand->name}}">
    @else
        <a href="{{route('brands.show',[$brand])}}">
            <img class="mr-md-1 product-size" src="{{config('app.url')}}/brands/{{$brand->id}}.jpg!product"
                 alt="{{$brand->name}}">
        </a>
    @endif

    <div class="media-body">
        {{--品牌名+国家--}}
        <div class="d-flex align-items-end border-dotted pl-md-2 pb-1 pb-md-2">
            @if(Route::currentRouteName()=='brands.show')
                <h3 class="text-brown mb-0 mr-2 mr-md-3">{{$brand->name}}
                    <small class="text-main font-italic">{{$brand->common_name}}</small>
                </h3>
            @else
                <a href="{{route('brands.show',[$brand])}}">
                    <h3 class="text-brown mb-0 mr-2 mr-md-3">{{$brand->name}}
                        <small class="text-main font-italic">{{$brand->common_name}}</small>
                    </h3>
                </a>
            @endif

            <img src="{{config('app.url')}}/countries/{{$brand->country_id}}.jpg!tiny"
                 class="border tiny-size country-img mr-1" alt="{{$brand->country}}"
                 style="margin-bottom: 3px">
            <div class="text-muted">{{$brand->country}}</div>
        </div>
        {{--品牌官网--}}
        <div class="border-dotted pl-md-2 py-2">
            @if(filled($brand->official_website))
                <a href="//{{$brand->official_website}}" target="_blank"
                   class="text-secondary font-italic text-normal">
                    {{$brand->official_website}}
                </a>
            @else
                <span class="text-muted">没有中文官网</span>
            @endif
        </div>
        {{--品牌统计--}}
        <div class="border-dotted pl-md-2 py-2">
            <span class="text-muted">总点评数:</span>
            <span class="text-brown mr-2">{{$brand->reviews_count}}</span>
            @if(Route::currentRouteName()=='brands.show')
            <span class="text-muted">总商品数:</span>
            <span class="text-brown">{{$products->total()}}</span>
            @endif
        </div>
        <div class="border-dotted pl-md-2 py-2">
            <div class="text-easy bg-easy d-inline-block px-1">
                平均回购率：{{$brand->buys_count==0 ? 0 : round(100*$brand->buys_count/$brand->reviews_count)}}%
            </div>
        </div>
    </div>
</div>
