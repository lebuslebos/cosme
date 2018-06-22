{{--匹配用户--}}

{{--与我最匹配的用户（点评数越多，匹配越精准），我会回购的商品中，他们也会回购。
        如果匹配度100%，则说明我会回购的商品，她都用过，并且是全部会回购。
        你可以进她的主页看看她用过的其他商品以做参考。--}}
<div class="text-easy bg-easy text-tiny px-1 mb-2">
    与我最匹配的用户（点评数越多，匹配越精准），我会回购的商品中，他们也会回购。
    如果匹配度100%，则说明我会回购的商品，她都用过，并且是全部会回购。
    你可以进她的主页看看她用过的其他商品以做参考。
</div>
<div class="text-muted text-tiny">
    ( {{date('Y年n月j日')}}凌晨{{config('common.ranking_updated_at')}}点更新 )
</div>
@if($user->buys_count==0)
    <div class="text-muted py-3 border-top">至少需要写一篇会回购的点评才有可能匹配</div>
@elseif(empty($match_count))
    <div class="text-muted py-3 border-top">未匹配到和我相同的用户，我用的好商品别人还都没用过或者别人都不觉得好。请试着多写几篇用的好的商品的点评（会回购商品点评）</div>
@else
    <div class="media py-3 mb-3 border-top align-items-center">
        {{--排序皇冠图--}}
        <img src="{{config('app.url')}}/icons/crown1.jpg!crown" class="mr-2 crown-size" alt="最匹配">

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
            <div class="text-muted text-tiny">{{$match_user->skin}}皮肤</div>

            <div class="text-muted text-tiny">用过了{{$match_user->reviews_count}}个化妆品</div>
            <div class="text-easy bg-easy text-tiny d-inline-block px-1">
                匹配度{{round(100*$match_count/$user->buys_count,1)}}%
            </div>

        </div>
    </div>


    {{--<ul class="list-unstyled">
        @foreach($match_users as $match_user)
            <li class="media py-3 border-top align-items-center">
                --}}{{--排序皇冠图--}}{{--
                @if($loop->iteration==1)
                    <img src="{{asset('img/rankings/icon_ranking_s_01.png')}}" class="mr-2" alt="最匹配"
                         title="最匹配的人">
                @elseif($loop->iteration==2)
                    <img src="{{asset('img/rankings/icon_ranking_s_02.png')}}" class="mr-2" alt="第二匹配"
                         title="第二匹配的人">
                @else
                    <img src="{{asset('img/rankings/icon_ranking_s_03.png')}}" class="mr-2" alt="第三匹配"
                         title="第三匹配的人">
                @endif
                --}}{{--用户头像--}}{{--
                <a href="{{route('users.show',[$match_user])}}">
                    <img class="mr-2 rounded" src="{{$match_user->avatar}}"
                         alt="{{$match_user->name}}的头像"
                         title="{{$match_user->name}}的头像"
                         style="width: 5rem;">
                </a>
                --}}{{--用户信息--}}{{--
                <div class="media-body">
                    <div><a href="{{route('users.show',[$match_user])}}"
                            class="text-main">
                            {{$match_user->name}}
                        </a></div>
                    <div class="text-muted text-tiny">{{$match_user->skin}}皮肤</div>

                    <div class="text-muted text-tiny">用过了{{$match_user->reviews_count}}个化妆品</div>
                    <div class="text-easy bg-easy text-tiny d-inline-block px-1">
                        匹配度{{round(100*($match_map[$match_user->id])/Auth::user()->buys_count,1)}}
                        %
                    </div>

                </div>
            </li>

        @endforeach
    </ul>--}}

@endif
