@extends('layouts.app')
@section('title','登录')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">登录</div>

                    <div class="card-body">
                        @if($errors->any())
                            @include('common.error')
                        @endif

                        <form method="POST" action="{{route('login')}}">
                            @csrf

                            <mobile-form ></mobile-form>

                            <verify-code-form></verify-code-form>


                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-info btn-block">
                                        登录
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection
