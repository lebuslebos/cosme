{{--分类页的排行榜--}}

{{--此分类回购品排行榜(红榜)（按回购率排，取10个以上点评作为基数）--}}
<div class="mb-2">
    [ <span class="text-main">{{$cat->name}}</span> ]&nbsp;
    <span class="text-muted hover-help" data-toggle="tooltip"
          data-original-title="此分类的好用排行榜 | 取拥有一定数量点评的商品，按回购率由高到低排">
        红榜
    </span>
    <span class="text-muted text-tiny">
        ( {{date('Y年n月j日')}}凌晨{{config('common.ranking_updated_at')}}点更新 )
    </span>
</div>
<ul class="list-unstyled">
    @forelse($red_products as $index=>$product)
        <ranking-product :index="{{$index}}" :product="{{$product}}" :cat="{{$cat}}"
                         :brand="{{$product->brand}}" :type="true" in="cat"></ranking-product>
    @empty
        <li class="border-top nothing">暂无榜单</li>
    @endforelse
</ul>

{{--此分类不会回购品排行榜(黑榜)（按回购率排，取10个以上点评作为基数）--}}
<div class="pt-5">
    [ <span class="text-main">{{$cat->name}}</span> ]&nbsp;
    <span class="text-muted hover-help" data-toggle="tooltip"
          data-original-title="此分类的差评排行榜 | 取拥有一定数量点评的商品，按不会回购率由高到低排">
        黑榜
    </span>
    <span class="text-muted text-tiny">
        ( {{date('Y年n月j日')}}凌晨{{config('common.ranking_updated_at')}}点更新 )
    </span>
</div>
<ul class="list-unstyled">
    @forelse($black_products as $index=>$product)
        <ranking-product :index="{{$index}}" :product="{{$product}}" :cat="{{$cat}}"
                         :brand="{{$product->brand}}" :type="false" in="cat"></ranking-product>
    @empty
        <li class="border-top nothing">暂无榜单</li>
    @endforelse
</ul>
