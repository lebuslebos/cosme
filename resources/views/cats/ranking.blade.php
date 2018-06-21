{{--分类页的排行榜--}}

{{--此分类回购品排行榜(红榜)（按回购率排，取10个以上点评作为基数）--}}
<div class="mb-2">
    [ <span class="text-main">{{$cat->name}}</span> ]&nbsp;
    <span class="text-muted hover-help" data-toggle="tooltip"
          data-original-title="该分类的好用排行榜（取一定点评数，按回购率排）">
        红榜
    </span>
    <span class="text-muted text-tiny">
        ( {{date('Y年n月j日')}}凌晨{{config('common.ranking_updated_at')}}点更新 )
    </span>
</div>
<ul class="list-unstyled">
    @foreach($red_products as $index=>$product)
        <ranking-product :index="{{$index}}" :product="{{$product}}" :cat="{{$cat}}"
                         :brand="{{$product->brand}}" :type="true" in="cat"></ranking-product>
    @endforeach
</ul>

{{--此分类不会回购品排行榜(黑榜)（按回购率排，取10个以上点评作为基数）--}}
<div class="pt-5">
    [ <span class="text-main">{{$cat->name}}</span> ]&nbsp;
    <span class="text-muted hover-help" data-toggle="tooltip"
          data-original-title="该分类的不好用排行榜（取一定点评数，按回购率排）">
        黑榜
    </span>
    <span class="text-muted text-tiny">
        ( {{date('Y年n月j日')}}凌晨{{config('common.ranking_updated_at')}}点更新 )
    </span>
</div>
<ul class="list-unstyled">
    @foreach($black_products as $index=>$product)
        <ranking-product :index="{{$index}}" :product="{{$product}}" :cat="{{$cat}}"
                         :brand="{{$product->brand}}" :type="false" in="cat"></ranking-product>
    @endforeach
</ul>
