@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="blog">Banners </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title">
        Banners
    </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
            trans('variables.add_element') => route('banners.create'),
        ]
    ])
</div>

@include('admin::admin.alerts')

@if(!$banners->isEmpty())
<div class="card">
    <div class="card-block">
        <table class="table table-hover table-striped text-center" id="tablelistsorter">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Image Desktop</th>
                    <th class="text-center">Image Mobile</th>
                    <th class="text-center">Key</th>
                    <th class="text-center">Edit</th>
                    <th class="text-center">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($banners as $key => $banner)
                <tr id="{{ $banner->id }}">
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>
                        <a class="img-wrapper" href="/images/banners/{{ $banner->desktop_src }}" data-lightbox="image-1" data-title="{{ $banner->key }}" data-lightbox="{{ $banner->key }}">
                            @if ($banner->desktop_src)
                                <img src="/images/banners/{{ $banner->desktop_src }}" height="80px">
                            @else
                                <img src="/admin/img/noimage.jpg" height="80px">
                            @endif
                        </a>
                    </td>
                    <td>
                        <a class="img-wrapper" href="/images/banners/{{ $banner->mobile_src }}" data-lightbox="image-1" data-title="{{ $banner->key }}" data-lightbox="{{ $banner->key }}">
                            @if ($banner->mobile_src)
                                <img src="/images/banners/{{ $banner->mobile_src }}" height="80px">
                            @else
                                <img src="/admin/img/noimage.jpg" height="80px">
                            @endif
                        </a>
                    </td>
                    <td>
                        <span class="label label-primary">{{ $banner->key }}</span>
                    </td>

                    <td valign="center">
                        <a href="{{ route('banners.edit', $banner->id) }}">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="destroy-element">
                        <form action="{{ route('banners.destroy', $banner->id) }}" method="post">
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
