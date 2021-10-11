@if (Session::has('success'))
    <div class="alert alert-success faded">
        <p>{{ Session::get('success') }}</p>
    </div>
@endif

@if (Session::has('error-message'))
    <div class="alert alert-danger faded">
        <p>{{ Session::get('error-message') }}</p>
    </div>
@endif

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{!! $error !!}</li>
            @endforeach
        </ul>
    </div>
@endif
