@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Front Users</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Front Users </h3>
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

  @if(!$users->isEmpty())
  <div class="card">
      <div class="card-block">
          <table class="table table-hover table-striped" id="tablelistsorter">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Edit</th>
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
                          {{ $user->email ?? trans('variables.another_name') }}
                      </td>
                      <td>
                          <a href="{{ route('users.edit', $user->id) }}">
                          <i class="fa fa-edit"></i>
                          </a>
                      </td>
                      <td class="destroy-element">
                          @if (count($users) > 1)
                          <form action="{{ route('users.destroy', $user->id) }}" method="post">
                              {{ csrf_field() }} {{ method_field('DELETE') }}
                              <button type="submit" class="btn-link">
                                  <a href=""><i class="fa fa-trash"></i></a>
                              </button>
                          </form>
                          @else
                              ---
                          @endif

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
