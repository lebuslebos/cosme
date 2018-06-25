{{--全部分类（移动端用）--}}


<div class="modal fade" id="allCats" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-slide w-50" role="document">
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
                                <a class="cat-nav d-inline-block py-2 pl-3 text-muted{{$all_cat->id==$cat->id?' active':''}}"
                                   href="{{$all_cat->id==$cat->id?'#':route('cats.show',[$all_cat])}}"
                                >{{$all_cat->name}}</a>
                            </li>
                        @endforeach
                    @endforeach
                </ul>

            </div>
        </div>
    </div>
</div>

