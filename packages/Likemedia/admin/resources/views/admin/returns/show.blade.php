@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="set">Returns</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title">Returns </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
            'Create Return' => url('/back/returns-select-order-to-return'),
        ]
    ])
</div>

@include('admin::admin.alerts')

<div class="card">
    <div class="card-block row">

        <div class="col-md-12">
            <div class="col-md-6">
                <h5 class="text-center">Return information:</h5><hr>
                <div class="col-md-12">
                    <div class="col-md-4">
                        Order hash  :
                    </div>
                    <div class="col-md-8">
                        <b>{{ $retur->order->order_hash }}</b>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4">
                        Return Reason :
                    </div>
                    <div class="col-md-8">
                        <b>{{ $retur->reason }}</b>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4">
                        Refund Method :
                    </div>
                    <div class="col-md-8">
                        <b>{{ $retur->payment }}</b>
                    </div>
                </div>

                @if ($retur->payment == 'bank_account')
                    <div class="col-md-12">
                        <div class="col-md-4">
                            IBAN :
                        </div>
                        <div class="col-md-8">
                            <b>{{ $retur->iban }}</b>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-4">
                            Bank :
                        </div>
                        <div class="col-md-8">
                            <b>{{ $retur->bank }}</b>
                        </div>
                    </div>
                @else
                    <div class="col-md-12">
                        <div class="col-md-4">
                            Paypal Email :
                        </div>
                        <div class="col-md-8">
                            <b>{{ $retur->paypal_email }}</b>
                        </div>
                    </div>
                @endif

                <div class="col-md-12">
                    <div class="col-md-4">
                        User Email :
                    </div>
                    <div class="col-md-8">
                        <b>{{ $retur->user->email }}</b>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4">
                        Return Date :
                    </div>
                    <div class="col-md-8">
                        <b>{{ $retur->datetime }}</b>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4">
                        Additional Info :
                    </div>
                    <div class="col-md-8">
                        <b>{{ $retur->additional }}</b>
                    </div>
                </div>

            </div>

            <div class="col-md-6">
                <h5 class="text-center">Return products:</h5><hr>
                @if ($retur->items()->count() > 0)
                    @php
                        $ammount = 0;
                    @endphp
                    @foreach ($retur->items as $key => $item)
                        <div class="col-md-7">
                            {{ $item->subproduct->product->translation->name }}
                            <b>{{ $item->subproduct->code }}</b>
                        </div>

                        <div class="col-md-3">
                            <small>{{ $item->subproduct->product->mainPrice->price }} {{ $mainCurrency->abbr }}</small>
                        </div>
                        <div class="col-md-2">
                            <small><b>Size</b></small> - {{ $item->subproduct->parameterValue->translation->name  }}
                        </div>
                        @php
                            $ammount += $item->subproduct->product->mainPrice->price;
                        @endphp
                    @endforeach
                @endif


                <div class="col-md-12"> <hr>
                    <p class="text-right text-danger"> <b>SUBTOTAL: {{ $ammount }} {{ $mainCurrency->abbr }}</b> </p>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <hr>
            <h5 class="text-center">User/Shipping information</h5>

            <div class="col-md-8">
                <div class="col-md-12">
                    <div class="col-md-4">
                        Contact name  :
                    </div>
                    <div class="col-md-8">
                        <b>{{ $retur->order->details->contact_name }}</b>
                    </div>
                </div>

                <div class="col-md-12">
                    <hr>
                    <div class="col-md-4">
                        User Email :
                    </div>
                    <div class="col-md-8">
                        <b>{{ $retur->order->details->email }}</b>
                    </div>
                </div>

                <div class="col-md-12">
                    <hr>
                    <div class="col-md-4">
                        User phone :
                    </div>
                    <div class="col-md-8">
                        <b>{{ $retur->order->details->code }} {{ $retur->order->details->phone }}</b>
                    </div>
                </div>

                <div class="col-md-12">
                    <hr>
                    <div class="col-md-4">
                        Order payment :
                    </div>
                    <div class="col-md-8">
                        <b>{{ $retur->order->details->payment }}</b>
                    </div>
                </div>

                <div class="col-md-12">
                    <hr>
                    <div class="col-md-4">
                        Order delivery :
                    </div>
                    <div class="col-md-8">
                        <b>{{ $retur->order->details->delivery }}</b>
                    </div>
                </div>

                <div class="col-md-12">
                    <hr>
                    <div class="col-md-4">
                        Country :
                    </div>
                    <div class="col-md-8">
                        <b>{{ $retur->order->details->country }}</b>
                    </div>
                </div>

                <div class="col-md-12">
                    <hr>
                    <div class="col-md-4">
                        City :
                    </div>
                    <div class="col-md-8">
                        <b>{{ $retur->order->details->city }}</b>
                    </div>
                </div>

                <div class="col-md-12">
                    <hr>
                    <div class="col-md-4">
                        Address :
                    </div>
                    <div class="col-md-8">
                        <b>{{ $retur->order->details->address }}</b>
                    </div>
                </div>

            </div>
            <div class="col-md-4">
                @if ($retur->image)
                    <p>Image</p>
                    <img src="/images/returns/{{ $retur->image }}" style="width: 100%;">
                @endif
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
