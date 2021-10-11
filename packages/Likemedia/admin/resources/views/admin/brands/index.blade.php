@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="brand">Brands </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Brands </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('brands.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

@if(!$brands->isEmpty())
<div class="card">
    <div class="card-block">
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{trans('variables.title_table')}}</th>
                    <th>{{trans('variables.position_table')}}</th>
                    <th>{{trans('variables.active_table')}}</th>
                    <th>{{trans('variables.edit_table')}}</th>
                    <th>{{trans('variables.delete_table')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($brands as $key => $brand)
                <tr id="{{ $brand->id }}">
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>
                        {{ $brand->translation()->first()->name ?? trans('variables.another_name') }}
                    </td>
                    <td class="dragHandle" nowrap style="cursor: move;">
                        <a class="top-pos" href=""><i class="fa fa-arrow-up"></i></a>
                        <a class="bottom-pos" href=""><i class="fa fa-arrow-down"></i></a>
                    </td>
                    <td>
                        <form action="{{ route('brands.change.status', $brand->id) }}" method="post">
                            {{ csrf_field() }} {{ method_field('PATCH') }}
                            <button type="submit" class="btn-link">
                            <a href="" class="change-active {{ $brand->active == 1 ? '' : 'negative' }}">
                                <i class="fa fa-{{ $brand->active == 1 ? 'plus' : 'minus' }}"></i>
                            </a>
                            </button>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('brands.edit', $brand->id) }}">
                        <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="destroy-element">
                        <form action="{{ route('brands.destroy', $brand->id) }}" method="post">
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
