

{{--简易商品信息（上下分布型）--首页--}}

<div class="text-center mr-4" style="width: 119px;">
    <a href="{{route('products.show',[$review->product])}}" class="d-block mb-1" target="_blank">
        <img class="product-size" src="{{Storage::url('products')}}/{{$review->product_id}}.jpg!product" alt="{{$review->product->name}}">
    </a>
    {{--品牌+商品+分类--}}
    <div><a href="{{route('brands.show',[$review->brand])}}" target="_blank"
            class="text-secondary">{{$review->brand->name}}</a></div>
    <div class="text-truncate"><a href="{{route('products.show',[$review->product])}}" target="_blank"
            class="text-main">{{$review->product->name}}</a></div>
    {{--<div><a href="{{route('cats.show',[$review->product->cat])}}"
            class="text-secondary">[{{$review->product->cat->name}}]</a></div>--}}
    {{--评分--}}
    <div>
        <product-rate :rate="{{$review->product->rate}}"></product-rate>
    </div>
</div>
