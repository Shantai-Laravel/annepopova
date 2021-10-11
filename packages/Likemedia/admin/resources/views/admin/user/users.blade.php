@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

    @include('admin::admin.speedbar')


    @if(!$users->isEmpty())
        <h4>
            <small><i class="fa fa-thumb-tack"></i></small>
        </h4>
        <table class="el-table">
            <thead>
            <tr>
                <th>Email</th>
                <th>Login</th>
                <th>Name</th>
                <th>Created at</th>
                <th>{{trans('variables.edit_table')}}</th>
                <th>{{trans('variables.delete_table')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ strtolower($user->email) }}</td>
                    <td>{{ $user->login }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->created_at->format('d.m.Y h:i') }}</td>
                    <td>
                        <a href=""><i class="fa fa-edit"></i></a>
                    </td>
                    <td class="destroy-element">
                        <a href=""><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    @else
        <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
    @endif
@stop

@section('footer')
    <footer>
        @include('admin::admin.footer')
    </footer>
@stop
