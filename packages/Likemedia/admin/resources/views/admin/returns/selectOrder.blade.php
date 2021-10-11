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
        @if ($userOrders->count() > 0)
        <div class="col-md-6">
            <h6>Auth Orders</h6><hr>
            <table class="table table-hover table-striped" id="tablelistsorter">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order hash</th>
                        <th>User Email</th>
                        <th>Was bought</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userOrders as $key => $order)
                    <tr id="{{ $order->id }}">
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>
                            {{ $order->order_hash }}
                        </td>
                        <td>
                            {{ $order->user->email }}
                        </td>
                        <td>
                            {{ daysBetween(date('Y-m-d h:i:s'), $order->change_status_at) }} day ago
                        </td>
                        <td>
                            <a href="{{ url('back/returns/'.$order->id.'/order') }}">
                                <i class="fa fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan=9></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        @if ($guestOrders->count() > 0)
        <div class="col-md-6">
            <h6>Guest Orders</h6><hr>
            <table class="table table-hover table-striped" id="tablelistsorter">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order hash</th>
                        <th>Guest Email</th>
                        <th>Was bought</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($guestOrders as $key => $orderGuest)
                    <tr id="{{ $orderGuest->id }}">
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>
                            {{ $orderGuest->order_hash }}
                        </td>
                        <td>
                            {{ $orderGuest->guest->email }}
                        </td>
                        <td>
                            {{ daysBetween(date('Y-m-d h:i:s'), $orderGuest->change_status_at) }} day ago
                        </td>
                        <td>
                            <a href="{{ url('back/returns/'.$orderGuest->id.'/order') }}">
                                <i class="fa fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan=9></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif
    </div>
</div>

@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
