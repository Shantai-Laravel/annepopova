@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('brands.index') }}">Brands</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Brand</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Create Brand </h3>
    @include('admin::admin.list-elements', [
        "actions" => [
            "Add new" => route('brands.create'),
        ]
    ])
</div>
@include('admin::admin.alerts')
<div class="list-content">
    <div class="card">
        <div class="card-block">
            <form class="form-reg" role="form" method="POST" action="{{ route('brands.store') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-8">
                        <div class="tab-area">
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                @if (!empty($langs))
                                @foreach ($langs as $lang)
                                <li class="nav-item">
                                    <a href="#{{ $lang->lang }}" class="nav-link  {{ $loop->first ? ' open active' : '' }}"
                                        data-target="#{{ $lang->lang }}">{{ $lang->lang }}</a>
                                </li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                        @if (!empty($langs))
                        @foreach ($langs as $lang)
                        <div class="tab-content {{ $loop->first ? ' active-content' : '' }}" id={{ $lang->
                            lang }}> <br>
                            <div class="form-group">
                                <label for="name-{{ $lang->lang }}">Title [{{ $lang->lang }}]</label>
                                <input type="text" class="form-control" name="title_{{ $lang->lang }}" id="title-{{ $lang->lang }}">
                            </div>
                            <div class="form-group">
                                <label for="description-{{ $lang->lang }}">Description [{{ $lang->lang }}]</label>
                                <textarea name="description_{{ $lang->lang }}"  id="body-{{ $lang->lang }}" class="form-control" id="description-{{ $lang->lang }}"></textarea>
                            </div>
                            <script>
                                CKEDITOR.replace('body-{{ $lang->lang }}', {
                                    language: '{{$lang}}',
                                    height: '200px'
                                });
                            </script>
                            <div class="form-group">
                                <label for="seo_text_{{ $lang->lang }}">Body [{{ $lang->lang }}]</label>
                                <textarea name="seo_text_{{ $lang->lang }}" class="form-control" id="seo_text_{{ $lang->lang }}"></textarea>
                            </div>

                            <hr>
                            <div class="form-group">
                                <label for="seo_title_{{ $lang->lang }}">Seo Title [{{ $lang->lang }}]</label>
                                <input type="text" name="seo_title_{{ $lang->lang }}" class="form-control" id="seo_title_{{ $lang->lang }}">
                            </div>
                            <div class="form-group">
                                <label for="seo_descr_{{ $lang->lang }}">Seo Description [{{ $lang->lang }}]</label>
                                <input type="text" name="seo_descr_{{ $lang->lang }}" class="form-control" id="seo_descr_{{ $lang->lang }}">
                            </div>
                            <div class="form-group">
                                <label for="seo_keywords_{{ $lang->lang }}">Seo Keywords [{{ $lang->lang }}]</label>
                                <input type="text" name="seo_keywords_{{ $lang->lang }}" class="form-control" id="seo_keywords_{{ $lang->lang }}">
                            </div>
                            <div class="form-group">
                                <img src="{{ asset('/admin/img/noimage.jpg') }}" style="height: 100px;">
                                <label for="img-{{ $lang->lang }}">Image [{{ $lang->lang }}]</label>
                                <input type="file" name="image_{{ $lang->lang }}" id="img-{{ $lang->lang }}"/>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="img">Brand Logo</label>
                                    <input type="file" name="logo" id="img"/>
                                    <br>
                                    <img src="{{ asset('/admin/img/noimage.jpg') }}" style="width: 60%;">
                                </div> <hr>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="picture">Brand Picture</label>
                                    <input type="file" name="picture" id="picture"/>
                                    <br>
                                    <img src="{{ asset('/admin/img/noimage.jpg') }}" style="width: 60%;">
                                </div> <hr>
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
