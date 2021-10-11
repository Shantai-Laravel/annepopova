@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('promocodes.index') }}">Promocodes </a></li>
        {{-- <li class="breadcrumb-item"><a href="{{ route('promocodesType.index') }}">Promocode Types </a></li> --}}
        <li class="breadcrumb-item active" aria-current="promotion">Promocode Types </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Promocode Types </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
        trans('variables.add_element') => route('promocodesType.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

@if(!$promocodeTypes->isEmpty())
<div class="card">
    <div class="card-block">
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Discount</th>
                    <th>Times</th>
                    <th>Treshold</th>
                    <th>Period</th>
                    <th>{{trans('variables.edit_table')}}</th>
                    <th>{{trans('variables.delete_table')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($promocodeTypes as $key => $promotionType)
                <tr id="{{ $promotionType->id }}">
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>
                        {{ $promotionType->name }}
                    </td>
                    <td>
                        {{ $promotionType->discount }} %
                    </td>
                    <td>
                        {{ $promotionType->times }}
                    </td>
                    <td>
                        {{ $promotionType->treshold }}
                    </td>
                    <td>
                        {{ $promotionType->period }}
                    </td>

                    <td>
                        <a href="{{ route('promocodesType.edit', $promotionType->id) }}">
                        <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="destroy-element">
                        <form action="{{ route('promocodesType.destroy', $promotionType->id) }}" method="post">
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
