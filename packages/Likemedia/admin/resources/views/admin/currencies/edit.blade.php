@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('currencies.index') }}">Currency</a></li>
        <li class="breadcrumb-item active" aria-current="blog">Edit currency</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Edit currency </h3>
</div>
@include('admin::admin.alerts')
<div class="list-content">
    <div class="card">
        <div class="card-block">
            <div class="row">
                <div class="col-md-9">


            <form class="form-reg" role="form" method="POST" action="{{ route('currencies.update', $currency->id) }}" id="add-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="row">
                    <div class="col-md-12">
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
                            @foreach($currency->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <div class="form-group">
                                    <label for="name-{{ $lang->lang }}">Name [{{ $lang->lang }}]</label>
                                    <input type="text" name="title_{{ $lang->lang }}" class="form-control" value="{{ $translation->name }}">
                                </div>
                                @endif
                            @endforeach
                            <hr>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Abbreviation</label>
                            <input type="text" name="abbr" class="form-control" value="{{ $currency->abbr }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Exchange rate <small>[ {{ @$mainCurrency->abbr }} ({{ @$mainCurrency->translation()->name }})]</small> </label>
                            <input type="number" step="0.01" name="rate" class="form-control" value="{{ $currency->rate }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Correction factor </label>
                            <input type="number" step="0.01" name="correction_factor" class="form-control" value="{{ $currency->correction_factor }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" name="type">
                                <option value="1" {{ $currency->type == 1 ? 'selected' : '' }}>Main</option>
                                <option value="0" {{ $currency->type == 0 ? 'selected' : '' }}>Secondary</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <hr>
                            <label>
                                <input type="checkbox" name="exchange_dependable" {{ $currency->exchange_dependable ? 'checked' : '' }}>
                                <span> Exchange Dependable </span>
                            </label>  |  
                            <label>
                                <input type="checkbox" name="active" {{ $currency->active ? 'checked' : '' }} {{  $currency->type ? 'disable' : '' }}>
                                <span> Active </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" value="Save" class="btn btn-primary">
                </div>
            </form>
        </div>
        <div class="col-md-3">
            <span class="label label-warning info-absolute">Info</span>
            <h6>Countries: </h6>
            @if (count($currency->countries) > 0)
                <ol>
                    @foreach ($currency->countries as $key => $country)
                        <li>{{ $key + 1 }}. {{ $country->translation->name }}</li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>
        </div>
    </div>
</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
