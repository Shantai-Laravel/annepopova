@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">Blogs</a></li>
        <li class="breadcrumb-item active" aria-current="blog">Edit blog</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Edit blog </h3>
    @include('admin::admin.list-elements', [
        'actions' => [
                "Add new" => route('blogs.create'),
            ]
    ])
</div>
@include('admin::admin.alerts')
<div class="list-content">
    <div class="card">
        <div class="card-block">
            <form class="form-reg" role="form" method="POST" action="{{ route('blogs.update', $blog->id) }}" id="add-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
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
                        <div class="tab-content {{ $loop->first ? ' active-content' : '' }}" id={{ $lang->lang }}>
                            @foreach($blog->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <div class="form-group">
                                    <label for="name-{{ $lang->lang }}">Title [{{ $lang->lang }}]</label>
                                    <input type="text" name="title_{{ $lang->lang }}" class="form-control" value="{{ $translation->name }}">
                                </div>
                                @endif
                            @endforeach
                            @foreach($blog->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <div class="form-group">
                                    <label for="descr-{{ $lang->lang }}">Description [{{ $lang->lang }}]</label>
                                    <textarea name="description_{{ $lang->lang }}" class="form-control">{{ $translation->description }}</textarea>
                                </div>
                                @endif
                            @endforeach
                            @foreach($blog->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <div class="form-group">
                                    <label for="body_{{ $lang->lang }}">Body [{{ $lang->lang }}]</label>
                                    <textarea  name="body_{{ $lang->lang }}" id="body-{{ $lang->lang }}" class="form-control">{{ $translation->body }}</textarea>
                                </div>
                                <script>
                                    CKEDITOR.replace('body-{{ $lang->lang }}', {
                                        language: '{{$lang}}',
                                        height: '500px',
                                        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
                                        filebrowserUploadMethod: 'form'
                                    });
                                    CKEDITOR.config.contentsCss = 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'
                                </script>
                                @endif
                            @endforeach
                            <hr>
                            @foreach($blog->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <div class="form-group">
                                    <label for="meta_title_{{ $lang->lang }}">Seo Title [{{ $lang->lang }}]</label>
                                    <input type="text" name="seo_title_{{ $lang->lang }}" class="form-control" value="{{ $translation->seo_title }}">
                                </div>
                                @endif
                            @endforeach
                            @foreach($blog->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <div class="form-group">
                                    <label for="seo_descr_{{ $lang->lang }}">Seo Description [{{ $lang->lang }}]</label>
                                    <input type="text" name="seo_descr_{{ $lang->lang }}" class="form-control" value="{{ $translation->seo_descr }}">
                                </div>
                                @endif
                            @endforeach
                            @foreach($blog->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <div class="form-group">
                                    <label for="seo_keywords_{{ $lang->lang }}">Seo Keywords [{{ $lang->lang }}]</label>
                                    <input type="text" name="seo_keywords_{{ $lang->lang }}" class="form-control" value="{{ $translation->seo_keywords }}">
                                </div>
                                @endif
                            @endforeach
                            <hr>
                            <div class="form-group">
                                @foreach($blog->translations as $translation)
                                    @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                        @if ($translation->banner)
                                            <img src="{{ asset('images/blogs/'. $translation->banner ) }}" height="100px">
                                            <input type="hidden" name="old_image_{{ $lang->lang }}" value="{{ $translation->banner }}"/>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Category</label>
                            <select class="form-control" name="category_id">
                                @foreach ($categories as $key => $category)
                                    <option value="{{ $category->id }}" {{ $category->id == $blog->category_id ? 'selected' : '' }}>
                                        {{ $category->translation->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Gallery</label>
                            <select class="form-control" name="gallery_id">
                                <option value="0">---</option>
                                @foreach ($galleries as $key => $gallery)
                                    <option value="{{ $gallery->id }}" {{ $gallery->id == $blog->gallery_id ? 'selected' : '' }}>
                                        {{ $gallery->alias }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="date" name="date" value="{{ date('Y-m-d', strtotime($blog->date_time)) }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>
                                <input class="checkbox" type="checkbox" name="first" {{ $blog->first ? 'checked' : '' }}>
                                <span>First</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="img">Blog Picture</label>
                            <input type="file" name="picture" id="img"/>
                            <br>
                            @if ($blog->image)
                                <img src="{{ asset('images/blogs/'. $blog->image ) }}" style="width: 60%;">
                                <input type="hidden" name="picture_old" value="{{ $blog->image }}"/>
                            @else
                                <img src="{{ asset('/admin/img/noimage.jpg') }}" style="width: 60%;">
                            @endif
                            <hr>
                        </div>
                        <div class="form-group">
                            <label for="img">Blog Picture Mob</label>
                            <input type="file" name="picture_mob" id="img"/>
                            <br>
                            @if ($blog->image_mob)
                                <img src="{{ asset('images/blogs/'. $blog->image_mob ) }}" style="width: 60%;">
                                <input type="hidden" name="picture_old_mob" value="{{ $blog->image_mob }}"/>
                            @else
                                <img src="{{ asset('/admin/img/noimage.jpg') }}" style="width: 60%;">
                            @endif
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" value="{{trans('variables.save_it')}}" class="btn btn-primary">
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
