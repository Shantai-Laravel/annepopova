@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/back/collections') }}">Collections</a></li>
        <li class="breadcrumb-item"><a href="{{ url('back/sets/collection/'.Request::get('collection')) }}">Seturi</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Set</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Create Set </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('sets.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">
    <form class="form-reg" role="form" method="POST" action="{{ route('sets.store') }}" id="add-form"
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
                        <label for="seo_text_{{ $lang->lang }}">Seo Text [{{ $lang->lang }}]</label>
                        <textarea name="seo_text_{{ $lang->lang }}"></textarea>
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

                <hr>
                <div class="col-md-3">
                    <li>
                        <label for="discount">Material</label>
                        @php $material = GetParameterValues('material'); @endphp
                        @if (!is_null($material))
                            <select name="material">
                                <option value="0">---</option>
                                @if (count($material->parameterValues()) > 0)
                                    @foreach ($material->parameterValues as $key => $materialValue)
                                        <option value="{{ $materialValue->id }}">{{ $materialValue->translation->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        @endif

                    </li>
                </div>
                <div class="col-md-3">
                    <li>
                        <label for="discount">Color</label>
                        @php $color = GetParameterValues('color'); @endphp
                        @if (!is_null($color))
                            <select name="color">
                                <option value="0">---</option>
                                @if (count($color->parameterValues()) > 0)
                                    @foreach ($color->parameterValues as $key => $colorValue)
                                        <option value="{{ $colorValue->id }}">{{ $colorValue->translation->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        @endif
                    </li>
                </div>
                <div class="col-md-3">
                    <li>
                        <label for="discount">Room</label>
                        @php $room = GetParameterValues('room'); @endphp
                        @if (!is_null($room))
                            <select name="room">
                                <option value="0">---</option>
                                @if (count($room->parameterValues()) > 0)
                                    @foreach ($room->parameterValues as $key => $roomValue)
                                        <option value="{{ $roomValue->id }}">{{ $roomValue->translation->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        @endif
                    </li>
                </div>
                <div class="col-md-3">
                    <li>
                        <label for="discount">Employment</label>
                        @php $employment = GetParameterValues('employment'); @endphp
                        @if (!is_null($employment))
                            <select name="employment">
                                <option value="0">---</option>
                                @if (count($employment->parameterValues()) > 0)
                                    @foreach ($employment->parameterValues as $key => $employmentValue)
                                        <option value="{{ $employmentValue->id }}">{{ $employmentValue->translation->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        @endif
                    </li>
                </div>


                <div class="col-md-3">
                    <li>
                        <label for="price">Price</label>
                        <input type="number" name="price" id="price"/>
                    </li>
                </div>
                <div class="col-md-3">
                    <li>
                        <label for="code">Code</label>
                        <input type="text" name="code" id="code"/>
                    </li>
                </div>
                <div class="col-md-3">
                    <li>
                        <label for="discount">Discount %</label>
                        <input type="number" name="discount" id="discount"/>
                    </li>
                </div>
                <div class="col-md-3">
                    <li>
                        <label for="discount">Atribuie la collectie</label>
                        <select name="collection_id">
                            @if (count($collections) > 0)
                                @foreach ($collections as $key => $collection)
                                    <option value="{{ $collection->id }}" {{ Request::get('collection') == $collection->id ? 'selected' : '' }}>{{ $collection->translation($lang->id)->first()->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </li>
                </div>
                <div class="col-md-3">
                    <li><br>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="on_home">
                                <span>Display on Homepage</span>
                            </label>
                        </div>
                    </li>
                </div>
                <div class="col-md-12"><hr>
                    <h6 class="text-center">Photo/Video Gallery</h6>
                    <div class="col-md-6">
                        <p class="text-center">Photos</p>

                        <li>
                            <label for="photos">Add photos here</label>
                            <input type="file" name="photos[]" value="" multiple>
                        </li>
                    </div>
                    <div class="col-md-6">
                        <p class="text-center">Videos</p>

                        <li>
                            <label for="video">Add video iframe here</label>
                            <input type="text" name="video" value="">
                        </li>
                    </div>
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
