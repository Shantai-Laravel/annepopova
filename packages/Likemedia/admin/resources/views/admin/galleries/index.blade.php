@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="gallery">Galeries </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Galeries </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('galleries.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

@if(!$galleries->isEmpty())
<div class="card">
    <div class="card-block">
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Alias</th>
                    <th>Image</th>
                    <th class="text-center">Images/Videos qty</th>
                    <th>{{trans('variables.edit_table')}}</th>
                    <th>{{trans('variables.delete_table')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($galleries as $key => $gallery)
                <tr id="{{ $gallery->id }}">
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>
                        <span class="label label-success">{{ $gallery->alias }}</span>
                    </td>
                    <td>
                        @if (!is_null($gallery->MainImage))
                            <img src="{{ $gallery->MainImage->src }}" height="50">
                        @else
                            <img src="{{ asset('images/noimage.jpg') }}" height="40">
                        @endif
                    </td>
                    <td class="text-center">
                        {{ count($gallery->Images) }}
                    </td>

                    <td>
                        <a href="{{ route('galleries.edit', $gallery->id) }}">
                        <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="destroy-element">
                        <form action="{{ route('galleries.destroy', $gallery->id) }}" method="post">
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
