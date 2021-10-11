@extends('front.mobile.app')
@section('content')
@include('front.mobile.partials.header')
<main class="clientArea">
    <div class="container">
        <h3>{{ trans('vars.Cabinet.yourPromocodes') }}</h3>
        <div class="row">
            <div class="col-12">
                <a href="{{ url()->previous() }}"><div class="undoStatic"></div></a>
            </div>
            <div class="col-12">
                <div class="user">
                    <p>{{ trans('vars.General.hello') }}, {{ $userdata->name }}</p>
                    <p>{{ trans('vars.Cabinet.welcomeTo') }} {{ trans('vars.Cabinet.yourPromocodes') }}</p>
                </div>
            </div>
            <div class="col-12">
                <div class="navArea" id="navArea">
                    <div id="pageSelected">
                        {{ trans('vars.Cabinet.yourPromocodes') }}
                        <svg
                            width="12px"
                            height="6px"
                            viewBox="0 0 12 6"
                            version="1.1"
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            >
                            <g id="AnaPopova-Site" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g
                                    id="Cabinet_Mob._375-cos"
                                    transform="translate(-325.000000, -156.000000)"
                                    fill="#B22D00"
                                    fill-rule="nonzero"
                                    >
                                    <polygon
                                        id="Shape"
                                        transform="translate(331.000000, 159.000000) scale(1, -1) translate(-331.000000, -159.000000) "
                                        points="331 156 325 162 330.716323 162 337 162"
                                        ></polygon>
                                </g>
                            </g>
                        </svg>
                    </div>
                    @include('front.mobile.account.accountMenu')
                </div>
            </div>
            <div class="col">
                <div class="row promocodes">
                    @if ($promocodes->count() > 0)
                    @foreach ($promocodes as $key => $promocode)
                    @php
                    $promocodeClass = "validNow";
                    if ($promocode->times <= $promocode->to_use) $promocodeClass = "usedNow";
                    if ($promocode->valid_to <= date('Y-m-d')) $promocodeClass = "expired";
                    @endphp
                    <div class="col-md-4">
                        <div class="promoItem {{ $promocodeClass }}">
                            <div class="log">
                                <p>{{ trans('vars.Promocode.jewerlyBy') }}</p>
                                <img src="/fronts/img/jewrly/logo.svg" alt="logo promo" />
                            </div>
                            <div class="discount">
                                <p>{{ trans('vars.Promocode.discount') }}</p>
                                <p>{{ $promocode->discount }}%</p>
                            </div>
                            <p class="code">
                                {{ trans('vars.Promocode.voucherCode') }}: {{ $promocode->name }}
                            </p>
                            <div class="validity">
                                <p>{{ trans('vars.Promocode.validTill') }} {{ $promocode->valid_to }}</p>
                                <p>{{ trans('vars.Promocode.couponStatus') }}: {{ $promocode->status }}</p>
                            </div>
                            <div class="usedFor">
                                <p>{{ trans('vars.Promocode.canBeUsed') }}:  {{ $promocode->treshold }} {{ $mainCurrency->abbr }}</p>
                            </div>
                            <div class="log">
                                <p>{{ trans('vars.Promocode.loungewearBy') }}</p>
                                <img src="/fronts/img/icons/logo.svg" alt="logo promo" />
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="col orderHistory">
                        <p class="text-center">{{ trans('vars.Promocode.youHaveNoPromocodes') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</main>
@include('front.mobile.partials.footer')
@stop
