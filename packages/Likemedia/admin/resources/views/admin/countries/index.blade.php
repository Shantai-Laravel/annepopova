@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="blog">Countries </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title">Countries</h3>
    @include('admin::admin.list-elements', [
    'actions' => [
            'Add new' => route('countries.create'),
        ]
    ])
</div>

@include('admin::admin.alerts')

@if(!$countries->isEmpty())
<div class="card">
    <div class="card-block">
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Active</th>
                    <th>Phone Code</th>
                    <th>Flag</th>
                    <th>Lang</th>
                    <th>Currency</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($countries as $key => $country)
                <tr id="{{ $country->id }}">
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>
                        {{ $country->name }}
                        @if ($country->main == 1)
                            <span class="label label-success pull-right">Default</span>
                        @endif
                    </td>
                    <td>
                        @if ($country->active == 1)
                            <i class="fa fa-check text-success"></i>
                        @else
                            <i class="fa fa-close text-danger"></i>
                        @endif
                    </td>
                    <td>
                        +{{ $country->phone_code }}
                    </td>
                    <td>
                        <img src="/images/flags/16x16/{{ $country->flag }}" alt="">
                    </td>
                    <td>
                        {{ !is_null($country->lang) ? $country->lang->lang : '---' }}
                    </td>
                    <td>
                        {{ !is_null($country->currency) ? $country->currency->abbr : '---' }}
                    </td>
                    <td>
                        <a href="{{ route('countries.edit', $country->id) }}">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="destroy-element">
                        <form action="{{ route('countries.destroy', $country->id) }}" method="post">
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
                    <td colspan=10></td>
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
