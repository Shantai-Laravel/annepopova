@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item" aria-current="collection"><a href="{{ url('/back/crm-orders-list') }}">Orders</a></li>
        <li class="breadcrumb-item active" aria-current="collection">Order Details</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Orders </h3>
    @include('admin::admin.list-elements', [
        'actions' => [
            'Create Order' => url('back/crm-orders'),
        ]
    ])
</div>

<div class="card">
    <div class="card-block">
        @if ($order->main_status == 'cancelled')
            <span class="stamp is-nope text-center">Cancelled
                <p>on {{ $order->change_status_at }}</p>
            </span>
        @endif

        <div class="row">
            <form  action="{{ url('back/crm-orders-change-order-status') }}" method="post">
                {{ csrf_field() }}
            <div class="col-md-7">
                <hr><h5>Order info:</h5>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Order Hash</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" disabled value="{{ $order->order_hash }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Contact name</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" disabled value="{{ $order->details->contact_name }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Email</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" disabled value="{{ $order->details->email }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Phone</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" disabled value="+{{ $order->details->code }} {{ $order->details->phone }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Promocode</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" disabled value="{{ $order->details->promocode ? $order->details->promocode : '---' }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Currency</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" disabled value="{{ $order->details->currency }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Payment</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" disabled value="{{ $order->payment_id }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Country</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" disabled value="{{ $order->details->country }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Region</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" value="{{ $order->details->region }}" class="form-control" name="region">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">City</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" value="{{ $order->details->city }}" class="form-control" name="city">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Address</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" value="{{ $order->details->address }}" class="form-control" name="address">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Apartment</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" value="{{ $order->details->apartment }}" class="form-control" name="apartment">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Zip Code</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" value="{{ $order->details->zip }}" class="form-control" name="zip">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Shipping</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" disabled value="{{ $order->details->delivery }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Shipping Price</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" disabled value="{{ $order->details->delivery_price }} {{ $mainCurrency->abbr }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Tax</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" disabled value="{{ $order->details->tax_price }} {{ $mainCurrency->abbr }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Amount
                            {{-- @if ($order->details->promocode)
                                <small>- promocode discount</small>
                            @endif --}}
                         </label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" disabled value="{{ $order->amount }} {{ $mainCurrency->abbr }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Order Label</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" disabled value="{{ $order->label }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Changed Label on:</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" disabled value="{{ $order->change_status_at }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="">Comment</label>
                    </div>
                    <div class="col-md-9">
                        <textarea type="text" name="comment" class="form-control">{{ $order->details->comment }}</textarea>
                    </div>
                </div>
            </div>

            <input type="hidden" name="order_id" value="{{ $order->id }}">

            @if ($order->main_status !== 'cancelled')
            <div class="col-md-5">
                <hr><h5>Statuses: </h5>
                <div class="form-group">
                    <label for="">Main Status</label>
                    <select class="form-control" name="main_status">
                        <option value="pending" {{ $order->main_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        {{-- <option value="processing" {{ $order->main_status == 'processing' ? 'selected' : '' }}>Procesing</option> --}}
                        <option value="inway" {{ $order->main_status == 'inway' ? 'selected' : '' }}>Inway</option>
                        <option value="completed" {{ $order->main_status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->main_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Secondary Status</label>
                    <select class="form-control" name="secondary_status">
                        <option value="0">---</option>
                        <option value="{{str_slug('User not responding')}}" {{ $order->secondary_status == str_slug('User not responding') ? 'selected' : '' }}>User not responding</option>
                        <option value="{{str_slug('Confirmed by User')}}" {{ $order->secondary_status == str_slug('Confirmed by User') ? 'selected' : '' }}>Confirmed by User</option>
                        <option value="{{str_slug('Cancelled by User')}}" {{ $order->secondary_status == str_slug('Cancelled by User') ? 'selected' : '' }}>Cancelled by User</option>
                        <option value="{{str_slug('In process of confirmation')}}" {{ $order->secondary_status == str_slug('In process of confirmation') ? 'selected' : '' }}>In process of confirmation</option>
                    </select>
                </div>
            <input type="submit" class="btn btn-primary" value="Save">
            </div>
            @else
                <div class="col-md-5">
                    <hr> <br>
                    <div class="alert alert-danger text-center">
                        <h6>Order Status:</h6>
                        <h5>Cancelled</h5>
                    </div>
                </div>
            @endif
        </form>


            <div class="col-md-12">
                <hr><h5>Order Products: </h5>
                <div class="row">
                    <table class="table table-hover table-striped">
                        <thead>
                            <th class="text-center">type</th>
                            <th>image</th>
                            <th>name</th>
                            <th>details</th>
                            <th>qty</th>
                            <th>discount</th>
                            <th>old price</th>
                            <th>price</th>
                        </thead>
                        <tbody>
                            @if ($orderProducts->count())
                                @foreach ($orderProducts as $key => $product)
                                    @if (!is_null($product))

                                    <tr>
                                        <td class="text-center">
                                            <span class="label label-primary">product</span>
                                        </td>
                                        <td>
                                            @if ($product->product->mainImage)
                                                <img src="/images/products/sm/{{ $product->product->mainImage->src }}" height="30px">
                                            @else
                                                <img src="/admin/img/noimage.jpg" height="30px">
                                            @endif
                                        </td>
                                        <td>
                                            {{ $product->product->translation->name }}
                                            <p>{{ $product->code }}</p>
                                        </td>
                                        <td>---</td>
                                        <td>{{ $product->qty }}</td>
                                        <td>{{ $product->discount ? $product->discount : '---' }}</td>
                                        <td>{{ $product->old_price }}</td>
                                        <td>{{ $product->price }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>Deleted product</td>
                                    </tr>
                                @endif

                                @endforeach
                            @endif

                            @if ($orderSubproducts->count())
                                @foreach ($orderSubproducts as $key => $subproduct)
                                    @if (!is_null($subproduct->subproduct))

                                    <tr>
                                        <td class="text-center">
                                            <span class="label label-success">subproduct</span>
                                        </td>
                                        <td>
                                            @if (!is_null($subproduct->subproduct->product->mainImage))
                                                <img src="/images/products/sm/{{ $subproduct->subproduct->product->mainImage->src }}" height="30px">
                                            @else
                                                <img src="/admin/img/noimage.jpg" height="30px">
                                            @endif
                                        </td>
                                        <td>
                                            {{ $subproduct->subproduct->product->translation->name }}
                                            <p><small>{{ $subproduct->code }}</small> </p>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ $subproduct->subproduct->parameterValue->translation->name }}
                                            </span>
                                        </td>
                                        <td>{{ $subproduct->qty }}</td>
                                        <td>{{ $subproduct->discount ? $subproduct->discount : '---'  }}</td>
                                        <td>{{ $subproduct->old_price }}</td>
                                        <td>{{ $subproduct->price }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>Deleted Subproduct</td>
                                    </tr>
                                @endif

                                @endforeach
                            @endif


                            @if ($orderSets->count())
                                @foreach ($orderSets as $key => $set)
                                    @if (!is_null($set->set))
                                    <tr>
                                        <td class="text-center">
                                            <span class="label label-warning">set</span>
                                        </td>
                                        <td>
                                            @if ($set->set->mainPhot)
                                                <img src="/images/products/sm/{{ $set->set->mainPhoto->src }}" height="30px">
                                            @else
                                                <img src="/admin/img/noimage.jpg" height="30px">
                                            @endif
                                        </td>
                                        <td>
                                            {{ $set->set->translation->name }}
                                            <p><small>{{ $set->code }}</small></p>
                                        </td>
                                        <td>
                                            <a href="#" data-toggle="modal" data-target="#setProds{{ $set->id }}">
                                                <small>view products</small>
                                            </a>
                                        </td>
                                        <td>{{ $set->qty }}</td>
                                        <td>{{ $set->discount ? $set->discount : '---' }}</td>
                                        <td>{{ $set->old_price }}</td>
                                        <td>{{ $set->price }}</td>
                                    </tr>

                                    <!-- Modal -->
                                    <div class="modal fade" id="setProds{{ $set->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h5 class="modal-title" id="exampleModalLabel">{{ $set->set->translation->name }}</h5>

                                          </div>
                                          <div class="modal-body">
                                              @foreach ($set->children as $key => $child)
                                                  <hr>
                                                  @if ($child->subproduct)
                                                      <div class="row">
                                                          <div class="col-md-1">
                                                              {{ $key + 1 }}
                                                          </div>
                                                          <div class="col-md-3">
                                                              {{ $child->subproduct->product->translation->name }}
                                                          </div>
                                                          <div class="col-md-3">
                                                              {{ $child->code }}
                                                          </div>
                                                          <div class="col-md-3">
                                                              <span class="badge badge-primary">
                                                                  {{ $child->subproduct->parameterValue->translation->name }}
                                                              </span>
                                                          </div>
                                                      </div>
                                                  @else
                                                      <div class="row">
                                                          <div class="col-md-1">
                                                              {{ $key + 1 }}
                                                          </div>
                                                          <div class="col-md-3">
                                                              {{ $child->product->translation->name }}
                                                          </div>
                                                          <div class="col-md-3">
                                                              {{ $child->code }}
                                                          </div>
                                                      </div>
                                                  @endif
                                              @endforeach
                                          </div>

                                        </div>
                                      </div>
                                    </div>
                                @else
                                    <tr>
                                        <td>Deleted Set</td>
                                    </tr>
                                @endif

                                @endforeach
                            @endif
                        </tbody>
                </div>

            </div>
        </div>

    </div>
</div>

<style media="screen">
    .form-group{
        padding-bottom: 25px;
    }
    .form-group .col-md-3{
        text-align: right;
    }
</style>

@stop
