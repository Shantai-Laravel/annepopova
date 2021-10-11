@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="set">Returns </li>
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

        @if (Session::has('error'))
            <p class="alert alert-danger text-center">
                {{ Session::get('error') }}
            </p>
        @endif

        <form action="{{ url('/back/returns/store') }}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <div class="col-md-12">
                @if ($order->items()->count() > 0)
                    @foreach ($order->items as $key => $item)
                        <div class="col-md-12">
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="checkbox" name="item_id[]" value="{{ $item->id }}" {{ $item->return_id > 0 ? 'disabled' : ''}}>
                                </div>
                            </div>
                            <div class="col-md-3">
                                {{ $item->subproduct->product->translation->name }}
                            </div>
                            <div class="col-md-2">
                                <small><b>Price</b></small> - {{ $item->subproduct->product->mainPrice->price }} {{ $mainCurrency->abbr }}
                            </div>
                            <div class="col-md-2">
                                <small><b>Qty</b></small> - {{ $item->qty }}
                            </div>
                            <div class="col-md-2">
                                <small><b>Size</b></small> - {{ $item->subproduct->parameterValue->translation->name  }}
                            </div>
                            <div class="col-md-2">
                                @if ($item->return_id)
                                    <span class="label label-danger">returned</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="col-md-12">
                <hr>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Return reasons</label>
                    <select class="form-control" name="reason">
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
                <div class="form-group">
                    <label for="">Additional details</label>
                    <textarea name="additional" rows="8" cols="80" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="">Upload pictures of items eligible to return</label>
                    <input type="file" name="img" value="">
                </div>

                <div>
                    <label for="">Select Refund Method</label>
                    <div class="tab-area">
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <a href="#bank_account" class="nav-link bank_account open active" data-target="#bank_account">Bank Account</a>
                            </li>
                            <li class="nav-item">
                                <a href="#paypal" class="nav-link paypal" data-target="#paypal">Paypal</a>
                            </li>
                        </ul>
                    </div>

                  <div class="tab-content active-content" id="bank_account">
                    <div class="form-group">
                        <input type="radio" name="return_method" value="bank_account" id="bank_account" checked>
                        <label for="bank_account">Bank Account</label>
                   </div>
                    <div class="form-group">
                        <label for="">IBAN</label>
                        <input type="text" name="iban" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Bank</label>
                        <input type="text" name="bank" class="form-control">
                    </div>
                  </div>

                  <div class="tab-content" id="paypal">
                     <div class="form-group">
                         <input type="radio" name="return_method" value="paypal" id="paypal">
                         <label for="paypal">Paypal</label>
                    </div>
                    <div class="form-group">
                        <label for="">Email address linked to Your Paypal Account</label>
                        <input type="email" name="paypal_email" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Return">
                </div>
            </div>
        </form>

    </div>
</div>

@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>

<script>

    $('.bank_account').on('click', function(){
        $('input:radio[name=return_method][value=bank_account]').prop('checked', true);
        $('input:radio[name=return_method][value=paypal]').prop('checked', false);

    });

    $('.paypal').on('click', function(){
        console.log('v');
        $('input:radio[name=return_method][value=paypal]').prop('checked', true);
        $('input:radio[name=return_method][value=bank_account]').prop('checked', false);

    });

</script>
@stop
