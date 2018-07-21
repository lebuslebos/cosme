{{--匹配用户--}}

{{--与我最匹配的用户（点评数越多，匹配越精准），我会回购的商品中，他们也会回购。
        如果匹配度100%，则说明我会回购的商品，她都用过，并且是全部会回购。
        你可以进她的主页看看她用过的其他商品以做参考。--}}
<div class="buy-percent">
    和我最相似的人（点评越多，则匹配越准确）
</div>
<div class="text-muted text-tiny border-bottom mt-2">
    ( {{date('Y年n月j日')}}凌晨{{config('common.ranking_updated_at')}}点更新 )
</div>
@if($user->buys_count==0)
    <div class="text-muted bg-light text-center py-5">至少有一篇会回购的点评才能匹配</div>
@elseif(empty($match_count))
    {{--未匹配到和我相同的用户，我用的好商品别人还都没用过或者别人都不觉得好。请试着多写几篇用的好的商品的点评（会回购商品点评）--}}
    <div class="text-muted bg-light text-center py-5">我用过的化妆品都比较小众，未能匹配</div>
@else
    <div class="media py-3 mb-3 align-items-center">
        {{--皇冠图--}}
        <img src="{{Storage::url('icons/crown1.jpg')}}!crown" class="mr-2 crown-size" alt="最匹配">

        {{--用户头像--}}
        <a href="{{route('users.show',[$match_user])}}" target="_blank">
            <img class="mr-2 rounded product-s-size" src="{{$match_user->avatar}}!product.s" alt="{{$match_user->name}}">
        </a>
        {{--用户信息--}}
        <div class="media-body">
            <div><a href="{{route('users.show',[$match_user])}}"
                    class="text-main" target="_blank">
                    {{$match_user->name}}
                </a></div>
            <div class="text-muted text-tiny">{{$match_user->skin}}肤质</div>

            <div class="text-muted text-tiny">用过{{$match_user->reviews_count}}个化妆品</div>
            <div class="buy-percent text-tiny">
                匹配度{{round(100*$match_count/$user->buys_count,1)}}%
            </div>

        </div>
    </div>
@endif
