{{--在商品列表页直接展示点评、目前用在个人页--显示自己的所有点评--}}

<user-review :product-id="{{$review->product_id}}" :review="{{$review}}"
             :can="@json(optional(Auth::user())->can('update',$user))"
             :likes="{{$review->likes_count}}" :hates="{{$review->hates_count}}"
             class="mt-2"
></user-review>


{{--用户必须已登录+用户有权限改这个点评--}}
{{--fromUser的作用是--直接展开点评、所以两个都传--}}
{{--@if(optional(Auth::user())->can('update',$review))
    <review :from-user="true"
            :is-login="true"
            :product-id="{{$review->product->id}}"
            :review="{{$review}}"
            :likes="{{$review->likes_count}}"
            :hates="{{$review->hates_count}}"
            class="p-3"
    ></review>

@else
    <review :from-user="true"
            :is-login="@json(auth()->check())"
            :product-id="{{$review->product->id}}"
            :review="{{$review}}"
            class="p-3"
            :can-not="true"
    ></review>
@endif--}}
