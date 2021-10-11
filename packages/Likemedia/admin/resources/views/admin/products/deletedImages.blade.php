@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Produse</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Deleted images

    </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
        trans('variables.add_element') => route('products.create', ['category' => Request::segment(4)]),
        "Auto Upload" => route('quick-upload.index', ['category' => Request::segment(4)]),
        "All products" => url('back/products'),
    ]
    ])
</div>


@if(count($deletedImages))
<div class="card">
    <div class="card-block">
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>date (+2 hours)</th>
                    <th>product name</th>
                    <th>src</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deletedImages as $key => $image)
                <tr id="{{ $image->id }}">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $image->created_at}}</td>
                    <td>{{ $image->product_name}}</td>
                    <td>{{ $image->src}}</td>
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
