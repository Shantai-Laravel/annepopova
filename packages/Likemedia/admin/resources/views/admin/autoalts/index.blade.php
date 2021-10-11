@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

  <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control panel</a></li>
          <li class="breadcrumb-item"><a href="{{ route('autoalt.index') }}">AutoAlt</a></li>
          <li class="breadcrumb-item active" aria-current="page">Create AutoAlt</li>
      </ol>
  </nav>

    <div class="list-content">
        <div class="tab-area">
            @include('admin::admin.alerts')
        </div>

        <div class="tab-content active-content" >
            <div class="part left-part">

              <form class="form-reg" action="{{ route('autoalt.exportCategories') }}" method="post">
                {{ csrf_field() }}
                <h6>Export Categories</h6>

                <input type="submit" value="Download">

              </form>

              <hr>

              <form class="form-reg" method="POST" action="{{ route('autoalt.store') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <ul>

                  <h6>Import Keywords</h6>

                    <li>
                        <input type="file" name="keywords">
                    </li>

                </ul>

                <input type="submit" value="{{trans('variables.save_it')}}">

              </form>

            </div>
        </div>
    </div>
@stop

@section('footer')
    <footer>
        @include('admin::admin.footer')
    </footer>
@stop
