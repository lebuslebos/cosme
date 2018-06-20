@extends('layouts.app')

@section('content')

    {{--左边部分--}}
    <div class="{{$is_tablet ? 'offset-md-1 col-md-10' : 'col-md-7'}}">
        <div class="d-flex align-items-baseline">
            <div class="text-muted text-tiny">有文字的点评会展示在这里</div>
            {{--移动端--}}
            @if($is_mobile)
            <button type="button" class="ml-auto btn btn-pink rounded"
                    data-toggle="modal"
                    data-target="#ranking">快速选化妆品
            </button>
            @endif
        </div>
        <ul class="list-unstyled mt-2">
            @foreach($reviews as $review)
                <li class="media py-3 py-md-4 border-top">

                    {{--商品简易信息,只有电脑和平板端才显示--}}
                    @if(!$is_phone)
                        @include('common.simple_product')
                    @endif

                    {{--点评--}}
                    <div class="media-body d-flex flex-column min-height-pc">
                        @include('common.review')
                    </div>
                </li>
            @endforeach
        </ul>

        {{--<div class="d-flex justify-content-center pt-5">{{$reviews->links()}}</div>--}}


    </div>



    {{--移动端ranking的modal--}}
    @if($is_mobile)
    <div class="modal fade" id="ranking" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-slide" role="document">

            {{--这里改border和radius--}}
            <div class="modal-content rounded-0 border-0">

                {{--<div class="modal-header">
                    <h5 class="modal-title">排行榜</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>--}}
                <div class="modal-body">
                    @include('common.ranking_all')
                </div>
            </div>
        </div>
    </div>
    @else
    {{--右边部分（电脑端）--}}
    <div class="offset-md-1 col-md-4 pl-md-5">
        @include('common.ranking_all')
    </div>
    @endif

@endsection
