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
                    <h5>{{ trans('vars.Cabinet.orderHistory') }}</h5>
                </div>
                <div class="col-lg-4 col-md-5">
                    <div class="cabinetNavBloc">
                        <div class="cabNavTitle">
                            {{ trans('vars.General.hello') }}, {{ $userdata->name }}
                        </div>
                        
                        @include('front.account.accountMenu')
                    </div>
                </div>
                <div class="col-lg-8 col-md-7 historyOne contact">
                    <div class="historyOneTitle">
                        {{ trans('vars.Cabinet.order') }}: <small>{{ $order->order_hash }}</small> <span class="delivered">{{ $order->main_status }}</span>
                    </div>
                    <form class="" action="{{ url('/'.$lang->lang.'/account/returns/store') }}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        @if ($order->orderSubproducts()->count() > 0)
                            @foreach ($order->items as $key => $subproduct)
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
                                        {{ $subproduct->qty }} {{ trans('vars.Cabinet.pieces') }}.
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
                        <div class="row productsCommands align-items-center justify-content-between">
                            <div class="col-md-12">
                                <label for="">Return reasons<b>*</b> </label>
                                <select name="reason">
                                    <option value="I ordered incorect product(s)">I ordered incorect product(s)</option>
                                    <option value="I ordered incorect size">I ordered incorect size</option>
                                    <option value="I received  incorect product(s)">I received  incorect product(s)</option>
                                    <option value="I received incorect size">I received incorect size</option>
                                    <option value="Product No Longer Needed">Product No Longer Needed</option>
                                    <option value="The product(s) is detereorated/ damaged">The product(s) is detereorated/ damaged</option>
                                    <option value="Product does not match description on website">Product does not match description on website</option>
                                    <option value="The shipping was longer than mentioned on website">The shipping was longer than mentioned on website</option>
                                    <option value="Other reason">Other reason</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="">Additional Details</label>
                                <textarea name="message" rows="8" cols="80" required></textarea>
                            </div>
                            <div class="col-12">
                                <label for="">Upload pictures of items eligible to return</label>
                                <input type="file" name="" value="">
                            </div>
                            <div class="col-12">
                                <label for="">Select Refund Method</label>
                                <div class="row">
                                    <div class="col-6">
                                        <div>
                                            <input type="radio" name="return_method" value="bank_account" id="bank_account" checked>
                                            <label for="bank_account">Bank Account</label>
                                        </div>
                                        <div>
                                            <label for="">IBAN</label>
                                            <input type="text" name="iban" class="form-control">
                                        </div>
                                        <div>
                                            <label for="">Bank</label>
                                            <input type="text" name="bank" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div>
                                            <input type="radio" name="return_method" value="paypal" id="paypal">
                                            <label for="paypal">Paypal</label>
                                        </div>
                                        <div>
                                            <label for="">Email address linked to Your Paypal Account</label>
                                            <input type="email" name="paypal_email" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="submit" value="Return">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@include('front.partials.footer')
@stop
