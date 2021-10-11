@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="blog">Autometa Scripts </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title">Autometa Scripts</h3>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
        Add new Script
    </button>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Choice Type:</h4>
                </div>
                <div class="modal-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <a href="{{ url('/back/autometa-scripts/create?type=category') }}" class="btn btn-primary btn-lg">Categories</a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ url('/back/autometa-scripts/create?type=product') }}" class="btn btn-primary btn-lg">Products</a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ url('/back/autometa-scripts/create?type=collection') }}" class="btn btn-primary btn-lg">Collections</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    </div>

@include('admin::admin.alerts')

@if(!$scripts->isEmpty())
<div class="card">
    <div class="card-block">
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($scripts as $key => $script)
                <tr id="{{ $script->id }}">
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>
                        {{ $script->name }}
                    </td>
                    <td>
                        <span class="label label-success">{{ $script->type }}</span>
                    </td>
                    <td>
                        <a href="{{ route('autometa-scripts.edit', $script->id) }}">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="destroy-element">
                        <form action="{{ route('autometa-scripts.destroy', $script->id) }}" method="post">
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
