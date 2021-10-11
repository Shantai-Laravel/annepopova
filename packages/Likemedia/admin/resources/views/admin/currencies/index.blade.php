@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="blog">Currencies </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title">Currencies</h3>
</div>
@include('admin::admin.alerts')
<div class="card">
    <div class="card-block">
        <div class="row">
            <div class="col-md-8">
                @if(!$currencies->isEmpty())
                <table class="table table-hover table-striped" id="tablelistsorter">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Abbr</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Active</th>
                            <th>Rate</th>
                            <th class="text-center">Countries</th>
                            <th>Edit</th>
                            <th class="text-center">Delete/Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($currencies as $key => $currency)
                        <tr id="{{ $currency->id }}">
                            <td>
                                {{ $key + 1 }}
                            </td>
                            <td>
                                {{ @$currency->translation()->name }}
                            </td>
                            <td>
                                {{ $currency->abbr }}
                            </td>
                            <td class="text-center">
                                @if ($currency->type == 1)
                                    <span class="label label-success">Main</span>
                                @else
                                    <span class="label label-primary">Secondary</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($currency->type == 0)
                                    @if ($currency->active == 1)
                                        <span class="label label-success">Active</span>
                                    @else
                                        <span class="label label-danger">Inactive</span>
                                    @endif
                                @else
                                    ---
                                @endif
                            </td>
                            <td>
                                @if ($currency->type != 1)
                                    {{ $currency->rate }}
                                @else
                                    ----
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success">{{ count($currency->countries) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('currencies.edit', $currency->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                @if ($currency->type == 0)
                                    <button type="button" class="btn-link" data-toggle="modal" data-target="#actions{{ $currency->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                @else
                                    ---
                                @endif

                                <div class="modal fade" id="actions{{ $currency->id }}" tabindex="-1" role="dialog" aria-labelledby="actions{{ $currency->id }}" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                          <h5 class="modal-title" id="exampleModalLabel">Actions {{ $currency->translation->name }}</h5>

                                      </div>
                                      <div class="modal-body">
                                          @if ($currency->countries()->count() > 0)
                                              This currency is associated with countries:
                                              <span class="text-primary">
                                                  @foreach ($currency->countries as $key => $country)
                                                       {{' ' . $country->translation->name . ', ' }}
                                                  @endforeach
                                              </span>
                                              . These countries will be associated with main currency.
                                          @endif
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <form action="{{ url('/back/currencies-deactivate/'.$currency->id) }}" method="post">
                                            {{ csrf_field() }}
                                            @if ($currency->active )
                                                <button type="submit" class="btn btn-primary">Deactivate</button>
                                            @else
                                                <button type="submit" class="btn btn-primary">Activate</button>
                                            @endif
                                        </form>
                                        <form action="{{ route('currencies.destroy', $currency->id) }}" method="post">
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
                            <td colspan=10></td>
                        </tr>
                    </tfoot>
                </table>
                @else
                <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
                @endif
            </div>
            <div class="col-md-4">
                <a href="{{ url('/back/currencies-regenerate') }}" class="btn btn-primary btn-block"> <i class="fa fa-refresh"></i> Refresh</a>
                <h5>Create new</h5>
                <hr>
                <form method="POST" class="form-reg" action="{{ route('currencies.store') }}">
                    {{ csrf_field() }}
                    <label for="">Name</label>
                    @foreach ($langs as $key => $lang)
                    <div class="input-group">
                        <input type="text" name="name_{{ $lang->lang }}" class="form-control" required>
                        <span class="input-group-addon">{{ $lang->lang }}</span>
                    </div>
                    @endforeach
                    <div class="form-group">
                        <label for="">Abbr</label>
                        <input type="text" name="abbr" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Type</label>
                        <select class="form-control" name="type">
                            <option value="0">Secondary</option>
                            <option value="1">Main</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Exchange rate</label>
                        <input type="number" name="rate" step="0.01" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Correction factor </label>
                        <input type="number" name="correction_factor" step="0.01" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="active" checked>
                            <span> Active</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="exchange_dependable" checked>
                            <span> Exchange dependeble</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
@section('footer')

<footer>
    @include('admin::admin.footer')
</footer>
@stop
