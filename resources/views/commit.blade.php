@extends('layouts.app')
@section('title','版本更新记录')

@section('content')

<div class="col-12">
    <h3>版本历史记录</h3>
    <ul class="list-unstyled review-text">
        <li class="border-top py-3">
            <div><span class="text-main text-big mr-3">Beta 1.0.5</span><span class="text-muted text-tiny">2018.8.1</span></div>
            <ol class="text-brown pl-3">
                <li>新增了10个品牌</li>
                <li>新增了20个商品</li>
                <li>无损压缩了所有的品牌图片，永远追求飞一般的感觉</li>
            </ol>
        </li>
        <li class="border-top py-3">
            <div><span class="text-main text-big mr-3">Beta 1.0.4</span><span class="text-muted text-tiny">2018.7.31</span></div>
            <ol class="text-brown pl-3">
                <li>在炎热的夏日整整搞了一天的缓存优化</li>
            </ol>
        </li>
        <li class="border-top py-3">
            <div><span class="text-main text-big mr-3">Beta 1.0.3</span><span class="text-muted text-tiny">2018.7.30</span></div>
            <ol class="text-brown pl-3">
                <li>申请了百度收录，谷歌收录</li>
                <li>修复了商品在点评被全部删除后变0分的bug</li>
                <li>在资源文件地址后面加了?t=xxx，保证用户在版本更新之后能看到最新内容</li>
            </ol>
        </li>
        <li class="border-top py-3">
            <div><span class="text-main text-big mr-3">Beta 1.0.2</span><span class="text-muted text-tiny">2018.7.29</span></div>
            <ol class="text-brown pl-3">
                <li>把分类图片变高清了</li>
                <li>把点评图片变高清了</li>
                <li>把用户头像变高清了</li>
                <li>把部分品牌图片的底色去掉了</li>
            </ol>
        </li>
        <li class="border-top py-3">
            <div><span class="text-main text-big mr-3">Beta 1.0.1</span><span class="text-muted text-tiny">2018.7.28</span></div>
            <ol class="text-brown pl-3">
                <li>把小程序点评内容的字体变大了</li>
                <li>修复了小程序和网页分页的bug</li>
                <li>修复了用户分享时无法回主页的bug</li>
            </ol>
        </li>
        <li class="border-top py-3">
            <div><span class="text-main text-big mr-3">Beta 1.0.0</span><span class="text-muted text-tiny">2018.7.27</span></div>
            <ol class="text-brown pl-3">
                <li>网站和小程序的Beta（公测版）同时上线，普天同庆！</li>
                <li>收录了351个最热门的品牌</li>
                <li>收录了2149个最热门的商品</li>
            </ol>
        </li>
    </ul>

</div>

@endsection
