@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="collection">Collections </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Collections </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
        trans('variables.add_element') => route('collections.create'),
        'Adauga un set' => route('sets.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

@if(!$collections->isEmpty())
<div class="card">
    <div class="card-block row">
        <div class="col-md-9">
            <table class="table table-hover table-striped" id="tablelistsorter">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{trans('variables.title_table')}}</th>
                        <th>Active</th>
                        <th>{{trans('variables.position_table')}}</th>
                        <th class="text-center">Vezi seturile</th>
                        <th class="text-center">{{trans('variables.edit_table')}}</th>
                        <th>{{trans('variables.delete_table')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($collections as $key => $collection)
                    <tr id="{{ $collection->id }}">
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>
                            {{ $collection->translation()->first()->name ?? trans('variables.another_name') }}
                        </td>
                        <td>
                            @if ($collection->active == 1)
                                <small class="text-success">active</small>
                            @else
                                <small class="text-danger">pasive</small>
                            @endif
                        </td>
                        <td class="dragHandle" nowrap style="cursor: move;">
                            <a class="top-pos" href=""><i class="fa fa-arrow-up"></i></a>
                            <a class="bottom-pos" href=""><i class="fa fa-arrow-down"></i></a>
                        </td>
                        <td class="text-center">
                            <a href="{{ url('back/sets/collection/'.$collection->id) }}">
                                <i class="fa fa-folder-open-o"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('collections.edit', $collection->id) }}">
                                <i class="fa fa-edit"></i>
                            </a>
                        </td>
                        <td class="destroy-element">
                            <form action="{{ route('collections.destroy', $collection->id) }}" method="post">
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
        <div class="col-md-3">
            <h6 class="text-center">Collection/Sets Tree</h6><hr>
            @foreach($collections as $key => $collection)
                <ul class="list-tree">
                    <li>
                        <a href="{{ route('collections.edit', $collection->id) }}">- {{ $collection->translation()->first()->name }}</a>
                        @if (count($collection->sets()->get()) > 0)
                            <ol>
                                @foreach ($collection->sets()->get() as $key => $set)
                                    <li><a href="{{ route('sets.edit', $set->id) }}">--  {{ $set->translation()->first()->name }}</a></li>
                                @endforeach
                            </ol>
                        @endif
                    </li>
                </ul>
            @endforeach
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
