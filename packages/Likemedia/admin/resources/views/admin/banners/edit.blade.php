@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('banners.index') }}">Banners</a></li>
        <li class="breadcrumb-item active" aria-current="banner">Edit banner</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Edit banner </h3>
    @include('admin::admin.list-elements', [
        'actions' => [
                "Add new" => route('banners.create'),
            ]
    ])
</div>
@include('admin::admin.alerts')
<div class="list-content">
    <div class="card">
        <div class="card-block">
            <form class="form-reg" role="form" method="POST" action="{{ route('banners.update', $banner->id) }}" id="add-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="key">Key</label>
                            <input type="text" class="form-control" name="key" value="{{ $banner->key }}" required>
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
                                        @if ($banner->desktop_src)
                                            <input type="hidden" name="banner_desktop_old" value="{{ $banner->desktop_src }}">
                                            <a href="/images/banners/{{$banner->desktop_src}}"  data-lightbox="image-1" data-title="{{ $banner->key }}" data-lightbox="{{ $banner->key }}">
                                                <img src="/images/banners/{{$banner->desktop_src}}" style="width: 60%;">
                                            </a>
                                        @else
                                            <img src="{{ asset('/admin/img/noimage.jpg') }}" style="width: 60%;">
                                        @endif
                                    </div><hr>
                                </div>
                                <div class="col-md-6"><hr>
                                    <div class="form-group">
                                        <label for="desktop_width">Width <small>[px]</small> </label>
                                        <input type="number" class="form-control" name="desktop_width" value="{{ $banner->desktop_width_size }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="desktop_height">Height <small>[px]</small></label>
                                        <input type="number" class="form-control" name="desktop_height" value="{{ $banner->desktop_height_size }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="picture_mob">Banner Mobile Image</label>
                                        <input type="file" name="banner_mobile" id="picture_mob"/>
                                        <br>
                                        @if ($banner->mobile_src)
                                            <input type="hidden" name="banner_mobile_old" value="{{ $banner->mobile_src }}">
                                            <a href="/images/banners/{{$banner->mobile_src}}"  data-lightbox="image-1" data-title="{{ $banner->key }}" data-lightbox="{{ $banner->key }}">
                                                <img src="/images/banners/{{$banner->mobile_src}}" style="width: 60%;">
                                            </a>
                                        @else
                                            <img src="{{ asset('/admin/img/noimage.jpg') }}" style="width: 60%;">
                                        @endif
                                    </div><hr>
                                </div>
                                <div class="col-md-6"><hr>
                                    <div class="form-group">
                                        <label for="mobile_width">Width <small>[px]</small> </label>
                                        <input type="number" class="form-control" name="mobile_width" value="{{ $banner->mobile_width_size }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile_height">Height <small>[px]</small></label>
                                        <input type="number" class="form-control" name="mobile_height" value="{{ $banner->mobile_height_size }}">
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
