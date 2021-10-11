@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('pages.index') }}">Pages</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit page</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Edit page </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
            "Add new" => route('pages.create'),
        ]
    ])
</div>
@include('admin::admin.alerts')


<div class="list-content">
    <div class="card">
        <div class="card-block">
            <form class="form-reg" role="form" method="POST" action="{{ route('pages.update', $page->id) }}" id="add-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="row">
                    <div class="col-md-9">
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
                        <div class="tab-content {{ $loop->first ? ' active-content' : '' }}" id={{ $lang->lang }}>
                            @foreach($page->translations as $translation)
                            @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <div class="form-group">
                                    <label for="name-{{ $lang->lang }}">Title [{{ $lang->lang }}]</label>
                                    <input type="text" name="title_{{ $lang->lang }}" class="form-control" value="{{ $translation->title }}">
                                </div>
                            @endif
                            @endforeach
                            @foreach($page->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                    <div class="ckeditor form-group">
                                        <label>Description [{{ $lang->lang }}]</label>
                                        <textarea name="description_{{ $lang->lang }}" class="form-control">{!! $translation->description !!}</textarea>
                                    </div>
                                @endif
                            @endforeach
                            @foreach($page->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                    <div class="ckeditor form-group">
                                        <label for="body-{{ $lang->lang }}">Body [{{ $lang->lang }}]</label>
                                        <textarea name="body_{{ $lang->lang }}" id="body-{{ $lang->lang }}" data-type="ckeditor">{!! $translation->body !!}</textarea>
                                        <script>
                                            CKEDITOR.replace('body-{{ $lang->lang }}', {
                                                language: '{{$lang}}',
                                                height: '500px',
                                                filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
                                                filebrowserUploadMethod: 'form'
                                            });
                                            CKEDITOR.config.contentsCss = 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'
                                        </script>
                                    </div>
                                @endif
                            @endforeach
                        <hr>
                        @foreach($page->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                            <div class="form-group">
                                <label>Seo Title [{{ $lang->lang }}]</label>
                                <input type="text" name="meta_title_{{ $lang->lang }}" class="form-control" value="{{ $translation->meta_title }}">
                            </div>
                        @endif
                        @endforeach
                        @foreach($page->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                            <div class="form-group">
                                <label>Seo Keywords [{{ $lang->lang }}]</label>
                                <input type="text" name="meta_keywords_{{ $lang->lang }}" class="form-control" value="{{ $translation->meta_keywords }}">
                            </div>
                        @endif
                        @endforeach
                        @foreach($page->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                            <div class="form-group">
                                <label>Seo Title [{{ $lang->lang }}]</label>
                                <input type="text" name="meta_description_{{ $lang->lang }}" class="form-control" value="{{ $translation->meta_description }}">
                            </div>
                        @endif
                        @endforeach
                        <hr>
                        <div class="form-group">
                            @foreach($page->translations as $translation)
                                @if($translation->lang_id == $lang->id)
                                    @if ($translation->image)
                                        <img src="{{ asset('/images/pages/'. $translation->image ) }}" height="100px">
                                        <input type="hidden" name="old_image_{{ $lang->lang }}" value="{{ $translation->image }}"/>
                                    @else
                                        <img src="{{ asset('/admin/img/noimage.jpg') }}" style="height: 100px;">
                                    @endif
                                @endif
                            @endforeach
                            <label for="img-{{ $lang->lang }}">Image [{{ $lang->lang }}]</label>
                            <input type="file" name="image_{{ $lang->lang }}" id="img-{{ $lang->lang }}"/>
                        </div>
                    </div>
                    @endforeach
                    @endif
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="alias">Gallery</label>
                            <select class="form-control" name="gallery_id">
                                <option value="0">---</option>
                                @if (!empty($galleries))
                                @foreach ($galleries as $key => $gallery)
                                    <option {{ $gallery->id == $page->gallery_id ? 'selected' : '' }} value="{{ $gallery->id }}">{{ $gallery->alias }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="on_header" {{ $page->on_header ? 'checked' : '' }}>
                                <span>On Header</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="on_drop_down" {{ $page->on_drop_down ? 'checked' : '' }}>
                                <span>On Drop Down</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="on_footer" {{ $page->on_footer ? 'checked' : '' }}>
                                <span>On Footer</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="img">Banner</label>
                            <input type="file" name="logo" id="img"/>
                            <br>
                            @if ($page->image)
                                <img src="{{ asset('/images/pages/'. $page->image ) }}" style="width: 70%;">
                                <input type="hidden" name="banner_old" value="{{ $page->image }}"/>
                            @else
                                <img src="{{ asset('/admin/img/noimage.jpg') }}" style="width: 70%;">
                            @endif
                            <hr>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <input type="submit" class="btn btn-primary" value="{{trans('variables.save_it')}}" data-form-id="add-form">
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
