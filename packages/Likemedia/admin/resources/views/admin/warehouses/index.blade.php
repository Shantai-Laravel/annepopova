@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="blog">Warehouses </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title">
        Warehouses
    </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('warehouses.create'),
    ]
    ])
</div>
@include('admin::admin.alerts')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-block">
                @if(!$warehouses->isEmpty())
                <table class="table table-hover table-striped text-center" id="tablelistsorter">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Default</th>
                            <th class="text-center">Active</th>
                            <th class="text-center">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($warehouses as $key => $warehouse)
                        <tr id="{{ $warehouse->id }}">
                            <td>
                                {{ $key + 1 }}
                            </td>
                            <td>
                                {{ $warehouse->name }}
                            </td>
                            <td>
                                @if ($warehouse->default == 1)
                                    <a href="{{ url('/back/warehouses/default/'. $warehouse->id) }}"><i class="fa fa-plus"></i> </a>
                                @else
                                    <a href="{{ url('/back/warehouses/default/'. $warehouse->id) }}"><i class="fa fa-minus"></i> </a>
                                @endif
                            </td>
                            <td>
                                @if ($warehouse->active == 1)
                                    <a href="{{ url('/back/warehouses/active/'. $warehouse->id) }}"><i class="fa fa-plus"></i> </a>
                                @else
                                    <a href="{{ url('/back/warehouses/active/'. $warehouse->id) }}"><i class="fa fa-minus"></i> </a>
                                @endif
                            </td>
                            <td class="destroy-element">
                                <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="post">
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
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-block">
                <h6>Create a Warehouse</h6>
                <form action="{{ route('warehouses.store') }}" enctype="multipart/form-data" method="post">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>
                            <input class="checkbox" type="checkbox" name="default">
                            <span> Default</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                            <input class="checkbox" type="checkbox" name="active" checked="true">
                            <span> Active</span>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
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
<style media="screen">
    table>tbody>tr>td{
    vertical-align: middle !important;
    }
    img{
    max-width: 150px;
    }
    .img-wrapper{
    display: block;
    background-color: #FFF;
    box-shadow: 0px 0px 1px 0px rgba(0,0,0,0.5);
    border-radius: 3px;
    padding: 5px;
    cursor: pointer;
    }
</style>
@stop
