@if (Session::has('message'))
    <div class="alert alert-info">{!! Session::get('message') !!}</div>
@endif

@if (Session::has('error-message'))
    <div class="error-alert alert-info">{!! Session::get('error-message') !!}</div>
@endif

@if(isset($actions) && count($actions))
    @foreach($actions as $action_name => $action)
        <a href="{{$action}}" class="btn btn-primary btn-sm rounded-s {{$action == url()->current() ? 'active' : ''}}" >{{$action_name}}</a>
    @endforeach
@endif
