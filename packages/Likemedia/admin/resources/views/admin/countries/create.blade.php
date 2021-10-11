@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('countries.index') }}">Countries</a></li>
        <li class="breadcrumb-item active" aria-current="blog">Create Country</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Create Country </h3>
</div>
@include('admin::admin.alerts')
<div class="list-content">
    <div class="card">
        <div class="card-block">
            <form method="POST" class="form-reg" action="{{ route('countries.store') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-6">
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
                            <div class="form-group">
                                <label for="name-{{ $lang->lang }}">Name [{{ $lang->lang }}]</label>
                                <input type="text" name="title_{{ $lang->lang }}" class="form-control" value="">
                            </div>
                        </div>
                        @endforeach
                        @endif
                        <hr>
                        <div class="form-group">
                            <label for="">ISO</label>
                            <input type="text" name="iso" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">ISO alpha-3</label>
                            <input type="text" name="iso3" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Phone Code </label>
                            <input type="number" name="phone_code" value="" class="form-control" placeholder="373">
                        </div>
                        <div class="form-group">
                            <label for="">VAT %</label>
                            <input type="number" step="0.01" name="vat" value="" class="form-control" placeholder="20">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="img">Flag</label>
                                <input type="file" name="flag" id="img"/>
                                <img src="{{ asset('/admin/img/noimage.jpg') }}" style="width: 128px;"><hr>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <hr>
                            <div class="form-group text-center">
                                <label>
                                    <input type="checkbox" name="active">
                                    <span>Active</span>
                                </label>
                            </div>
                            <hr>
                        </div>
                        <div class="col-md-3">
                            <hr>
                            <div class="form-group text-center">
                                <label>
                                    <input type="checkbox" name="main">
                                    <span>Main</span>
                                </label>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Name (native)</label>
                            <input type="text" name="name_native" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Language</label>
                            <select class="form-control" name="lang">
                                @foreach ($langs as $key => $oneLang)
                                    <option value="{{ $oneLang->id }}">{{ $oneLang->lang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Currency</label>
                            <select class="form-control" name="currency">
                                @foreach ($currencies as $key => $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->translation->name }}</option>
                                @endforeach
                            </select>
                        </div> <hr>
                        <div class="col-md-6">
                            <h6>Delivery methods:</h6> <hr>
                            @foreach ($deliveries as $key => $delivery)
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="on_drop_down">
                                        <span> {{ $delivery->translation->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-md-6">
                            <h6>Payment methods:</h6> <hr>
                            @foreach ($payments as $key => $payment)
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="on_drop_down">
                                        <span> {{ $payment->translation->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" value="Save" class="btn btn-primary">
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
