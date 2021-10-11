@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('promocodes.index') }}">Promocodes </a></li>
        <li class="breadcrumb-item"><a href="{{ route('promocodesType.index') }}">Promocode Types </a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Promocode Type</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Editarea promocode type </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('promocodes.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">
    <form class="form-reg" role="form" method="POST" action="{{ route('promocodesType.update', $promocode->id) }}" id="add-form" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        <div class="tab-content active-content">
            <div class="part full-part">

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $promocode->name }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="discount">Discount %</label>
                            <input type="number" name="discount" class="form-control" value="{{ $promocode->discount }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="times">Times</label>
                            <input type="text" name="times" class="form-control" value="{{ $promocode->times }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="treshold">Treshold</label>
                            <input type='number' class="form-control" name="treshold" value="{{ $promocode->treshold }}"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="period">Period</label>
                            <input type='number' class="form-control" name="period" value="{{ $promocode->period }}"/>
                        </div>
                    </div>
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
