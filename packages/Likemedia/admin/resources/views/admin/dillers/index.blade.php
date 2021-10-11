@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dillers</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Dillers </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
            'Add new Diller' => route('dillers.create'),
        ]
    ])
</div>
<div class="list-content">
    <div class="tab-area">
        @include('admin::admin.alerts')
    </div>
    @if(!$users->isEmpty())
    <div class="card">
        <div class="card-block">
            <table class="table table-hover table-striped" id="tablelistsorter">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th class="text-center">Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                    <tr id="{{ $user->id }}">
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>
                            {{ $user->name ?? trans('variables.another_name') }}
                        </td>
                        <td>
                            {{ $user->company }}
                        </td>
                        <td>
                            {{ $user->email ?? trans('variables.another_name') }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('dillers.edit', $user->id) }}">
                            <i class="fa fa-edit"></i>
                            </a>
                        </td>
                        <td class="destroy-element">
                            <form action="{{ route('dillers.destroy', $user->id) }}" method="post">
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
</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
