@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

  <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control panel</a></li>
          <li class="breadcrumb-item"><a href="{{ route('diller-groups.index') }}">Diller Group</a></li>
          <li class="breadcrumb-item active" aria-current="page">Create Diller Group</li>
      </ol>
  </nav>

  <div class="title-block">
      <h3 class="title"> Create Diller Group</h3>
      @include('admin::admin.list-elements', [
      'actions' => [
            "add new user" => route('diller-groups.create'),
        ]
      ])
  </div>

    @if (count($countries) > 0)
      <div class="list-content">
          <div class="tab-area">
              @include('admin::admin.alerts')
          </div>
          <form class="form-reg" role="form" method="POST" action="{{ route('diller-groups.store') }}" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="part left-part">
                  <ul>
                      <li>
                          <label for="name">Name</label>
                          <input type="text" name="name" id="name" value="{{old('name')}}">
                      </li>
                      <li>
                          <label for="name">Type</label>
                          <select class="form-control" name="type">
                              <option value="discounter">Discounter</option>
                              <option value="custom_prices" disabled>Custom Prices</option>
                          </select>
                      </li>
                      <li>
                          <label for="discount">Discount %</label>
                          <input type="number" name="discount" class="name" id="discount" value="{{old('discount')}}">
                      </li>
                      <li>
                          <label for="name">Discount Applied On</label>
                          <select class="form-control" name="applied_on">
                              <option value="retail">Retail Prices</option>
                              <option value="b2b">B2B Prices</option>
                          </select>
                      </li>
                      <hr>
                      <li>
                          <input type="submit" value="Save">
                      </li>
                  </ul>
              </div>
              <div class="part right-part">
                  <h6>Currencies:</h6>
                <ul>
                    <li>
                         @foreach ($currencies as $key => $currency)
                            <label>
                                <input class="checkbox" type="checkbox" name="currencies[{{ $currency->id }}]">
                                <span>{{ $currency->abbr }}</span>
                            </label>
                        @endforeach
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
