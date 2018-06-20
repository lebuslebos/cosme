
{{--品牌页的排行榜--}}

{{--此品牌回购品排行榜(红榜)（按回购率排，取10个以上点评作为基数）--}}
<div class="mb-2">
    [ <span class="text-main">{{$brand->name}}</span> ]&nbsp;
    <span class="text-muted hover-help" data-toggle="tooltip"
          data-original-title="该品牌的好用排行榜（取一定点评数，按回购率排）">
                红榜
            </span>
    <span class="text-muted text-tiny">
                ( {{date('Y年n月j日')}}凌晨{{env('RANKING_UPDATED_AT')}}点更新 )
            </span>
</div>
<ul class="list-unstyled">
    @foreach($red_products as $index=>$product)
        <ranking-product :index="{{$index}}" :product="{{$product}}" :cat="{{$product->cat}}"
                         :brand="{{$brand}}" :type="true" in="brand"></ranking-product>
    @endforeach
</ul>



{{--此品牌不会回购品排行榜(黑榜)（按回购率排，取10个以上点评作为基数）--}}
<div class="pt-5">
    [ <span class="text-main">{{$brand->name}}</span> ]&nbsp;
    <span class="text-muted hover-help" data-toggle="tooltip"
          data-original-title="该品牌的不好用排行榜（取一定点评数，按回购率排）">
                黑榜
            </span>
    <span class="text-muted text-tiny">
                ( {{date('Y年n月j日')}}凌晨{{env('RANKING_UPDATED_AT')}}点更新 )
            </span>
</div>
<ul class="list-unstyled">
    @foreach($black_products as $index=>$product)
        <ranking-product :index="{{$index}}" :product="{{$product}}" :cat="{{$product->cat}}"
                         :brand="{{$brand}}" :type="false" in="brand"></ranking-product>
    @endforeach
</ul>
