@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Parameters</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Parameters </h3>
    @include('admin::admin.list-elements', [
        'actions' => [
            trans('variables.add_element') => route('parameters.create'),
            'Parameter Groups' => url('/back/parameter-groups'),
        ]
    ])
</div>
@include('admin::admin.alerts')
<div class="card">
    <div class="card-block">
        <div class="row">
            <div class="col-md-8">
                @if(!$parameterGroups->isEmpty())
                    <table class="table table-hover table-striped" id="tablelistsorter">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Key</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parameterGroups as $key => $parameterGroup)
                            <tr>
                                <td>
                                    {{ $key + 1 }}
                                </td>
                                <td>
                                    {{ $parameterGroup->key }}
                                </td>
                                <td>
                                    <a href="{{ route('parameter-groups.edit', $parameterGroup->id) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                                <td class="destroy-element">
                                    <form action="{{ route('parameter-groups.destroy', $parameterGroup->id) }}" method="post">
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
                                <td colspan=9></td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
                @endif
            </div>
            <div class="col-md-4">
                <h5>Add new</h5>
                <form class="" action="{{ route('parameter-groups.store') }}" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="">Key</label>
                        @if ($translationKeys->count() > 0)
                            <select class="form-control" name="key">
                                @foreach ($translationKeys as $key => $translationKey)
                                    <optgroup label="{{ $translationKey->key }}">
                                        @if ($translationKey->translations()->count() > 0)
                                            @foreach ($translationKey->translations as $key => $translation)
                                                <option value="{{ $translation->id }}">{{ $translation->key }}</option>
                                            @endforeach
                                        @endif
                                    </optgroup>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Save">
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
