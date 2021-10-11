@extends('../front.desktop.app')
@section('content')
@include('front.desktop.partials.header')
<main class="thanksContent">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <h3>{{ trans('vars.PagesThankYou.thankMessage') }} {{ $user->name }}!</h3>
            </div>
            <div class="col-lg-8">
                <img src="/fronts/img/backgrounds/thanksbox.png" alt="">
            </div>

            @php
                $ammount = 0;
            @endphp

            <div class="col-lg-10">
                <h5>{{ trans('vars.PagesThankYou.ourOrder') }}</h5>
                <div class="row productsList">
                    <div class="col-12">
                        @if ($order->orderProducts()->count() > 0)
                        @foreach ($order->orderProducts as $key => $product)
                        @if (!is_null($product->product))
                        <div class="row cartItem fbq-content" data-id="{{ $product->product->code }}" data-qty="{{ $subproduct->qty }}">
                            <a href="{{ url('/'.$lang->lang.'/'.$site.'/catalog/'.$product->product->category->alias.'/'.$product->product->alias) }}">
                            @if ($product->product->mainImage)
                                <img src="/images/products/og/{{ $product->product->mainImage->src }}" alt="" />
                            @else
                                <img src="" alt="" />
                            @endif
                            </a>
                            <div class="description col-md">
                                <a href="{{ url('/'.$lang->lang.'/'.$site.'/catalog/'.$product->product->category->alias.'/'.$product->product->alias) }}">
                                    {{ $product->product->translation->name }}
                                </a>
                                <div class="params">
                                    <span>{{ trans('vars.DetailsProductSet.qty') }}: {{ $product->qty }}</span>
                                </div>
                            </div>
                            <div class="price col-auto">
                                @if ($product->product->personalPrice)
                                    <span>{{ $product->product->personalPrice->price }} {{ $currency->abbr }}</span>
                                @endif
                                @php
                                    $ammount +=  $product->product->personalPrice->price * $product->qty;
                                @endphp
                                <span></span>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @endif

                        @if ($order->orderSubproducts()->count() > 0)
                        @foreach ($order->orderSubproducts as $key => $subproduct)
                        @if (!is_null($subproduct->product))
                        <div class="row cartItem fbq-content" data-id="{{ $subproduct->product->code }}" data-qty="{{ $subproduct->qty }}">
                            <a href="{{ url('/'.$lang->lang.'/'.$site.'/catalog/'.$subproduct->product->category->alias.'/'.$subproduct->product->alias) }}">
                            @if ($subproduct->product->mainImage)
                                <img src="/images/products/og/{{ $subproduct->product->mainImage->src }}" alt="" />
                            @else
                                <img src="" alt="" />
                            @endif
                            </a>
                            <div class="description col-md">
                                <a href="{{ url('/'.$lang->lang.'/'.$site.'/catalog/'.$subproduct->product->category->alias.'/'.$subproduct->product->alias) }}">
                                    {{ $subproduct->product->translation->name }}
                                </a>
                                <div class="params">
                                    <span>{{ trans('vars.DetailsProductSet.qty') }}: {{ $subproduct->qty }}</span>
                                    <span>{{ trans('vars.DetailsProductSet.size') }}: {{ $subproduct->subproduct->parameterValue->translation->name }}</span>
                                </div>
                                <div class="methods">
                                    <div class="methods"></div>
                                </div>
                            </div>
                            <div class="price col-auto">
                                @if ($subproduct->product->personalPrice)
                                    <span>{{ $subproduct->product->personalPrice->price }} {{ $currency->abbr }}</span>
                                @endif
                                @php
                                    $ammount +=  $subproduct->product->personalPrice->price * $subproduct->qty;
                                @endphp
                                <span></span>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
            @php
                $discount = $ammount - ($ammount - ($ammount * $order->discount / 100))
            @endphp

            <h5>Amount - {{ $ammount - $discount + $order->shipping_price }} {{ $order->currency->abbr }}</h5>
            <div class="col-lg-10">
            @if ($order->discount > 0)
                <h5 class="text-center"><small>Discount - {{ $discount }} {{ $order->currency->abbr }}</small></h5>
            @endif
            <div id="_purchase" data="{{ $ammount + $order->shipping_price }}"></div>
            </div>

            <div class="col-lg-10 thanks">

                <h5>{{ trans('vars.PagesThankYou.row1') }}:</h5>
                <p>
                    {{ trans('vars.PagesThankYou.thanksTrackOrderByEmail') }}
                </p>
                <p>
                    @if ($order->invoice_file)
                        <a href="/pdf/{{ $order->invoice_file }}" download> <small>Download Invoce (ro)</small> </a>
                    @endif
                </p>

                @if ($promocode)
                    <h5>{{ trans('vars.PagesThankYou.giftTitle') }}</h5>
                    <p>
                        {{ trans('vars.PagesThankYou.EnterPromocode') }}
                        {{ $promocode->name }}
                        {{ trans('vars.PagesThankYou.whenYouMake') }}
                        {{ $promocode->treshold }}
                        {{ trans('vars.PagesThankYou.euroOrMore') }}
                        {{ date("j F Y", strtotime($promocode->valid_to)) }}
                        {{ trans('vars.PagesThankYou.andEnjoy') }}
                        {{ $promocode->discount }}
                        {{ trans('vars.PagesThankYou.off') }}
                    </p>
                @endif
                <p class="continue">{{ trans('vars.PagesThankYou.row3') }}</p>
                <div class="buttGroups">
                    <a class="butt" href="{{ '/' . $lang->lang. '/homewear' }}">{{ trans('vars.General.HomewearStore') }}</a>
                    <a class="butt" href="{{ '/' . $lang->lang. '/bijoux' }}">{{ trans('vars.General.BijouxBoutique') }}</a>
                </div>
            </div>
        </div>
    </div>
