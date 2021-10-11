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
    <h3 class="title">Returns</h3>
    @include('admin::admin.list-elements', [
    'actions' => [
            'Create Return' => url('/back/returns-select-order-to-return'),
        ]
    ])
</div>

@include('admin::admin.alerts')

@if(!$returns->isEmpty())
<div class="card">
    <div class="card-block row">
        <div class="col-md-12">
            <table class="table table-hover table-striped" id="tablelistsorter">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order Hash</th>
                        <th>Return reasons</th>
                        <th>Refund Method</th>
                        <th>User Email</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returns as $key => $return)
                    <tr id="{{ $return->id }}">
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>
                            {{ $return->order->order_hash }}
                        </td>
                        <td>
                            {{ $return->reason }}
                        </td>
                        <td>
                            {{ $return->payment }}
                        </td>
                        <td>
                            @if ($return->user)
                                <span class="label label-primary">auth user</span>
                                {{ $return->user->email }}
                            @else
                                <span class="label label-primary">guest user</span>
                                {{ $return->guest->email }}
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ url('back/returns/'. $return->id .'/show') }}">
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
    </div>
</div>
@else
<div class="empty-response">{{trans('variables.list_is_empty')}}</div>
@endif
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
