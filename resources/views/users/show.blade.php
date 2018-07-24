@extends('layouts.app')
@section('title',$user->name)

@section('content')

    {{--左边部分--}}
    <div class="{{$is_tablet ? 'offset-md-1 col-md-10' : 'col-md-7'}}">

        {{--用户的基本信息--}}
        <div class="media mb-4">
            {{--用户头像--}}
            <avatar :can="@json(optional(Auth::user())->can('update',$user))"
                    avatar="{{$user->avatar}}" :user-id="{{$user->id}}"></avatar>

            {{--用户信息--}}
            <ul class="media-body list-unstyled mb-0">
                {{--昵称--}}
                <name :can="@json(optional(Auth::user())->can('update',$user))"
                      :reviews-count="{{$user->reviews_count}}" name="{{$user->name}}"
                      :user-id="{{$user->id}}"></name>

                {{--肤质--}}
                <skin :can="@json(optional(Auth::user())->can('update',$user))" skin="{{$user->skin}}"
                      :user-id="{{$user->id}}"></skin>

                {{--其他统计信息--}}
                <li class="border-dotted pl-2 py-2">
                    <img src="{{Storage::url('icons/double-star-size.jpg')}}" class="align-text-bottom double-star-size"
                         alt="花">
                    <span class="text-muted">收获的赞同数:</span>
                    <span class="text-brown">{{$user->likes_count}}</span>
                </li>
                <li class="border-dotted pl-2 py-2">
                    <img src="{{Storage::url('icons/double-star-size.jpg')}}" class="align-text-bottom double-star-size"
                         alt="花">
                    <span class="text-muted">用过的化妆品数:</span>
                    <span class="text-brown">{{$user->reviews_count}}</span>
                </li>

                <li class="border-dotted pl-2 py-2">
                    <img src="{{Storage::url('icons/double-star-size.jpg')}}" class="align-text-bottom double-star-size"
                         alt="花">
                    <span class="text-muted hover-help" data-toggle="tooltip"
                          data-original-title="若一样多则随机取一个">用的最多的分类:</span>
                    @if($user->reviews_count==0 )
                        <span class="text-brown">不知道呢</span>
                    @else
                        <span class="text-brown">{{$most_cat}}</span><span
                                class="text-muted text-tiny">（{{round($most_cat_count*100/$user->reviews_count)}}
                            %）</span>
                    @endif
                </li>
                <li class="border-dotted pl-2 py-2">
                    <img src="{{Storage::url('icons/double-star-size.jpg')}}" class="align-text-bottom double-star-size"
                         alt="花">
                    <span class="text-muted hover-help" data-toggle="tooltip"
                          data-original-title="若一样多则随机取一个">用的最多的品牌:</span>
                    @if($user->reviews_count==0)
                        <span class="text-brown">不知道呢</span>
                    @else
                        <span class="text-brown">{{$most_brand}}</span><span
                                class="text-muted text-tiny">（{{round($most_brand_count*100/$user->reviews_count)}}
                            %）</span>
                    @endif
                </li>

            </ul>
        </div>

        {{--所有点评--}}
        <div class="d-flex align-items-baseline text-muted pt-2" id="user-reviews">
            <div><i class="fa fa-circle-o text-normal font-weight-bold mr-1"></i></div>
            @if(optional(Auth::user())->can('update',$user))
                <div>本仙的</div>
            @else
                <div>{{$user->name}}的</div>
            @endif
            <div>所有点评({{$user->reviews_count}})</div>
            {{--移动端--}}
            @if($is_mobile && optional(Auth::user())->can('update',$user))
                <button type="button" class="btn btn-pink rounded ml-auto"
                        data-toggle="modal"
                        data-target="#matchUser">匹配的人
                </button>
            @endif
        </div>
        @if($user->reviews_count>0)
            <ul class="list-unstyled mt-1 mt-md-2">
                @foreach($reviews as $review)
                    <li class="border-top pb-4 pt-3">
                        @include('common.list_product',['product'=>$review->product,'cat'=>$review->cat,'brand'=>$review->brand])
                        @include('users.review')
                    </li>
                @endforeach
            </ul>
            <div class="d-flex justify-content-center">{{$reviews->fragment('user-reviews')->links()}}</div>
        @else
            <ul class="list-unstyled mt-1 mt-md-2">
                <li class="border-top py-2"><h4>还没有点评</h4></li>
            </ul>
        @endif

    </div>

    {{--右边部分--}}
    <div class="{{$is_tablet ? 'offset-md-1 col-md-10' : 'offset-md-1 col-md-4 pl-md-5'}}">

        {{--已登录且有权限--}}
        @if(optional(Auth::user())->can('update',$user))
            @if($is_mobile)
                {{--移动端match-user的modal--}}
                <div class="modal fade" id="matchUser" tabindex="-1" role="dialog">
                    <div class="modal-dialog m-0" role="document">
                        <div class="modal-content rounded-0 border-0">
                            <div class="modal-body">
                                @include('users.match')
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{--pc端--}}
                @include('users.match')
            @endif
        @endif

        <div class="pt-5">
            <div class="mb-2 text-muted hover-help d-inline-block" data-toggle="tooltip"
                 data-original-title="此分类一旦有点评则点亮">
                点评徽章
            </div>

            <ul class="list-inline border-top py-3">
                @if($user->reviews_count==0)
                    @foreach($all_cats as $cat)
                        <li class="list-inline-item text-center mr-1 mb-2">
                            <a href="{{route('cats.show',[$cat])}}">
                                <img src="{{Storage::url('cats/H-')}}{{$cat->id}}.jpg"
                                     alt="未点亮的{{$cat->name}}" class="product-s-size">
                                <div class="text-muted text-tiny">
                                    <div>{{$cat->name}}</div>
                                    <div>(0)</div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                @else
                    @foreach($all_cats as $cat)
                        <li class="list-inline-item text-center mr-1 mb-2">
                            @if(array_key_exists($cat->name,$cats))
                                <a href="{{route('cats.show',[$cat])}}">
                                    {{--备份--}}
                                    {{--<img src="{{asset("img/cats/cat_light_$cat->id.gif")}}" alt="点亮的{{$cat->name}}"--}}
                                    <img src="{{Storage::url('cats')}}/{{$cat->id}}.jpg"
                                         alt="点亮的{{$cat->name}}"
                                         class="product-s-size">
                                    <div class="text-brown text-tiny">
                                        <div>{{$cat->name}}</div>
                                        <div>({{$cats[$cat->name]}})</div>
                                    </div>
                                </a>
                            @else
                                <a href="{{route('cats.show',[$cat])}}">
                                    <img src="{{Storage::url('cats/H-')}}{{$cat->id}}.jpg"
                                         alt="未点亮的{{$cat->name}}" class="product-s-size">
                                    <div class="text-muted text-tiny">
                                        <div>{{$cat->name}}</div>
                                        <div>(0)</div>
                                    </div>
                                </a>
                            @endif
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>


    </div>


@endsection
