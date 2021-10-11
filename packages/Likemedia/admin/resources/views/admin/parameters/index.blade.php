@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Parameters</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Parameters </h3>
    @include('admin::admin.list-elements', [
        'actions' => [
            trans('variables.add_element') => route('parameters.create'),
            'Parameter Groups' => url('/back/parameter-groups'),
        ]
    ])
</div>
@include('admin::admin.alerts')
@if(!$parameters->isEmpty())
<div class="card">
    <div class="card-block">
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Key</th>
                    <th>Type</th>
                    <th>Filter</th>
                    <th>Multilingual</th>
                    <th>Position</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($parameters as $key => $parameter)
                <tr id="{{ $parameter->id }}">
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>
                        {{ $parameter->translation()->first()->name }}
                    </td>
                    <td>
                        {{ $parameter->key }}
                    </td>
                    <td>
                        <small>{{ $parameter->type }}</small>
                    </td>
                    <td>
                        @if ($parameter->in_filter)
                            <i class="fa fa-check"></i>
                        @else
                            ---
                        @endif
                    </td>
                    <td>
                        @if ($parameter->multilingual)
                            <i class="fa fa-check"></i>
                        @else
                            ---
                        @endif
                    </td>
                    <td class="dragHandle" nowrap style="cursor: move;">
                        <a class="top-pos" href=""><i class="fa fa-arrow-up"></i></a>
                        <a class="bottom-pos" href=""><i class="fa fa-arrow-down"></i></a>
                    </td>
                    <td>
                        <a href="{{ route('parameters.edit', $parameter->id) }}">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="destroy-element">
                        <form action="{{ route('parameters.destroy', $parameter->id) }}" method="post">
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
                    <td colspan=9></td>
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
