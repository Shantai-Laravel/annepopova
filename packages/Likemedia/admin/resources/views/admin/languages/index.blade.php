@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Languages</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Languages </h3>
</div>

@include('admin::admin.alerts')

@if(!empty($languages))
<div class="card">
    <div class="card-block">
        <div class="row">
            <div class="col-md-8">
                <table class="table table-hover table-striped" id="tablelistsorter">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Abbr</th>
                            <th>Name</th>
                            <th class="text-center">Default</th>
                            <th class="text-center">Active</th>
                            <th class="text-center">Countries</th>
                            <th class="text-center">Delete/Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($languages as $key => $language)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $language->lang }}</td>
                            <td>{{ $language->description }}</td>
                            <td class="text-center">
                                @if($language->default == 1)
                                    <span class="label label-success">Default</span>
                                @else
                                <form action="{{ route('languages.default', $language->id) }}" method="post">
                                    {{ csrf_field() }} {{ method_field('PATCH') }}
                                    <button type="submit" class="btn-link">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($language->default == 1)
                                    ---
                                @else
                                <form action="{{ route('languages.active', $language->id) }}" method="post">
                                    {{ csrf_field() }} {{ method_field('PATCH') }}
                                    <button type="submit" class="btn-link">
                                        @if ($language->active == 1)
                                            <i class="fa fa-plus"></i>
                                        @else
                                            <i class="fa fa-minus"></i>
                                        @endif
                                    </button>
                                </form>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success">{{ count($language->countries) }}</span>
                            </td>
                            <td class="text-center">
                                @if ($language->default == 0)
                                    <button type="button" data-toggle="modal" data-target="#actions{{ $language->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                @else
                                    ---
                                @endif
                                <div class="modal fade" id="actions{{ $language->id }}" tabindex="-1" role="dialog" aria-labelledby="actions{{ $language->id }}" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                          <h5 class="modal-title" id="exampleModalLabel">Actions {{ $language->lang }}</h5>
                                      </div>
                                      <div class="modal-body">
                                           This lang is associated with countries:
                                           <span class="text-primary">
                                               @foreach ($language->countries as $key => $country)
                                                    {{' ' . $country->translation->name . ', ' }}
                                               @endforeach
                                           </span>
                                           . These countries will be associated with main lang.
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                        <form action="{{ route('languages.active', $language->id) }}" method="post">
                                            {{ csrf_field() }} {{ method_field('PATCH') }}
                                            @if ($language->active )
                                                <button type="submit" class="btn btn-primary">Deactivate</button>
                                            @else
                                                <button type="submit" class="btn btn-primary">Activate</button>
                                            @endif
                                        </form>

                                        <form action="{{ route('languages.destroy', $language->id) }}" method="post">
                                            {{ csrf_field() }} {{ method_field('DELETE') }}
                                            <button type="submit" class="btn btn-primary">Delete</button>
                                        </form>

                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </td>
                        </tr>


                        @endforeach

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan=7></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-md-4">
                <h5>Create new</h5><hr>
                <form class="form-reg" role="form" method="POST" action="{{ route('languages.store') }}" id="add-form">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">Abbreviation</label>
                        <input type="text" name="name" id="name" placeholder="en" value="{{ old('name')}}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Name</label>
                        <input type="text" name="description" id="description" placeholder="English" value="{{ old('description')}}"  class="form-control" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Save" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@else
<div class="empty-response">{{trans('variables.list_is_empty')}}</div>
@endif
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
