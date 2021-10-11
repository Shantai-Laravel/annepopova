@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Admin Users</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Admin User</li>
    </ol>
</nav>

<div class="title-block">
    <h3 class="title"> Edit User </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('users.create'),
    ]
    ])
</div>

    <div class="list-content">
        <div class="tab-area">
            @include('admin::admin.alerts')
        </div>

        <form class="form-reg" role="form" method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
          {{ csrf_field() }}
          {{ method_field('PATCH') }}
          <div class="part full-part">
            <h3>Personal User Information</h3>
            <ul>
                <li>
                    <label for="username">Username</label>
                    <input type="text" name="username" class="username" id="username" value="{{$user->login}}">
                </li>
                <li>
                    <label for="email">Email</label>
                    <input type="email" name="email" class="name" id="email" value="{{$user->email}}">
                </li>
                <li>
                    <label for="password">Password</label>
                    <input type="text" name="password" class="name" id="password">
                </li>
                <li>
                    <label for="password-again">Password Again</label>
                    <input type="text" name="password-again" class="name" id="password-again">
                </li>
                <li>
                    <input type="submit" value="{{trans('variables.save_it')}}">
                </li>
            </ul>
          </div>
        </form>


    </div>


@stop

@section('footer')
    <footer>
        @include('admin::admin.footer')
    </footer>
@stop
