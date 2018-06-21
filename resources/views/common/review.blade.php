{{--展示的点评--用于首页和商品页--}}

{{--点评上半部分（用户信息+简易点评部分）--}}
<div class="media">
    {{--用户头像，并分游客和用户处理--}}
    <div class="align-self-center mr-2">
        @if(!!$review->user_id)
            <a href="{{route('users.show',[$review->user])}}">
                <img src="{{$review->user->avatar}}!avatar" alt="头像" class="rounded avatar-size">
            </a>
        @else
            <img class="rounded avatar-size" src="{{$review->user->avatar}}!avatar" alt="头像">
        @endif
    </div>
    <div class="media-body">
        {{--用户名字+肤质+点评数，并分游客和用户处理--}}
        <div class="d-flex align-items-end mb-1">
            @if(!!$review->user_id)
                <div class="text-truncate name-truncate">
                    <a href="{{route('users.show',[$review->user])}}" class="text-main">
                        {{$review->user->name}}
                    </a>
                </div>
                @if($review->user->reviews_count>=5 && $review->user->reviews_count<10)
                    <img class="fav-size hover-help align-self-center ml-1" src="{{config('app.url')}}/icons/fav-5.gif"
                         alt="花" data-toggle="tooltip" data-original-title="用过5-10个化妆品">
                @elseif($review->user->reviews_count>=10 && $review->user->reviews_count<25)
                    <img class="fav-size hover-help align-self-center ml-1" src="{{config('app.url')}}/icons/fav-10.gif"
                         alt="花" data-toggle="tooltip" data-original-title="用过10-25个化妆品">
                @elseif($review->user->reviews_count>=25 && $review->user->reviews_count<50)
                    <img class="fav-size hover-help align-self-center ml-1" src="{{config('app.url')}}/icons/fav-25.gif"
                         alt="花" data-toggle="tooltip" data-original-title="用过25-50个化妆品">
                @elseif($review->user->reviews_count>=50 && $review->user->reviews_count<100)
                    <img class="fav-size hover-help align-self-center ml-1" src="{{config('app.url')}}/icons/fav-50.gif"
                         alt="花" data-toggle="tooltip" data-original-title="用过50-100个化妆品">
                @elseif($review->user->reviews_count>=100)
                    <img class="fav-size hover-help align-self-center ml-1" src="{{config('app.url')}}/icons/fav-100.gif"
                         alt="花" data-toggle="tooltip" data-original-title="用过100个以上的化妆品">
                @endif
                <div class="text-muted text-tiny mx-2">{{$review->user->skin}}皮肤</div>
                <div class="text-muted text-tiny">用过了{{$review->user->reviews_count}}个化妆品</div>
            @else
                <span class="text-main">{{$review->user->name}}</span>
            @endif
        </div>
        {{--评分+回购+购买场所--}}
        <div class="d-flex align-items-center">
            <review-rate :rate="{{$review->rate}}"></review-rate>
            {{--<div class="d-flex align-items-center mt-1 mt-md-0">--}}
            <review-buy class="ml-1 mr-2 ml-md-2" :buy="{{$review->buy}}"></review-buy>
            <review-shop class="mr-2" :shop="{{$review->shop}}"></review-shop>
            <review-date :date=@json($review->updated_at)></review-date>
            {{--</div>--}}
            {{--<div class="badge-easy">{{$review->updated_at}}</div>--}}

            {{--<span class="badge badge-dark">买的{{$shop[$review->shop]}}的</span>--}}
        </div>

    </div>
</div>


{{--点评的下半部分（文字点评+图片+点赞点踩）--}}
<div class="text-brown my-2">{!!nl2br(e($review->body))!!}</div>

@if(filled(json_decode($review->imgs)))
    <div class="d-flex">
        @foreach(json_decode($review->imgs) as $img)
            <review-img img="{{$img}}" class="mb-2 mr-2"></review-img>
        @endforeach
    </div>
@endif

<div class="d-flex align-items-center justify-content-between mt-md-auto">
    @empty(!$review->user_id)
        <vote :review="{{$review->id}}"
              :user="{{$review->user_id}}"
              :likes="{{$review->likes_count}}"
              :hates="{{$review->hates_count}}"
              class="pt-2"></vote>
    @endempty

    @if(Route::currentRouteName()=='home' && $is_phone)
        {{--手机时的商品信息--}}
        <div class="pt-2">
            <a class="btn btn-main rounded"
               href="{{route('products.show',[$review->product])}}">{{$review->brand->name}}
                -{{$review->product->name}}</a>
        </div>
    @endif
</div>

