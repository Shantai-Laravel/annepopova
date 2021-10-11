@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('languages.index') }}">Languages</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Language</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Create Language </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
        'Add new' => route('languages.create'),
        ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">
    <form class="form-reg" role="form" method="POST" action="{{ route('languages.store') }}" id="add-form">
        {{ csrf_field() }}
        <div class="part full-part">
            <ul>
                <li>
                    <label for="name">Abbreviation</label>
                    <input type="text" name="name" id="name" placeholder="en" value="{{ old('name')}}">
                </li>
                <li>
                    <label for="description">Name</label>
                    <input type="text" name="description" id="description" placeholder="English" value="{{ old('description')}}">
                </li>
                <input type="submit" value="Save" class="btn btn-primary">
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
