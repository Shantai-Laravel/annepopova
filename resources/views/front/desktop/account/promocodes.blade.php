@extends('front.desktop.app')
@section('content')
@include('front.desktop.partials.header')
<main class="clientArea">
    <div class="container">
        <h3>{{ trans('vars.Cabinet.yourPromocodes') }}</h3>
        <div class="row">
            <div class="col-auto">
                <div class="navArea" id="navArea">
                    <div class="user">
                        <p>{{ trans('vars.General.hello') }}, {{ $userdata->name }}</p>
                        <p>{{ trans('vars.Cabinet.welcomeTo') }} {{ trans('vars.Cabinet.yourPromocodes') }}</p>
                    </div>
                    @include('front.desktop.account.accountMenu')
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
                                <p>{{ trans('vars.Promocode.canBeUsed') }}: {{ $promocode->treshold }} {{ $mainCurrency->abbr }}</p>
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
@include('front.desktop.partials.footer')
@stop
