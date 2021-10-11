@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="promotion">Promo codes </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Promo codes </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
        trans('variables.add_element') => route('promocodes.create'),
        'Promo codes Type' => route('promocodesType.index'),
    ]
    ])
</div>

<div class="row">
    <div class="col-md-4">
        <div class="input-group mb-3">
          <label class="input-group-text" for="inputGroupSelect01">Sorteaza dupa: &nbsp; &nbsp; &nbsp; </label>
          <select class="custom-select" id="inputGroupSelect01" onchange="if (this.value) window.location.href=this.value">
            <option value="{{ url('back/promocodes?type='.Request::get('type')) }}" {{ Request::get('sort') == '' ? 'selected' : '' }}>Dupa ID</option>
            <option value="{{ url('back/promocodes?sort=name&type='.Request::get('type')) }}" {{ Request::get('sort') == 'name' ? 'selected' : '' }}>Dupa denumire</option>
            <option value="{{ url('back/promocodes?sort=valid_to&type='.Request::get('type')) }}" {{ Request::get('sort') == 'valid_to' ? 'selected' : '' }}>Dupa valabilitate</option>
          </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="input-group mb-3">
          <label class="input-group-text" for="inputGroupSelect01">Sorteaza dupa tip: &nbsp; &nbsp; &nbsp; </label>
          <select class="custom-select" id="inputGroupSelect01" onchange="if (this.value) window.location.href=this.value">
            <option value="{{ url('back/promocodes?sort='.Request::get('sort').'&type=') }}" {{ Request::get('type') == '' ? 'selected' : '' }}>Toate</option>
            @if (!empty($promocodeTypes))
                @foreach ($promocodeTypes as $key => $item)
                    <option value="{{ url('back/promocodes?sort='.Request::get('sort').'&type='.$item->type->id) }}" {{ Request::get('type') == $item->type->id ? 'selected' : '' }}>
                        {{ $item->type->name }}
                     </option>
                @endforeach
            @endif
          </select>
        </div>
    </div>
</div>

@include('admin::admin.alerts')

@if(!$promocodes->isEmpty())
<div class="card">
    <div class="card-block">
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Promo code</th>
                    <th>Type</th>
                    <th>Discount</th>

                    <th>Treshold</th>
                    <th>Period</th>
                    <th>Times</th>
                    <th>Was used</th>

                    <th>Valabil de la</th>
                    <th>Valabil pana la</th>
                    <th>Status</th>
                    <th>{{trans('variables.edit_table')}}</th>
                    <th>{{trans('variables.delete_table')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($promocodes as $key => $promotion)
                <tr id="{{ $promotion->id }}">
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>
                        {{ $promotion->name }}
                    </td>
                    <td>
                        {{ $promotion->type->name }}
                    </td>
                    <td>
                        {{ $promotion->discount }} %
                    </td>
                    <td>
                        {{ $promotion->treshold }}
                    </td>
                    <td>
                        {{ $promotion->period }}
                    </td>
                    <td>
                        {{ $promotion->times }}
                    </td>
                    <td>
                        {{ $promotion->to_use }}
                    </td>
                    <td>
                        {{ $promotion->valid_from }}
                    </td>
                    <td>
                        {{ $promotion->valid_to }}
                    </td>
                    <td>
                        @if (strtotime(date('m/d/Y')) > strtotime($promotion->valid_to))
                            <span class="label label-danger">expired</span>
                        @else
                            @if ($promotion->to_use == 0)
                                <span class="label label-success">valid</span>
                            @endif
                            @if ($promotion->to_use == $promotion->times)
                                <span class="label label-warning">used</span>
                            @endif
                            @if (($promotion->to_use < $promotion->times) && ($promotion->to_use > 0 ))
                                <span class="label label-primary">partially</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('promocodes.edit', $promotion->id) }}">
                        <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="destroy-element">
                        <form action="{{ route('promocodes.destroy', $promotion->id) }}" method="post">
                            {{ csrf_field() }} {{ method_field('DELETE') }}
                            <button type="submit" class="btn-link">
                                <a href=""><i class="fa fa-trash"></i></a>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan=13></td>
                </tr>
            </tfoot>
        </table>
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
