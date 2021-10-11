@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.category' , ['category' => Request::get('category')]) }}">Produse</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Product</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Create Product
        @if (!is_null($category))
            @if (!is_null($category->translation->first()))
                [ {{ $category->translation->first()->name }} ]
            @endif
        @endif
    </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('products.create', ['category' => Request::get('category')])
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">
    <form class="form-reg" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-4">
                <ul>
                    <li>
                        <label>Categorie</label>
                        <select name="category_id">
                        @foreach($categories as $categoryOne)
                        <option value="{{ $categoryOne->id }}" {{ Request::get('category') == $categoryOne->id ? 'selected' : '' }}>{{ $categoryOne->translation($lang->id)->first()->name }}</option>
                        @endforeach
                        </select>
                        @if (Request::get('category'))
                            <a class="btn btn-primary btn-sm" href="{{ url('/back/products/category/'.Request::get('category')) }}"><< Back to category</a>
                        @endif
                    </li>
                </ul>
            </div>

            <div class="col-md-4">
                <ul>
                    <li>
                        <label>Promotion</label>
                        <select name="prommotion_id">
                        <option value="0">---</option>
                        @foreach($promotions as $promotion)
                        <option value="{{ $promotion->id }}">{{ $promotion->translation($lang->id)->first()->name }}</option>
                        @endforeach
                        </select>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-area">
            @include('admin::admin.alerts')
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
        {{ csrf_field() }}
        @if (!empty($langs))
        @foreach ($langs as $lang)
        <div class="tab-content {{ $loop->first ? ' active-content' : '' }}" id={{ $lang->
            lang }}>
            <div class="part left-part">
                <ul>
                    <li>
                        <label>{{trans('variables.title_table')}} [{{ $lang->lang }}]</label>
                        <input type="text" name="name_{{ $lang->lang }}" class="name"
                            data-lang="{{ $lang->lang }}">
                    </li>
                    <li class="ckeditor">
                        <label>{{trans('variables.description')}} [{{ $lang->lang }}]</label>
                        <textarea name="description_{{ $lang->lang }}"></textarea>
                        <script>
                            CKEDITOR.replace('description_{{ $lang->lang }}', {
                                language: '{{$lang}}',
                            });
                        </script>
                    </li>
                    <li class="ckeditor">
                        <label>{{trans('variables.body')}} [{{ $lang->lang }}]</label>
                        <textarea name="body_{{ $lang->lang }}"></textarea>
                        <script>
                            CKEDITOR.replace('body_{{ $lang->lang }}', {
                                language: '{{$lang}}',
                            });
                        </script>
                    </li>
                    @include('admin::admin.products.propertiesMultilang')
                </ul>
            </div>
            <div class="part right-part">
                <ul>
                    <h6>Seo texts</h6>
                    <li>
                        <label>{{trans('variables.meta_title_page')}} [{{ $lang->lang }}]</label>
                        <input type="text" name="meta_title_{{ $lang->lang }}">
                    </li>
                    <li>
                        <label>{{trans('variables.meta_keywords_page')}} [{{ $lang->lang }}]</label>
                        <input type="text" name="meta_keywords_{{ $lang->lang }}">
                    </li>
                    <li>
                        <label>{{trans('variables.meta_description_page')}} [{{ $lang->lang }}]</label>
                        <input type="text" name="meta_description_{{ $lang->lang }}">
                    </li>
                    <hr>

                </ul>
            </div>
        </div>
        @endforeach
        @endif

        <div class="part left-part">
            @if (!is_null($category))
                @include('admin::admin.products.parametersCreate',  ['category' => $category])
            @endif
        </div>
        <div class="part left-part">
        <ul>
            <li>
                <label>Alias</label>
                <input type="text" name="alias" id="slug-ro">
            </li>
            <li>
                <label>Code</label>
                <input type="text" name="code">
            </li>
            <li>
                <label>Stock</label>
                <input type="number" name="stock"  step="any">
            </li>
            <li>
                <label>Price</label>
                <input type="number" name="price" value="">
            </li>
            <li>
                <label>Discount</label>
                <input type="number" name="discount" value="">
            </li>
            <li>
                <div class="col-md-6">
                    <label>Video</label>
                    <input type="file" name="video" value="">
                </div>
            </li>
            <li>
                <label>Hit</label>
                <input type="checkbox" name="hit">
            </li>
            <li>
                <label>Recomended</label>
                <input type="checkbox" name="recomended">
            </li>

            <hr>
            <h6>Similar products</h6>
            <li>

            </li>
            <li>
                <div class="row">
                    <hr>
                    <div class="col-md-6">
                        Upload  images
                        <div class="form-group">
                              <label for="upload">choice images</label>
                              <input type="file" id="upload_file" name="images[]" onchange="preview_image();" multiple/>
                              <div id="image_preview"></div>
                              <hr>
                        </div>
                    </div>
                    <div class="col-md-8">

                    </div>
                </div>
            </li>
            <li>
                <input type="submit" value="Save">
            </li>
        </ul>
    </div>
    </form>
</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
<script>
    function preview_image(){
        var total_file=document.getElementById("upload_file").files.length;
        for(var i=0; i < total_file; i++){
            $('#image_preview').append(
                "<div class='row append'><div class='col-md-12'><img src='"+URL.createObjectURL(event.target.files[i])+"'alt=''></div><div class='col-md-12'>@foreach ($langs as $key => $lang)<label for=''>Alt[{{ $lang->lang }}]</label><input type='text' name='alt_[{{ $lang->id }}][]'><label for=''>Title[{{ $lang->lang }}]</label><input type='text' name='title_[{{ $lang->id }}][]'>@endforeach </div><hr><br>"
            );
        }
    }

    $().ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            }
        });
    });
</script>
@stop
