@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('frontusers.index') }}">Front Users</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Front User</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Edit Front User </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
            'Add new user' => route('frontusers.create'),
        ]
    ])
</div>
<div class="list-content">
    <div class="tab-area">
        @include('admin::admin.alerts')
    </div>
    <form class="form-reg" role="form" method="POST" action="{{ route('frontusers.update', $user->id) }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
        <div class="part left-part">
            <h5>User Information</h5>
            <ul>
                <li>
                    <label for="name">Customer Type</label>
                    <select class="form-control" name="customer_type">
                        <option value="custumer">Customer</option>
                        <option value="diller">Diller</option>
                    </select>
                </li>
                <li>
                    <label for="name">Name</label>
                    <input type="text" name="name" class="name" id="name" value="{{$user->name}}">
                </li>
                <li>
                    <label for="company">Company</label>
                    <input type="text" name="company" class="name" id="company" value="{{$user->company}}">
                </li>
                <li>
                    <label for="email">Email</label>
                    <input type="email" name="email" class="name" id="email" value="{{$user->email}}">
                </li>
                <li>
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" class="name" id="phone" value="{{$user->phone}}">
                </li>
                <li>
                    <label>Countries</label>
                    <select class="" name="country_id">
                    @foreach ($countries as $key => $country)
                    <option value="{{ $country->id }}" {{ $user->country_id == $country->id  ? 'selected' : '' }}>{{ $country->translation->name }}</option>
                    @endforeach
                    </select>
                </li>
                <li>
                    <label>Currecy</label>
                    <select class="" name="currency_id">
                    @foreach ($currencies as $key => $currency)
                    <option value="{{ $currency->id }}"  {{ $user->currency_id == $currency->id ? 'selected' : '' }}>{{ $currency->abbr }}</option>
                    @endforeach
                    </select>
                </li>
                <li>
                    <label>Languages</label>
                    <select class="" name="language_id">
                    @foreach ($languages as $key => $language)
                    <option value="{{ $language->id }}" {{ $user->lang_id == $language->id ? 'selected' : '' }}>{{ $language->lang }}</option>
                    @endforeach
                    </select>
                </li>
                <li>
                    <input type="submit" value="Save">
                </li>
            </ul>
        </div>
    <div class="part right-part">
        <h5>Shipping Information</h5>
        <div class="address">
            {{ csrf_field() }}
            <div class="frAdr">
                <div class="address">
                    @if (!is_null($user->address))
                        @include('admin::admin.frontusers.editAddress', ['address' => $user->address])
                    @else
                        @include('admin::admin.frontusers.address')
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>

</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
