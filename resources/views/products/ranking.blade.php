
{{--商品页的排行榜--}}

{{--与此商品同一分类的好商品（按回购率排，取10个以上点评作为基数）--}}

<div class="mb-2">
    [ <a href="{{route('cats.show',[$cat])}}" target="_blank"><span class="text-main">{{$cat->name}}</span></a> ]&nbsp;
    <span class="text-muted hover-help" data-toggle="tooltip"
          data-original-title="与此商品相同分类的好用排行榜 | 取拥有一定数量点评的商品，按回购率由高到低排">
                红榜
            </span>
    <span class="text-muted text-tiny">
                ( {{date('Y年n月j日')}}凌晨{{config('common.ranking_updated_at')}}点更新 )
            </span>
</div>

<ul class="list-unstyled">
    @forelse($red_products as $index=>$red_product)
        <ranking-product :index="{{$index}}" :product="{{$red_product}}" :cat="{{$cat}}" :brand="{{$red_product->brand}}" :type="true" :current-product-id="{{$product->id}}"></ranking-product>
    @empty
        <li class="border-top nothing">
            <span>暂无榜单</span>
            <a href="{{route('cats.show',[$cat])}}" class="text-main"><i class="fa fa-pencil-square-o"></i>去点评</a>
        </li>
    @endforelse
</ul>
