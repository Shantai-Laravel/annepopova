@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="collection">Orders </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title">
        Orders
        @if (Request::segment(3) != 'guests')
            <b>Auth Users</b>
        @else
            <b>Guest Users</b>
        @endif
    </h3>
    @include('admin::admin.list-elements', [
        'actions' => [
            'Create Order' => url('back/crm-orders'),
        ]
    ])
</div>

<div class="card">
    <div class="card-block">
        {{-- <div class="row order-tabs-area">
            <div class="col-md-2 col-md-offset-1">
                <a href="{{ Request::url() }}" class="btn btn-primary btn-block {{ !Request::get('status') ? 'active-btn' : ''}}">Pending Order</a>
            </div>
            <div class="col-md-2">
                <a href="{{ Request::url().'?status=inway' }}" class="btn btn-primary btn-block {{ Request::get('status') == 'inway' ? 'active-btn' : ''}}">Inway Order</a>
            </div>
            <div class="col-md-2">
                <a href="{{ Request::url().'?status=completed' }}" class="btn btn-primary btn-block {{ Request::get('status') == 'completed' ? 'active-btn' : ''}}">Completed Order</a>
            </div>
            <div class="col-md-2">
                <a href="{{ Request::url().'?status=cancelled' }}" class="btn btn-primary btn-block {{ Request::get('status') == 'cancelled' ? 'active-btn' : ''}}">Canceled Order</a>
            </div>
            <div class="col-md-2">
                <a href="{{ Request::url().'?status=preorders' }}" class="btn btn-primary btn-block {{ Request::get('status') == 'preorders' ? 'active-btn' : ''}}">Preorders</a>
            </div>
        </div> <hr> --}}

        <div class="row">
            <table class="table table-hover table-striped">
                <thead>
                    <th>#</th>
                    <th>User</th>
                    <th>Amout (EUR)</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th>Details</th>
                    <th>Delete</th>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $order)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ !is_null($order->user) ? $order->user->name : $order->details->contact_name }} <small>({{ $order->details->email }})</small>   </td>
                            <td>{{ $order->amount }} EUR </td>
                            <td><small class="label label-primary">{{ $order->main_status }}</small></td>
                            <td><small class="label label-success">{{ $order->payment_id }}</small></td>
                            <td>{{ date('d-m-Y h:i:s', strtotime($order->change_status_at) ) }}</td>
                            <td><a href="{{ url('/back/crm-orders-detail/'.$order->id) }}"><i class="fa fa-edit"></i></a></td>
                            <td><a href="{{ url('/back/crm-order-delete/'.$order->id) }}"><i class="fa fa-trash"></i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@stop
