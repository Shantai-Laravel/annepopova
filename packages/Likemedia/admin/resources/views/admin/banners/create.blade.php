@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('banners.index') }}">Banners</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Banner</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Create Banner </h3>
    @include('admin::admin.list-elements', [
        "actions" => [
            "Add new" => route('banners.create'),
        ]
    ])
</div>
@include('admin::admin.alerts')
<div class="list-content">
    <div class="card">
        <div class="card-block">
            <form class="form-reg" role="form" method="POST" action="{{ route('warehouses.store') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="key">Key</label>
                            <input type="text" class="form-control" name="key" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="picture">Banner Desktop Image:</label>
                                        <input type="file" name="banner_desktop" id="picture"/>
                                        <br>
                                        <img src="{{ asset('/admin/img/noimage.jpg') }}" style="width: 60%;">
                                    </div> <hr>
                                </div>
                                <div class="col-md-6"> <hr>
                                    <div class="form-group">
                                        <label for="desktop_width">Width <small>[px]</small> </label>
                                        <input type="number" class="form-control" name="desktop_width">
                                    </div>
                                    <div class="form-group">
                                        <label for="desktop_height">Height <small>[px]</small></label>
                                        <input type="number" class="form-control" name="desktop_height">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="picture_mob">Banner Mobile Image</label>
                                        <input type="file" name="banner_mobile" id="picture_mob"/>
                                        <br>
                                        <img src="{{ asset('/admin/img/noimage.jpg') }}" style="width: 60%;">
                                    </div> <hr>
                                </div>
                                <div class="col-md-6"> <hr>
                                    <div class="form-group">
                                        <label for="mobile_width">Width <small>[px]</small> </label>
                                        <input type="number" class="form-control" name="mobile_width">
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile_height">Height <small>[px]</small></label>
                                        <input type="number" class="form-control" name="mobile_height">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="submit" value="{{trans('variables.save_it')}}" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
