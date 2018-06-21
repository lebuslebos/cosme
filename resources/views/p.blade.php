@extends('layouts.app')
@section('content')

    <ul class="col-md-12 list-unstyled mt-1 mt-md-2">
        @foreach($products as $product)
            <li class="py-3 border-top">
                @include('common.list_product',['cat'=>$product->cat,'brand'=>$product->brand])
            </li>
        @endforeach
    </ul>

    <div class="d-flex justify-content-center pt-5">{{$products->links()}}</div>



    {{--关于缓存：
    批量更新，批量删除不会触发model事件（批量是指：带where的语句，大量同时更新或删除，并不是说用了update或delete就不能触发）


    tags：（命名规则：前部代表-->哪个页面，尾部代表-->他若更新则此tag需更新）

    带分页的:key为加上page数
    cats-1-products:需后台更新--->product观察者saved和deleted事件时更新
    brands-1-products:需后台更新--->product观察者saved和deleted事件时更新
    products-1-reviews:在review观察者saved事件时更新
    users-1-reviews:review观察者saved事件(仅登录用户)时更新

    ranking:需后台更新+更新完放入缓存---key例:cats-1-desc-5--->已加入定时任务（全部flush+把分类排行榜存入缓存）
    match:需后台更新---key:match-map-1,match-users-1--->已加入定时任务




    key:

    reviews:首页点评--review观察者created事件（仅登录+有内容）时更新
    (固定)popular-cats:首页随机cat，id决定哪几个之后基本不变（可随季节变动），需后台更新+更新完放入缓存


    (后台三件套):
    cats-1:需后台更新--->cat观察者updated和deleted事件时更新；因观察者的$cat不可筛选列，故不做放入缓存操作。
    (固定)all-cats:全部分类，需后台更新--->cat观察者saved和deleted事件时更新；因每次更改cat都造成后台有压力，故不做放入缓存操作。
    brands-1:1，需后台更新--->brand观察者updated和deleted事件时更新
    products-1:1,需后台更新--->product观察者updated和deleted事件时更新。2,先由此商品的-第一篇-登录用户-的点评触发更新商品事件，由product观察者的updated事件时更新。


    users-1:user观察者updated事件时更新
    users-1-b,users-1-c:用户用的最多的分类和品牌。review观察者created事件（仅登录用户）时更新

    1-2:user_id-product_id(my_review),我的点评，review观察者saved事件（仅登录用户）时更新。

    --}}{{--users-1-again:用户会回购的商品。登录用户新建点评（会回购）+修改点评（两次回购有变动）时更新。--}}{{--


    全部存在缓存中的--需定时持久化存储
    点评(2)：
    l-1/h-1:likes_count/hates_count。点赞/踩-点评id---每条点评的点赞/踩数

    品牌(3):
    r-1-b:reviews_count
    b-1-b:buys_count

    商品():
    sh-1:shop分布---共用下面的r-p-ids，得出每日有点评入账的商品ids，刷新掉其的缓存
    sk-1:skin分布---共用下面的p-ids，得出每日有内容点评入账的商品ids，刷新掉其的缓存

    ra-1:rate
    p-ids:定时（每日）有内容点评入账的商品ids，review观察者created事件时push进，然后在缓存中用新值覆盖原来的值---定时执行(用户更新点评的时候暂不算)

    r-1-p:reviews_count
    r-p-ids:定时（每日）有点评入账的商品ids，review观察者created事件时push进，然后在缓存中用新值覆盖原来的值---定时执行

    b-1-p:buys_count
    b-p-ids:定时（每日）有回购点评入账的商品ids，review观察者created事件时push进，然后在缓存中用新值覆盖原来的值---定时执行(用户更新点评的时候暂不算)

    用户(4)：
    r-1-u：reviews_count
    b-1-u：buys_count
    l-1-u/h-1-u:likes_count/hates_count用户获得的赞/踩数--}}



@endsection
