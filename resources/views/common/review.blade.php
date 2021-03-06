{{--展示的点评--用于首页和商品页--}}

{{--点评上半部分（用户信息+简易点评部分）--}}
<div class="media">
    {{--用户头像，并分游客和用户处理--}}
    <div class="align-self-center mr-2">
        @if(!!$review->user_id)
            <a href="{{route('users.show',[$review->user])}}" target="_blank">
                <img src="{{$review->user->avatar}}!brand" alt="" class="rounded avatar-size">
            </a>
        @else
            <img class="rounded avatar-size" src="{{$review->user->avatar}}" alt="">
        @endif
    </div>
    <div class="media-body">
        {{--用户名字+肤质+点评数，并分游客和用户处理--}}
        <div class="d-flex align-items-end mb-1">
            @if(!!$review->user_id)
                <div class="text-truncate name-truncate">
                    <a href="{{route('users.show',[$review->user])}}" class="text-main" target="_blank">
                        {{$review->user->name}}
                    </a>
                </div>
                @if($review->user->reviews_count>=5 && $review->user->reviews_count<10)
                    <img class="fav-size hover-help align-self-center ml-1 ml-md-3"
                         src="{{Storage::url('icons/fav-5.gif')}}"
                         alt="" data-toggle="tooltip" data-original-title="用过5-10个化妆品">
                @elseif($review->user->reviews_count>=10 && $review->user->reviews_count<25)
                    <img class="fav-size hover-help align-self-center ml-1 ml-md-3"
                         src="{{Storage::url('icons/fav-10.gif')}}"
                         alt="" data-toggle="tooltip" data-original-title="用过10-25个化妆品">
                @elseif($review->user->reviews_count>=25 && $review->user->reviews_count<50)
                    <img class="fav-size hover-help align-self-center ml-1 ml-md-3"
                         src="{{Storage::url('icons/fav-25.gif')}}"
                         alt="" data-toggle="tooltip" data-original-title="用过25-50个化妆品">
                @elseif($review->user->reviews_count>=50 && $review->user->reviews_count<100)
                    <img class="fav-size hover-help align-self-center ml-1 ml-md-3"
                         src="{{Storage::url('icons/fav-50.gif')}}"
                         alt="" data-toggle="tooltip" data-original-title="用过50-100个化妆品">
                @elseif($review->user->reviews_count>=100)
                    <img class="fav-size hover-help align-self-center ml-1 ml-md-3"
                         src="{{Storage::url('icons/fav-100.gif')}}"
                         alt="" data-toggle="tooltip" data-original-title="用过100个以上的化妆品">
                @endif
                <div class="text-muted text-tiny mx-2 mx-md-3">{{$review->user->skin}}肤质</div>
                <div class="text-muted text-tiny">用过{{$review->user->reviews_count}}个化妆品</div>
            @else
                <span class="text-main">{{$review->user->name}}</span>
            @endif
        </div>
        {{--评分+回购+购买场所--}}
        <div class="d-flex align-items-center">
            <review-rate :rate="{{$review->rate}}"></review-rate>
            <review-buy class="ml-1 mr-2 mx-md-3" :buy="{{$review->buy}}"></review-buy>
            <review-shop class="mr-2 mr-md-3" :shop="{{$review->shop}}"></review-shop>
            <review-date :date=@json($review->updated_at)></review-date>
        </div>

    </div>
</div>


{{--点评的下半部分（文字点评+图片+点赞点踩）--}}
<div class="text-brown review-text my-2 my-md-3{{Route::currentRouteName()=='products.show' || $is_phone ? ' ml-47' : ''}}">{!!nl2br(e($review->body))!!}</div>

@if(filled(json_decode($review->imgs)))
    <div class="d-flex {{Route::currentRouteName()=='products.show' || $is_phone ? ' ml-47' : ''}}">
        @foreach(json_decode($review->imgs) as $img)
            <review-img img="{{$img}}" class="mb-2 mr-2"></review-img>
        @endforeach
    </div>
@endif

@empty(!$review->user_id)
    <div class="d-flex mt-md-auto">
        <vote :review="{{$review->id}}" :user="{{$review->user_id}}"
              :likes="{{$review->likes_count}}" :hates="{{$review->hates_count}}"
              class="ml-auto pt-2"></vote>
    </div>
@endempty

{{--手机时的商品信息--}}
@if(Route::currentRouteName()=='home' && $is_phone)
    <a href="{{route('products.show',[$review->product])}}"
       class="phone-product ml-47 d-flex align-items-center bg-light py-2 pl-1 mt-3">
        <img class="product-s-size mr-2" src="{{Storage::url('products')}}/{{$review->product_id}}.jpg!cosme"
             alt="{{$review->product->name}}">
        <div>
            <div class="text-muted">{{$review->brand->name}}</div>
            <div class="text-main">{{$review->product->name}}</div>
            <div>
                <product-rate :rate="{{$review->product->rate}}"></product-rate>
            </div>
            <div class="text-muted">
                <span>有{{$review->product->reviews_count}}人用过 其中{{$review->product->buys_count==0 ? 0 : round(100*$review->product->buys_count/$review->product->reviews_count)}}%的人会再次购买</span>
            </div>
        </div>
    </a>
@endif
