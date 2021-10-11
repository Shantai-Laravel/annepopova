@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('brands.index') }}">Collections</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Collection</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Create Collection </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('collections.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">
    <form class="form-reg" role="form" method="POST" action="{{ route('collections.store') }}" id="add-form"
        enctype="multipart/form-data">
        {{ csrf_field() }}
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
            <div class="part left-part">
                <ul>
                    <li>
                        <label for="name-{{ $lang->lang }}">{{trans('variables.title_table')}} [{{ $lang->lang }}]</label>
                        <input type="text" name="title_{{ $lang->lang }}" class="name">
                    </li>
                    <li>
                        <label for="description-{{ $lang->lang }}">Description [{{ $lang->lang }}]</label>
                        <textarea name="description_{{ $lang->lang }}"></textarea>
                    </li>
                    <li>
                        <label for="body-{{ $lang->lang }}">Body [{{ $lang->lang }}]</label>
                        <textarea name="body_{{ $lang->lang }}"></textarea>
                    </li>
                </ul>
            </div>
            <div class="part right-part">

                <ul>
                    <hr>
                    <h6>Seo Texts</h6>

                    <li>
                        <label for="seo_title_{{ $lang->lang }}">Seo Title [{{ $lang->lang }}]</label>
                        <input type="text" name="seo_title_{{ $lang->lang }}">
                    </li>
                    <li>
                        <label for="seo_description_{{ $lang->lang }}">Seo Description [{{ $lang->lang }}]</label>
                        <input type="text" name="seo_descr_{{ $lang->lang }}">
                    </li>
                    <li>
                        <label for="seo_keywords_{{ $lang->lang }}">Seo Keywords [{{ $lang->lang }}]</label>
                        <input type="text" name="seo_keywords_{{ $lang->lang }}">
                    </li>
                </ul>
                <ul>
                    <hr>
                    <li>
                        <label for="img-{{ $lang->lang }}">Image (multilingual) [{{ $lang->lang }}]</label>
                        <input type="file" name="image_{{ $lang->lang }}" id="img-{{ $lang->lang }}"/>
                    </li>
                </ul>
            </div>
        </div>
        @endforeach
        @endif

        <ul class="part full-part">
            <div class="row">
                <div class="col-md-4">
                    <li>
                        <label for="img">Collection Banner</label>
                        <input type="file" name="banner" id="nammer"/>
                    </li>
                </div>
            </div>
            <li><br>
                <input type="submit" value="{{trans('variables.save_it')}}" data-form-id="add-form">
            </li>
        </ul>
    </form>
</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