</main>



@include('front.desktop.partials.footer')
@stop


@section('purchase-event')
    @if (Request::route()->getName() == 'thanks')
    <script>
    if ($("#_purchase").length > 0){
        let ammount = '{{ $order->amount - ($order->amount * $order->discount / 100) }}';
        let currency = document.documentElement.getAttribute("main-currency");
        let contents = [];
        ammount = parseFloat(ammount);

        for (var i = 0; i < $('.fbq-content').length; i++) {
            contents.push({
                            'id' : $('.fbq-content').eq(i).attr('data-id'),
                            'quantity': $('.fbq-content').eq(i).attr('data-qty')
                        });
        }

        fbq("track", "Purchase", {
            contents: contents,
            content_type: 'product',
            value: ammount.toFixed(2),
            currency: currency
        });

        dataLayer.push({
            'event': 'eec.purchase',
            'ecommerce' : {
                'currencyCode': 'EUR',
                'purchase' :{
                    'actionField': {
                        'id': "{{ $order->order_hash }}",
                        'affiliation': 'Online Store',
                        'revenue': ammount.toFixed(2),
                        'shipping': '{{ $order->shipping_price }}',
                        'coupon': '{{ @$order->details->promocode }}'
                    },
                    'products' : [
                        @foreach ($order->orderSubproducts as $key => $subproduct)
                        {
                            'id'    : '{{ $subproduct->subproduct->product->code }}',
                            'name'  : '{{ $subproduct->subproduct->product->translation->name }}',
                            'price' : '{{ $subproduct->subproduct->product->personalPrice->price }}',
                            'brand' : 'Anne Popova',
                            'category': '{{ $subproduct->subproduct->product->category->translation->name }}',
                            'quantity': '{{ $subproduct->qty }}',
                            'coupon': '{{ @$order->details->promocode }}'
                        },
                        @endforeach
                    ]
                },
            },
        });
    }
    </script>
    @endif
@stop
