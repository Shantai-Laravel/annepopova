@extends('front.app')
@section('content')
    <div class="fullWidthHeader">
        @include('front.partials.header')
    </div>
    <main>
        <div class="cabinet" id="bodyScroll">
            <ul class="crumbs">
                <li>
                    <a href="{{ url('/'.$lang->lang) }}">Home</a>
                </li>
                <li><a href="#">{{ trans('vars.Cabinet.returns') }}</a></li>
            </ul>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h5>{{ trans('vars.Cabinet.returns') }}</h5>
                    </div>
                    <div class="col-lg-4 col-md-5">
                        <div class="cabinetNavBloc">
                            <div class="cabNavTitle">
                                {{ trans('vars.General.hello') }}, {{ $userdata->name }}
                            </div>
                            @include('front.account.accountMenu')
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-7">
                        @if ($return->items()->count() > 0)
                            @foreach ($return->items as $key => $subproduct)
                                @if (!is_null($subproduct->subproduct->product))
                                <div class="row productsCommands align-items-center justify-content-between">
                                    <div class="col-md-7 col-7 d-flex align-items-center">
                                        @if ($subproduct->subproduct->product->mainImage)
                                            <img src="/images/products/og/{{ $subproduct->subproduct->product->mainImage->src }}" alt="" />
                                        @else
                                            <img src="" alt="" />
                                        @endif
                                        <a href="{{ url('/'.$lang->lang.'/catalog/'.$subproduct->subproduct->product->category->alias.'/'.$subproduct->subproduct->product->alias) }}" class="nameProduct">{{ $subproduct->subproduct->product->translation->name }}</a>
                                    </div>
                                    <div class="col-md-auto col-2">
                                        {{ $subproduct->qty }} {{ trans('vars.Cabinet.pieces') }}
                                    </div>
                                    <div class="col-md-auto col-2">
                                        @if ($subproduct->subproduct->product->mainPrice)
                                            {{ $subproduct->subproduct->product->mainPrice->price }} {{ $mainCurrency->abbr }}
                                        @endif
                                    </div>
                                    <div class="col-md-auto col-1">
                                        @if ($subproduct->return_id)
                                            <b>returned</b>
                                        @else
                                            <label class="checkContainer">
                                                <input type="checkbox"  name="item_id[]" value="{{ $subproduct->id }}">
                                                <span></span>
                                            </label>
                                        @endif
                                    </div>
                                </div>
                                @else
                                    <div class="row productsCommands align-items-center justify-content-between">
                                        <p class="alert alert-danger">Product was deleted!</p>
                                    </div>
                                @endif
                            @endforeach
                        @endif

                        <div class="row justify-content-end">
                            <div class="col-12">
                                <div class="row justify-content-between adressBloc">
                                    <div class="col-lg-6 col-md-12">
                                        <p>
                                            Order hash : <b>{{ $return->order->order_hash }}</b>
                                        </p>
                                        <p>
                                            Return Reason : <b>{{ $return->reason }}</b>
                                        </p>
                                        <p>
                                            Refund Method : <b>{{ $return->payment }}</b>
                                        </p>
                                        @if ($return->payment == 'bank_account')
                                            <p>
                                                IBAN : <b>{{ $return->iban }}</b>
                                            </p>
                                            <p>
                                                Bank : <b>{{ $return->bank }}</b>
                                            </p>
                                        @else
                                            <p>
                                                Paypal Email : <b>{{ $return->paypal_email }}</b>
                                            </p>
                                        @endif

                                        @if ($return->image)
                                            <p>Image</p>
                                            <img src="/images/returns/{{ $return->image }}">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('front.partials.footer')
@stop
