@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('promotions.index') }}">Promotions</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Promotion</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Create Promotion </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
            trans('variables.add_element') => route('promotions.create'),
        ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">
    <div class="card">
        <div class="card-block">

    <form class="form-reg" role="form" method="POST" action="{{ route('promotions.store') }}" id="add-form"
        enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="tab-area">
            <ul class="nav nav-tabs nav-tabs-bordered">
                @if (!empty($langs))
                @foreach ($langs as $key => $lang)
                <li class="nav-item">
                    <a href="#{{ $lang->lang }}" class="nav-link  {{ $key == 0 ? ' open active' : '' }}"
                        data-target="#{{ $lang->lang }}">{{ $lang->lang }}</a>
                </li>
                @endforeach
                @endif
            </ul>
        </div>
        @if (!empty($langs))
        @foreach ($langs as $key  => $lang)
        <div class="tab-content {{ $key == 0  ? ' active-content' : '' }}" id={{ $lang->lang }}>
            <div class="part left-part">
                <ul>
                    <li>
                        <label for="name-{{ $lang->lang }}">{{trans('variables.title_table')}}  [{{ $lang->lang }}]</label>
                        <input type="text" name="title_{{ $lang->lang }}" class="name"
                            id="title-{{ $lang->lang }}">
                    </li>
                    <li>
                        <label for="description-{{ $lang->lang }}">Description [{{ $lang->lang }}]</label>
                        <textarea name="description_{{ $lang->lang }}"
                            id="description-{{ $lang->lang }}"></textarea>
                    </li>
                    <li class="ckeditor">
                        <label for="body-{{ $lang->lang }}">{{trans('variables.body')}} [{{ $lang->lang }}]</label>
                        <textarea name="body_{{ $lang->lang }}" id="body-{{ $lang->lang }}"
                            data-type="ckeditor"></textarea>
                        <script>
                            CKEDITOR.replace('body-{{ $lang->lang }}', {
                                language: '{{$lang}}',
                            });
                        </script>
                    </li>
                    <li>
                        <label for="seo_title_{{ $lang->lang }}">Button Text [{{ $lang->lang }}]</label>
                        <input type="text" name="btn_text_{{ $lang->lang }}">
                    </li>
                </ul>
            </div>
            <div class="part right-part">
                <ul>
                    <hr>
                    <h6>Seo тексты</h6>
                    <li>
                        <label for="seo_title_{{ $lang->lang }}">Seo Title [{{ $lang->lang }}]</label>
                        <input type="text" name="seo_title_{{ $lang->lang }}"
                            id="seo_title_{{ $lang->lang }}">
                    </li>
                    <li>
                        <label for="seo_descr_{{ $lang->lang }}">Seo Description [{{ $lang->lang }}]</label>
                        <input type="text" name="seo_descr_{{ $lang->lang }}"
                            id="seo_descr_{{ $lang->lang }}">
                    </li>
                    <li>
                        <label for="seo_keywords_{{ $lang->lang }}">Seo Keywords [{{ $lang->lang }}]</label>
                        <input type="text" name="seo_keywords_{{ $lang->lang }}"
                            id="seo_keywords_{{ $lang->lang }}">
                    </li>
                </ul>
                <ul>
                    <hr>
                    <h6>Дополнительно</h6>
                    <li>
                        <label for="img-{{ $lang->lang }}">{{trans('variables.img')}} [{{ $lang->lang }}]</label>
                        <input type="file" name="image_{{ $lang->lang }}" id="img-{{ $lang->lang }}"/>
                    </li>
                    <hr>
                    <li>
                        <label for="img_mob-{{ $lang->lang }}">{{trans('variables.img')}} Mob [{{ $lang->lang }}]</label>
                        <input type="file" name="image_mob_{{ $lang->lang }}" id="img_mob-{{ $lang->lang }}"/>
                    </li>
                </ul>
            </div>
        </div>
        @endforeach
        @endif
        <div class="part left-part">
            <ul>
                <li>
                    <label for="discount">Discount</label>
                    <input type="number" name="discount" id="discount"/>
                </li>
                <li><br>
                    <input type="submit" value="{{trans('variables.save_it')}}" data-form-id="add-form">
                </li>
            </ul>
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
