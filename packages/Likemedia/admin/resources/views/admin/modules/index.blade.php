@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Modules</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Modules </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
            'Add new' => route('modules.create'),
        ]
    ])
</div>
@if(!empty($modules))
<div class="card">
<div class="card-block">
<table class="table table-hover" id="tablelistsorter">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Position</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        @foreach($modules as $key => $module)
        <tr id="{{ $module->id }}">
            <th>#{{ $key + 1 }}</th>
            <th>
                {{ $module->name ?? trans('variables.another_name')}}
            </th>
            <td class="dragHandle" nowrap style="cursor: move;">
                <a class="top-pos" href=""><i class="fa fa-arrow-up"></i></a>
                <a class="bottom-pos" href=""><i class="fa fa-arrow-down"></i></a>
            </td>
            <th>
                <a href="{{ route('modules.edit', $module->id) }}">
                <i class="fa fa-edit"></i>
                </a>
            </th>
            <th class="destroy-element">
                <form action="{{ route('modules.destroy', $module->id) }}" method="post">
                    {{ csrf_field() }} {{ method_field('DELETE') }}
                    <button type="submit" class="btn-link">
                    <a>
                    <i class="fa fa-trash"></i>
                    </a>
                    </button>
                </form>
            </th>
            @if (count($module->children) > 0)
    <tbody class="children-table">
        @foreach($module->children as $key => $module)
        <tr id="{{ $module->id }}">
            <td>#{{ $key + 1 }}</td>
            <td>
                {{ $module->name ?? trans('variables.another_name')}}
            </td>
            <td class="dragHandle" nowrap style="cursor: move;">
                <a class="top-pos" href=""><i class="fa fa-arrow-up"></i></a>
                <a class="bottom-pos" href=""><i class="fa fa-arrow-down"></i></a>
            </td>
            <td>
                <a href="{{ route('modules.edit', $module->id) }}">
                <i class="fa fa-edit"></i>
                </a>
            </td>
            <td class="destroy-element">
                <form action="{{ route('modules.destroy', $module->id) }}" method="post">
                    {{ csrf_field() }} {{ method_field('DELETE') }}
                    <button type="submit" class="btn-link">
                    <a>
                    <i class="fa fa-trash"></i>
                    </a>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
    @endif
    </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan=7></td>
        </tr>
    </tfoot>
</table>
<div>
<div>
@else
<div class="empty-response">Empty list</div>
@endif
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
