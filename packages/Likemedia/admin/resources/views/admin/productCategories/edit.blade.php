@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('product-categories.index') }}">Product Categories</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Category</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Edit Category </h3>
</div>


<div class="card">
    <div class="card-block">

        <div class="row">
            <form class="form-reg" method="post" action="{{ route('product-categories.update', $category->id) }}" enctype="multipart/form-data">
                <div class="col-md-7">
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

                        {{ csrf_field() }} {{ method_field('PATCH') }}
                        <input type="hidden" name="dependable-status" value="false">
                        <input type="hidden" name="submit-status" value="false">
                        @if (!empty($langs))
                        @foreach ($langs as $key => $lang)
                        <div class="tab-content {{ $key == 0 ? ' active-content' : '' }}" id={{ $lang->
                            lang }}>
                            <div class="part full-part">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{trans('variables.title_table')}}[{{ $lang->lang }}]</label>
                                            <input type="text" name="name_{{ $lang->lang }}" class="form-control"
                                            @foreach($category->translations as $translation)
                                            @if ($translation->lang_id == $lang->id)
                                            value="{{ $translation->name }}"
                                            @endif
                                            @endforeach
                                            >
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Subtitle[{{ $lang->lang }}]</label>
                                            <input type="text" name="subtitle_{{ $lang->lang }}" class="form-control"
                                            @foreach($category->translations as $translation)
                                            @if ($translation->lang_id == $lang->id)
                                            value="{{ $translation->subtitle }}"
                                            @endif
                                            @endforeach
                                            >
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Description[{{ $lang->lang }}]</label>
                                        @foreach($category->translations as $translation)
                                            @if($translation->lang_id == $lang->id)
                                        <textarea name="description_{{ $lang->lang }}" class="form-control" id="body-{{ $lang->lang }}" data-type="ckeditor">
                                            {{ $translation->description }}
                                        </textarea>
                                            <script>
                                                CKEDITOR.replace('body-{{ $lang->lang }}', {
                                                    language: '{{$lang}}',
                                                    height: '200px'
                                                });
                                            </script>
                                        @endif
                                    @endforeach
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Seo Title[{{ $lang->lang }}]</label>
                                        <input type="text" name="seo_title_{{ $lang->lang }}" class="form-control"
                                        @foreach($category->translations as $translation)
                                        @if ($translation->lang_id == $lang->id)
                                        value="{{ $translation->seo_title }}"
                                        @endif
                                        @endforeach
                                        >
                                    </div>
                                    <div class="col-md-4">
                                        <label>Seo Description[{{ $lang->lang }}]</label>
                                        <input type="text" name="seo_description_{{ $lang->lang }}" class="form-control"
                                        @foreach($category->translations as $translation)
                                        @if ($translation->lang_id == $lang->id)
                                        value="{{ $translation->seo_description }}"
                                        @endif
                                        @endforeach
                                        >
                                    </div>
                                    <div class="col-md-4">
                                        <label>Seo Keywords[{{ $lang->lang }}]</label>
                                        <input type="text" name="seo_keywords_{{ $lang->lang }}" class="form-control"
                                        @foreach($category->translations as $translation)
                                        @if ($translation->lang_id == $lang->id)
                                        value="{{ $translation->seo_keywords }}"
                                        @endif
                                        @endforeach
                                        >
                                    </div>
                                    <div class="col-md-12">
                                        <label>Seo Text[{{ $lang->lang }}]</label>
                                        <textarea name="seo_text_{{ $lang->lang }}" height="300" class="form-control">@foreach($category->translations as $translation)@if($translation->lang_id == $lang->id){{ $translation->seo_text }}@endif @endforeach</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <hr>
                                        <h6 class="text-center">Images</h6>
                                    <hr>
                                    <div class="col-md-6">
                                        <li>
                                            @foreach($category->translations as $translation)
                                            @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                                @if ($translation->banner_desktop)
                                                <img src="{{ asset('images/categories/og/'. $translation->banner_desktop ) }}" style="height:100px;"><br>
                                                <input type="hidden" name="old_banner_desktop_{{ $lang->lang }}" value="{{ $translation->banner_desktop }}"/>
                                                @else
                                                    <img src="{{ asset('admin/img/noimage.jpg') }}" style="height:100px;">
                                                @endif
                                            @endif
                                            @endforeach

                                            <label for="img-{{ $lang->lang }}">Banner desktop [{{ $lang->lang }}]</label><br>
                                            <input type="file" name="banner_desktop_{{ $lang->lang }}" id="img-{{ $lang->lang }}"/>
                                        </li>
                                    </div>
                                    <div class="col-md-6">
                                        <li>
                                            @foreach($category->translations as $translation)
                                            @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                                @if ($translation->banner_mobile)
                                                <img src="{{ asset('images/categories/og/'. $translation->banner_mobile ) }}" style="height:100px;"><br>
                                                <input type="hidden" name="old_banner_mobile_{{ $lang->lang }}" value="{{ $translation->banner_mobile }}"/>
                                                @else
                                                    <img src="{{ asset('admin/img/noimage.jpg') }}" style="height:100px;">
                                                @endif
                                            @endif
                                            @endforeach

                                            <label for="img-{{ $lang->lang }}">Banner mobile [{{ $lang->lang }}]</label><br>
                                            <input type="file" name="banner_mobile_{{ $lang->lang }}" id="img-{{ $lang->lang }}"/>
                                        </li>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12"><hr>
                                        @php
                                            $checked = $category->parametersGroups->pluck('group_id')->toArray();
                                        @endphp
                                        @if ($groups->count() > 0)
                                            <h5>Parameters Groups:</h5>
                                            @foreach ($groups as $key => $group)
                                                <div class="form-group">
                                                    <label for="group{{ $group->id }}">
                                                        <input type="checkbox" name="parameter_groups[{{ $group->id }}]" id="group{{ $group->id }}"  value="{{ $group->id }}" {{ in_array($group->id, $checked) ? 'checked' : '' }}>
                                                        <span> {{ trans('vars.Parameter_groups.' . $group->key) }} [{{ $group->key }}]</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-md-5">
                        <div class="form-group">
                            <label for="alias">Alias</label>
                            <input id="alias" type="text" name="alias" class="form-control" value="{{ $category->alias }}">
                        </div>

                        <div class="form-group">
                            <label for="number">Number</label>
                            <input id="number" type="text" name="number" class="form-control" value="{{ $category->number }}">
                        </div>

                        <div class="form-group">
                            <label for="">Products type</label> <br>
                            <select class="form-control" name="product_type">
                                <option value="0">---</option>
                                <option value="pre-order" {{ $category->product_type == 'pre-order' ? 'selected' : '' }}>Pre-order</option>
                                <option value="order" {{ $category->product_type == 'order' ? 'selected' : '' }}>Order</option>
                                <option value="show/pre-order" {{ $category->product_type == 'show/pre-order' ? 'selected' : '' }}>Show/Pre-order</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>
                                <input class="checkbox" type="checkbox" name="on_home" {{ $category->on_home == 1 ? 'checked' : ''}}>
                                <span>Display on home</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                <input class="checkbox" type="checkbox" name="active" {{ $category->active == 1 ? 'checked' : ''}}>
                                <span>Active</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                <input class="checkbox" type="checkbox" name="loungewear" {{ $category->homewear == 1 ? 'checked' : ''}}>
                                <span>Homewear</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                <input class="checkbox" type="checkbox" name="jewelry" {{ $category->bijoux == 1 ? 'checked' : ''}}>
                                <span>Bijoux</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="img">select an icon</label>
                            <input type="file" name="icon" id="img"/><br>
                            @if ($category->icon)
                                <input type="hidden" name="old_icon" value="{{ $category->icon }}"/>
                                <img src="{{ asset('/images/categories/og/'. $category->icon ) }}" style="height:70px;">
                            @else
                                <img src="{{ asset('admin/img/noimage.jpg') }}" style="height:70px;">
                            @endif
                        </div> <hr>
                        <div class="form-group">
                            <label for="img">select banner desktop [one lang]</label>
                            <input type="file" name="banner_desktop" id="banner_desktop"/><br>
                            @if ($category->banner_desktop)
                                <input type="hidden" name="old_banner_desktop" value="{{ $category->banner_desktop }}"/>
                                <img src="{{ asset('/images/categories/og/'. $category->banner_desktop ) }}" style="height:70px;">
                                <a href="{{ url('back/product-categories/delete-banner/desktop/'. $category->id) }}"><small><i class="fa fa-remove"></i> remove</small></a>
                            @else
                                <img src="{{ asset('admin/img/noimage.jpg') }}" style="height:70px;">
                            @endif
                        </div> <hr>
                        <div class="form-group">
                            <label for="img">select banner mobile [one lang]</label>
                            <input type="file" name="banner_mobile" id="banner_mobile"/><br>
                            @if ($category->banner_mobile)
                                <input type="hidden" name="old_banner_mobile" value="{{ $category->banner_mobile }}"/>
                                <img src="{{ asset('/images/categories/og/'. $category->banner_mobile ) }}" style="height:70px;">
                                <a href="{{ url('back/product-categories/delete-banner/mobile/'. $category->id) }}"><small><i class="fa fa-remove"></i> remove</small></a>
                            @else
                                <img src="{{ asset('admin/img/noimage.jpg') }}" style="height:70px;">
                            @endif
                        </div> <hr>

                        @php
                             $property = 0;
                        @endphp
                        @include('admin::admin.productCategories.propertiesTree')

                    </div>
                </div>

                <div class="row text-center">
                    <div class="col-md-12"> <br><hr>
                        <input type="submit" class="btn btn-primary" value="{{trans('variables.save_it')}}" class="submit">
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
