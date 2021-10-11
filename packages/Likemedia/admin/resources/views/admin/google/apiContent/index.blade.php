@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="gallery">Google API Content </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Google API Content </h3>
</div>

<div class="card card-block">

        <div class="row">
            <div class="col-md-6">
                <h6>Insert/Update all products</h6>
                <form class="" action="{{ url('/back/google-api-content/insert-content') }}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="">Countries</label>
                        <select class="form-control" name="country">
                            @foreach ($countries as $key => $country)
                                <option value="{{ $country->iso }}">{{ $country->translation->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Languages</label>
                        <select class="form-control" name="lang">
                            @foreach ($langs as $key => $oneLang)
                                <option value="{{ $oneLang->lang }}">{{ $oneLang->lang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Handle" class="btn btn-primary">
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <h6>Add products to list</h6>
                <form class="" action="{{ url('/back/google-api-content/insert-new-content') }}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="">Countries</label>
                        <select class="form-control" name="country">
                            @foreach ($countries as $key => $country)
                                <option value="{{ $country->iso }}">{{ $country->translation->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Languages</label>
                        <select class="form-control" name="lang">
                            @foreach ($langs as $key => $oneLang)
                                <option value="{{ $oneLang->lang }}">{{ $oneLang->lang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Handle" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>

    <hr>
</div>


@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
