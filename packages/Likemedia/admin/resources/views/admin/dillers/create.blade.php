@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

  <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control panel</a></li>
          <li class="breadcrumb-item"><a href="{{ route('dillers.index') }}">Dillers</a></li>
          <li class="breadcrumb-item active" aria-current="page">Create Diller</li>
      </ol>
  </nav>

  <div class="title-block">
      <h3 class="title"> Create Diller </h3>
      @include('admin::admin.list-elements', [
      'actions' => [
            "add new user" => route('dillers.create'),
        ]
      ])
  </div>

    @if (count($countries) > 0)
      <div class="list-content">
          <div class="tab-area">
              @include('admin::admin.alerts')
          </div>
          <form class="form-reg" role="form" method="POST" action="{{ route('dillers.store') }}" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="part left-part">
                  <h5>Personal Information</h5>
                  <ul>
                      <li>
                          <label for="name">Customer Type</label>
                          <select class="form-control" name="customer_type">
                              <option value="diller">Diller</option>
                              <option value="custumer">Customer</option>
                          </select>
                      </li>
                      <li>
                          <label for="name">Diller Group</label>
                          <select class="form-control" name="diller_group">
                              <option value="0">Retail Prices</option>
                              @foreach ($dillerGroups as $key => $dillerGroup)
                                  <option value="{{ $dillerGroup->id }}">{{ $dillerGroup->name }}</option>
                              @endforeach
                          </select>
                      </li>
                      <li>
                          <label for="name">Name</label>
                          <input type="text" name="name" class="name" id="name" value="{{old('name')}}">
                      </li>
                      <li>
                          <label for="company">Company</label>
                          <input type="text" name="company" class="name" id="company" value="{{old('company')}}">
                      </li>
                      <li>
                          <label for="email">Email</label>
                          <input type="email" name="email" class="name" id="email" value="{{old('email')}}">
                      </li>
                      <li>
                          <label for="phone">Phone</label>
                          <input type="text" name="phone" class="name" id="phone" value="{{old('phone')}}">
                      </li>
                      <hr>
                      <li>
                          <label>Countries</label>
                          <select class="" name="country_id">
                              @foreach ($countries as $key => $country)
                                  <option value="{{ $country->id }}">{{ $country->translation->name }}</option>
                              @endforeach
                          </select>
                      </li>
                      <li>
                          <label>Currecy</label>
                          <select class="" name="currency_id">
                              @foreach ($currencies as $key => $currency)
                                  <option value="{{ $currency->id }}">{{ $currency->abbr }}</option>
                              @endforeach
                          </select>
                      </li>
                      <li>
                          <label>Languages</label>
                          <select class="" name="language_id">
                              @foreach ($languages as $key => $language)
                                  <option value="{{ $language->id }}" >{{ $language->lang }}</option>
                              @endforeach
                          </select>
                      </li>
                      <hr>
                      <h5>Password</h5>
                      <li>
                          <label for="password">Password</label>
                          <input type="password" name="password" class="name" id="password">
                      </li>
                      <li>
                          <label for="repeatpassword">Repeat Password</label>
                          <input type="password" name="repeatpassword" class="name" id="repeatpassword">
                      </li>
                      <li>
                          <input type="submit" value="Save">
                      </li>
                  </ul>
              </div>
              <div class="part right-part">
                  <div class="form-group">
                      <label><input class="checkbox" type="checkbox" name="active" >
                          <span>Active</span>
                      </label>
                  </div>
                  <h6>Shipping Information</h6>
                    <ul>
                        <li>
                          <label for="country">Country</label>
                          <select name="country" class="name filterCountries" data-id="0" id="country">
                              <option disabled selected>Выберите страну</option>
                              @foreach ($countries as $onecountry)
                                  <option value="{{$onecountry->id}}">{{$onecountry->translation->name}}</option>
                              @endforeach
                          </select>
                        </li>
                        <li>
                          <label for="region">Region</label>
                          <input type="text" name="region" class="name" id="region" value="">
                        </li>
                        <li>
                          <label for="location">City</label>
                          <input type="text" name="location" class="name" id="location" value="">
                        </li>
                        <li>
                            <label for="address">Address</label>
                            <input type="text" name="address" class="name" id="address" value="">
                        </li>
                        <li>
                            <label for="code">Code</label>
                            <input type="text" name="code" class="name" id="code" value="">
                        </li>
                        <li>
                            <label for="apartment">Apartment</label>
                            <input type="text" name="apartment" class="name" id="apartment" value="">
                        </li>
                    </ul>
                    <h6>Payment Method</h6>

                    <ul>
                        <li>
                          <label for="country">Payment</label>
                          <select name="payment_id" class="name filterCountries">
                              <option disabled selected>Choose method</option>
                              @foreach ($payments as $payment)
                                  <option value="{{$payment->id}}">{{$payment->translation->name}}</option>
                              @endforeach
                          </select>
                        </li>
                    </ul>
              </div>
          </form>
      </div>
    @else
      <div class="empty-response">Please add countries</div>
    @endif
@stop

@section('footer')
    <footer>
        @include('admin::admin.footer')
    </footer>
@stop
