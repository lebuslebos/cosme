@extends('layouts.app')
@section('title','登录')

@section('content')

    <div class="col-md-4 offset-md-4 pt-5">
        <form action="{{route('test')}}" method="post" class="form-inline pt-5">
            @csrf

            <input type="hidden" name="url" value="{{request()->url()}}">
            <div class="form-group w-50">
                <input type="number" name="mobile" placeholder="手机号"  class="form-control w-100">
            </div>
            <div class="form-group w-25 mx-3">
                <select class="form-control w-100" name="skin">
                    <option value="0">中性</option>
                    <option value="1">干性</option>
                    <option value="2" selected="selected">混合性</option>
                    <option value="3">油性</option>
                    <option value="4">敏感性</option>
                    <option value="5">过敏性</option>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-main rounded" type="submit">登录</button>
            </div>

        </form>
    </div>

@endsection
