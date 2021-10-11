@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('promocodes.index') }}">Promo codes </a></li>
        <li class="breadcrumb-item active" aria-current="promocode">Edit promo code</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Editarea promo code </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('promocodes.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">
    <form class="form-reg" role="form" method="POST" action="{{ route('promocodes.update', $promocode->id) }}" id="add-form" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        <div class="tab-content active-content">
            <div class="part full-part">

                <div class="row">
                    <div class="col-md-3">
                        <div class="col-md-12">
                            <div class="col-md-12 text-center">
                                <label> Promocode Name: </label>
                            </div>
                            <div class="col-md-12 text-center text-warning">
                                 {{ !is_null($promocode) ? $promocode->name : '----' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="type" class="text-center">Promocode Types:</label>
                            <select name="type" class="form-control promocode-type">
                                <option value="0">---</option>
                                @if (count($types) > 0)
                                    @foreach ($types as $key => $type)
                                        <option value="{{ $type->id }}" {{ $promocode->type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row response">
                    <script src="{{asset('admin::admin/js/datepicker.js')}}"></script>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="treshold">Treshold</label>
                            <input type='number' class="form-control"  name="treshold" value="{{ $promocode->treshold  }}" required/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="discount">Discount %</label>
                            <input type='number' class="form-control"  name="discount" value="{{ $promocode->discount }}" required/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="valid_from">Valid From</label>
                            <input type="date"  value="{{ date('Y-m-d', strtotime($promocode->valid_from)) }}" class="form-control datepicker-from"  name="valid_from" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="valid_to">Valid To <small class="text-danger">{{ !is_null($promoType) ? '+ '.$promoType->period .'days' : ''}}</small></label>
                            <input type="date"  value="{{ date('Y-m-d', strtotime($promocode->valid_to)) }}" class="form-control datepicker-to"  name="valid_to" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr>
                    </div>
                    <div class="col-md-7">
                        <div class="col-md-3 text-right">
                            <label> Period: </label>
                        </div>
                        <div class="col-md-5">
                            {{ !is_null($promoType) ? $promoType->period : '0' }} days
                        </div> <br><hr>
                    </div>
                    <div class="col-md-7">
                        <div class="col-md-3 text-right">
                            <label> Times: </label>
                        </div>
                        <div class="col-md-5">
                             {{ !is_null($promoType) ? $promoType->times : '0' }}
                        </div> <br><hr>
                    </div>
                    <div class="col-md-7">
                        <div class="col-md-3 text-right">
                            <label> Was used: </label>
                        </div>
                        <div class="col-md-5">
                            0
                        </div> <br><hr>
                    </div>
                    <div class="col-md-7">
                        <div class="col-md-3 text-right">
                            <label> User: </label>
                        </div>
                        <div class="col-md-5">
                            @if (!is_null($promocode->user()->first()))
                                <p>{{ $promocode->user()->first()->name }} {{ $promocode->user()->first()->surname }}</p>
                                <p>{{ $promocode->user()->first()->email }}</p>
                            @else
                                ----
                            @endif
                        </div> <br><hr>
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
