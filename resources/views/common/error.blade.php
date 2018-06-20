w<div class="alert alert-warning alert-dismissible fade show" role="alert">

    <ul class="list-unstyled">
        @foreach($errors->all() as $error)
            <li>{{$error}}</li>
        @endforeach
    </ul>

    <button type="button" class="close" data-dismiss="alert" >
        <span>&times;</span>
    </button>
</div>
