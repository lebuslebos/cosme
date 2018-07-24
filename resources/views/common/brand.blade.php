{{--品牌信息：用于品牌详情、搜索页面--}}
<div class="media align-items-center">
    {{--品牌图片--}}
    @if(Route::currentRouteName()=='brands.show')
        <img class="mr-md-3 product-size" src="{{Storage::url('brands')}}/{{$brand->id}}.jpg!product"
             alt="{{$brand->name}}">
    @else
        <a href="{{route('brands.show',[$brand])}}">
            <img class="mr-md-1 product-size" src="{{Storage::url('brands')}}/{{$brand->id}}.jpg!product"
                 alt="{{$brand->name}}">
        </a>
    @endif

    <div class="media-body">
        {{--品牌名+国家--}}
        <div class="d-flex align-items-end border-dotted pl-md-2 pb-1 pb-md-2">
            @if(Route::currentRouteName()=='brands.show')
                <div>
                    <span class="text-brown{{$is_phone?' text-normal':' h3 mb-0'}}">{{$brand->name}}</span>
                    <span class="text-main font-italic{{$is_phone?'':' text-xl'}}">{{$brand->common_name}}</span>
                </div>
                {{--<h3 class="text-brown mb-0 mr-2 mr-md-3">{{$brand->name}}
                    <small class="text-main font-italic">{{$brand->common_name}}</small>
                </h3>--}}
            @else
                <a href="{{route('brands.show',[$brand])}}">
                    <span class="text-brown{{$is_phone?' text-normal':' h3 mb-0'}}">{{$brand->name}}</span>
                    <span class="text-main font-italic{{$is_phone?'':' text-xl'}}">{{$brand->common_name}}</span>
                </a>

            @endif

            <div class="ml-auto ml-md-3 mb-md-1">
                <img src="{{Storage::url('countries')}}/{{$brand->country_id}}.jpg"
                     class="border tiny-size country-img d-inline-block" alt="{{$brand->country}}">
                <span class="text-muted align-text-top">{{$brand->country}}</span>
            </div>
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
            <div class="buy-percent">
                平均回购率：{{$brand->buys_count==0 ? 0 : round(100*$brand->buys_count/$brand->reviews_count)}}%
            </div>
        </div>
    </div>
</div>
