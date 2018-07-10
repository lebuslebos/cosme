{{--商品列表下面的点评框--}}
@if(filled($my_review=optional(Auth::user())->my_review($product->id)))
    {{--用户已登录状态+用户已点评过--->就一种很少的情况，所以先判断--}}
    <review :from-list="true" :is-login="true" :product-id="{{$product->id}}" :review="{{$my_review}}"></review>
@else
    {{--用户已登录但未点+用户未登录（包括未点和已点）--->共三种情况--}}
    <review :from-list="true" :is-login="@json(Auth::check())" :product-id="{{$product->id}}"></review>
@endif
