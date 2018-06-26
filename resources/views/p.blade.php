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


@endsection
