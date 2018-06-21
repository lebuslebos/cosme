
{{--商品页的排行榜--}}

{{--与此商品同一分类的好商品（按回购率排，取10个以上点评作为基数）--}}

<div class="mb-2">
    [ <span class="text-main">{{$cat->name}}</span> ]&nbsp;
    <span class="text-muted hover-help" data-toggle="tooltip"
          data-original-title="与该商品相同分类的好用排行榜（取一定点评数，按回购率排）">
                红榜
            </span>
    <span class="text-muted text-tiny">
                ( {{date('Y年n月j日')}}凌晨{{config('common.ranking_updated_at')}}点更新 )
            </span>
</div>

<ul class="list-unstyled">
    @foreach($red_products as $index=>$red_product)
        <ranking-product :index="{{$index}}" :product="{{$red_product}}" :cat="{{$cat}}"
                         :brand="{{$red_product->brand}}" :type="true" :current-product-id="{{$product->id}}"></ranking-product>
    @endforeach
</ul>
