@extends('layouts.app')
@section('content')

    <ul class="col-md-12 list-unstyled mt-1 mt-md-2">
        @foreach($brands as $brand)
            <li class="py-3 border-top">
                @include('common.brand')
            </li>
        @endforeach
    </ul>

    <div class="d-flex justify-content-center pt-5">{{$brands->links()}}</div>


@endsection
