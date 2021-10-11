@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/back/collections') }}">Collections</a></li>
        <li class="breadcrumb-item active" aria-current="set">Seturi </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title">Seturi <small>din colectia "{{ $collection->translation()->first()->name }}"</small> </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('sets.create').'?collection='.Request::segment(4),
    ]
    ])
</div>

@include('admin::admin.alerts')

@if(!$sets->isEmpty())
<div class="card">
    <div class="card-block row">
        <div class="col-md-12">
            <table class="table table-hover table-striped" id="tablelistsorter">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{trans('variables.title_table')}}</th>
                        <th>Pret</th>
                        <th class="text-center">Cantitatea produse</th>
                        <th>Active</th>
                        <th>{{trans('variables.position_table')}}</th>
                        <th class="text-center">Vezi produsele</th>
                        <th class="text-center">{{trans('variables.edit_table')}}</th>
                        <th>{{trans('variables.delete_table')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sets as $key => $set)
                    <tr id="{{ $set->id }}">
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>
                            {{ $set->translation()->first()->name ?? trans('variables.another_name') }}
                        </td>
                        <td>
                            @if ($set->price)
                                {{ $set->price }} Euro
                            @else
                                ---
                            @endif
                        </td>
                        <td class="text-center">
                            {{ count($set->products()->get()) }}
                        </td>
                        <td>
                            @if ($set->active == 1)
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
                            <a href="{{ url('back/products/sets/'.$set->id) }}">
                                <i class="fa fa-folder-o"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ url('back/sets/'.$set->id.'/edit?collection='.$set->collection_id) }}">
                                <i class="fa fa-edit"></i>
                            </a>
                        </td>
                        <td class="destroy-element">
                            <form action="{{ route('sets.destroy', $set->id) }}" method="post">
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
