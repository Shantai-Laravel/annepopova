@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="blog">Delivery Methods</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title">Delivery Methods</h3>
</div>
@include('admin::admin.alerts')
<div class="card">
    <div class="card-block">
        <div class="row">
            <div class="col-md-8">
                @if(!$deliveries->isEmpty())
                <table class="table table-hover table-striped" id="tablelistsorter">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th class="text-center">Countries</th>
                            <th class="text-center">Price</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deliveries as $key => $delivery)
                        <tr id="{{ $delivery->id }}">
                            <td>
                                {{ $key + 1 }}
                            </td>
                            <td>
                                {{ $delivery->translation->name }}
                            </td>
                            <td class="text-center">
                                <span class="badge badge-primary">{{ $delivery->countries()->count() }}</span>
                            </td>
                            <td class="text-center">
                                {{ $delivery->price }} {{ $mainCurrency->abbr }}
                            </td>
                            <td>
                                <a href="{{ route('delivery.edit', $delivery->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                            <td class="destroy-element">
                                <form action="{{ route('delivery.destroy', $delivery->id) }}" method="post">
                                    {{ csrf_field() }} {{ method_field('DELETE') }}
                                    <button type="submit" class="btn-link">
                                    <a href=""><i class="fa fa-trash"></i></a>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan=7></td>
                        </tr>
                    </tfoot>
                </table>
                @else
                <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
                @endif
            </div>
            <div class="col-md-4">
                <h5>Create new</h5><hr>
                <form method="POST" action="{{ route('delivery.store') }}">
                    {{ csrf_field() }}
                    <label for="">Name</label>
                    @foreach ($langs as $key => $lang)
                    <div class="input-group">
                        <input type="text" name="name_{{ $lang->lang }}" class="form-control" required>
                        <span class="input-group-addon">{{ $lang->lang }}</span>
                    </div>
                    @endforeach
                    <div class="input-group">
                        <label>Price <small>[ currency - {{ $mainCurrency->abbr }} ]</small> </label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>
                    <div class="input-group">
                        <label>Time <small>[ days ]</small> </label>
                        <input type="text" step="0.01" name="delivery_time" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
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
