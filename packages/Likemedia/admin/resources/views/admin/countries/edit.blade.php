@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('countries.index') }}">Countries</a></li>
        <li class="breadcrumb-item active" aria-current="blog">Edit Country</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Edit Country </h3>
</div>
@include('admin::admin.alerts')
<div class="list-content">
    <div class="card">
        <div class="card-block">
            <form class="form-reg" role="form" method="POST" action="{{ route('countries.update', $country->id) }}" id="add-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
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
                            @foreach($country->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <div class="form-group">
                                    <label for="name-{{ $lang->lang }}">Name [{{ $lang->lang }}]</label>
                                    <input type="text" name="title_{{ $lang->lang }}" class="form-control" value="{{ $translation->name }}">
                                </div>
                                @endif
                            @endforeach
                        </div>
                        @endforeach
                        @endif
                        <hr>
                        <div class="form-group">
                            <label for="">ISO</label>
                            <input type="text" name="iso" value="{{ $country->iso }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">ISO alpha-3</label>
                            <input type="text" name="iso3" value="{{ $country->iso3 }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Phone Code </label>
                            <input type="number" name="phone_code" value="{{ $country->phone_code }}" class="form-control" placeholder="373">
                        </div>
                        <div class="form-group">
                            <label for="">VAT %</label>
                            <input type="number" step="0.01" name="vat" value="{{ $country->vat }}" class="form-control" placeholder="20">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="img">Flag</label>
                                <input type="file" name="flag" id="img"/>
                                @if ($country->flag)
                                    <img src="{{ asset('images/flags/128x128/'. $country->flag ) }}" style="width: 128px;">
                                    <input type="hidden" name="flag_old" value="{{ $country->flag }}"/>
                                @else
                                    <img src="{{ asset('/admin/img/noimage.jpg') }}" style="width: 128px;">
                                @endif
                                <hr>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <hr>
                            <div class="form-group text-center">
                                <label>
                                    <input type="checkbox" name="active" {{ $country->active == 1 ? 'checked' : '' }}>
                                    <span> Active</span>
                                </label>
                            </div>
                            <hr>
                        </div>
                        <div class="col-md-3">
                            <hr>
                            <div class="form-group text-center">
                                <label>
                                    <input type="checkbox" name="main" {{ $country->main == 1 ? 'checked' : '' }}>
                                    <span> Main</span>
                                </label>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Name (native)</label>
                            <input type="text" name="name_native" value="{{ $country->name }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Language</label>
                            <select class="form-control" name="lang">
                                @foreach ($langs as $key => $oneLang)
                                    <option value="{{ $oneLang->id }}" {{ $country->lang_id ==  $oneLang->id ? 'selected' : ''}}>{{ $oneLang->lang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Currency</label>
                            <select class="form-control" name="currency">
                                @foreach ($currencies as $key => $currency)
                                    <option value="{{ $currency->id }}" {{ $country->currency_id == $currency->id ? 'selected' : ''}}>{{ $currency->translation->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Warehouse</label>
                            <select class="form-control" name="warehouse">
                                @foreach ($warehouses as $key => $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ $country->warehouse_id == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <div class="col-md-6">
                            <h6>Delivery methods:</h6> <hr>
                            @foreach ($deliveries as $key => $delivery)
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="deliveries[{{ $delivery->id }}]" {{ in_array($delivery->id, $country->deliveries()->pluck('delivery_id')->toArray()) ? 'checked' : '' }}>
                                        <span> {{ $delivery->translation->name }}</span>
                                    </label>
                                    <i class="fa fa-arrow-right arrow-del pull-right"></i>
                                    <div class="deliveryInfo">
                                        @php
                                            $deliveryCountry = getDeliveryCountry($delivery->id, $country->id);
                                        @endphp
                                        <div class="input-group margin-0">
                                            <input type="number" step="0.01" name="deliveryPrice[{{ $delivery->id }}]" value="{{ $deliveryCountry ? $deliveryCountry->price : $delivery->price }}" class="form-control">
                                            <span class="input-group-addon">Price </span>
                                        </div>
                                        <div class="input-group margin-0">
                                            <input type="text" name="deliveryTime[{{ $delivery->id }}]" value="{{ $deliveryCountry ? $deliveryCountry->delivery_time : $delivery->delivery_time }}" class="form-control">
                                            <span class="input-group-addon">Time</span>
                                        </div>
                                        <div class="text-center">
                                            <label>
                                                @if ($deliveryCountry)
                                                    <input type="checkbox" name="deliveryInherit[{{ $delivery->id }}]" value="" class="inheritDelPrice" {{ $deliveryCountry->inherit_price == 1 ? 'checked' : '' }}>
                                                @else
                                                    <input type="checkbox" name="deliveryInherit[{{ $delivery->id }}]" value="" class="inheritDelPrice" checked>
                                                @endif
                                                <span><small>Inherit delivery price?</small></span>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-md-6">
                            <h6>Payment methods:</h6> <hr>
                            @foreach ($payments as $key => $payment)
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="payments[{{ $payment->id }}]" {{ in_array($payment->id, $country->payments()->pluck('payment_id')->toArray()) ? 'checked' : '' }}>
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
