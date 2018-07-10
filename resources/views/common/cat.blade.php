{{--分类信息：用于分类详情、搜索页面--}}

<div class="media align-items-center">
    @if(Route::currentRouteName()=='cats.show')
        <div class="mr-md-1" style="padding: 0 24px 0 25px;">
            <img src="{{Storage::url('cats')}}/{{$cat->id}}.jpg!product.s" class="product-s-size" alt="{{$cat->name}}">
        </div>
    @else
        <a href="{{route('cats.show',[$cat])}}">
            <div class="mr-md-1" style="padding: 0 24px 0 25px;">
                <img src="{{Storage::url('cats')}}/{{$cat->id}}.jpg!product.s" class="product-s-size" alt="{{$cat->name}}">
            </div>
        </a>
    @endif


    <div class="media-body">
        <div class="d-flex justify-content-between align-items-end pb-1 pb-md-2 pl-md-2 border-dotted">
            @if(Route::currentRouteName()=='cats.show')
                <h3 class="text-brown mb-0">{{$cat->name}}</h3>
                {{--移动端--}}
                @if($is_mobile)
                <button type="button" class="btn btn-pink rounded"
                        data-toggle="modal"
                        data-target="#allCats">所有分类
                </button>
                @endif
            @else
                <a href="{{route('cats.show',[$cat])}}">
                    <h3 class="text-brown mb-0">{{$cat->name}}</h3>
                </a>
            @endif
        </div>

        {{--<h1 class="mt-0">{{$cat->name}}</h1>--}}
        <div class="border-dotted pl-md-2 py-2">
            <div class="text-easy bg-easy d-inline-block  pr-1">包括:{{$cat->similar_name}}</div>
        </div>
        {{--<div class="align-self-end ml-2">这里是一些介绍这里是一些介绍这里是一些介绍</div>--}}
    </div>

</div>
