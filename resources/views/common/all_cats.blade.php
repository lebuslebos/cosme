{{--所有分类--用于首页--}}

<button type="button" class="btn btn-pink rounded"
        data-toggle="modal"
        data-target="#homeAllCats">查看所有分类
</button>
<div class="modal fade" id="homeAllCats" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-slide" style="width: {{$is_mobile?'50%':'20%'}};" role="document">
        <div class="modal-content rounded-0 border-0">
            <div class="modal-body">

                <ul class="nav flex-column px-4">
                    @foreach(config('common.big_cats') as $index=>$big_cat)
                        <li class="nav-item mb-1{{$index==0? ' mt-2' : ' mt-4'}}">
                            <i class="fa fa-circle-o"></i>
                            <span class="text-main text-big">{{$big_cat}}</span>
                        </li>
                        @foreach($all_cats->where('id','>',$index*20)->where('id','<=',($index+1)*20) as $all_cat)
                            <li class="nav-item bg-light-brown">
                                <a class="cat-nav d-inline-block py-2 pl-3 text-muted" target="_blank"
                                   href="{{route('cats.show',[$all_cat])}}"
                                >{{$all_cat->name}}</a>
                            </li>
                        @endforeach
                    @endforeach
                </ul>

            </div>
        </div>
    </div>
</div>
