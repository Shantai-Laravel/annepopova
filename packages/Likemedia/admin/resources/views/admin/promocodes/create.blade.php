@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('promocodes.index') }}">Promo codes </a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Promo code</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Create Promo code </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('promocodes.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">
    <form class="form-reg" role="form" method="POST" action="{{ route('promocodes.store') }}" id="add-form"
        enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="tab-content active-content">
            <div class="part full-part">

                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="form-group">
                            <label for="type" class="text-center">Promocode Types:</label>
                            <select name="type" class="form-control promocode-type">
                                <option value="0">---</option>
                                @if (count($types) > 0)
                                    @foreach ($types as $key => $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <script src="{{asset('admin::admin/js/datepicker.js')}}"></script>
                <div class="row response">
                    @include('admin::admin.promocodes.promoTypeBlock')
                </div>
            </div>
        </div>

        <div class="part full-part">
            <ul>
                <li><br>
                    <input type="submit" value="{{trans('variables.save_it')}}" data-form-id="add-form">
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
