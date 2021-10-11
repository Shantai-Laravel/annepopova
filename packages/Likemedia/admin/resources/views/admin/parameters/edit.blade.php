@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<div id="cover">
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('parameters.index') }}">Parameters</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Parameter</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Create Parameter </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
            trans('variables.add_element') => route('parameters.create'),
        ]
    ])
</div>
@include('admin::admin.alerts')
<div class="list-content">

    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#exampleModal">Images</button>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            <h5 class="modal-title" id="exampleModalLabel">Images</h5>

          </div>

          <form action="{{ url('/back/parameters/addImages') }}" method="post" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="parameter_id" value="{{ $parameter->id }}">

              <div class="modal-body">
                  <div class="row">
                      @if (count($values) > 0)
                          @foreach ($values as $key => $value)
                              <div class="col-md-12">
                                  <div class="col-md-3">
                                      {{-- {{ $value->translation->name }} --}}
                                  </div>
                                  <div class="col-md-3 text-right">
                                      @if ($value->image)
                                          <img src="/images/parameters/{{ $value->image }}" width="50px">
                                      @else
                                          <img src="/images/noimage.jpg" width="50px">
                                      @endif
                                  </div>
                                  <div class="col-md-6">
                                      <input type="file" name="images[{{$value->id}}]">
                                  </div>

                                  <div class="col-md-12">
                                      <hr>
                                  </div>
                              </div>
                          @endforeach
                      @endif
                  </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save Images</button>
              </div>
          </form>

        </div>
      </div>
    </div>


    <edit-parameter   :langs="{{ $langs }}"
                      :lang="{{ $lang }}"
                      :parameter="{{ $parameter }}"
                      :categories="{{ $categories }}"
                      :groups="{{ $groups }}"
                    >
    </edit-parameter>


</div>
</div>
<script src="{{asset('fronts/js/app.js?'.date('Y-m-sssss'))}}"></script>

@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
