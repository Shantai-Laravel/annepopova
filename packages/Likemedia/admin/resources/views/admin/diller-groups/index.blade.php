@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Diller Groups</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Diller Groups </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
            'Add new Diller' => route('diller-groups.create'),
        ]
    ])
</div>
<div class="list-content">
    <div class="tab-area">
        @include('admin::admin.alerts')
    </div>
    @if(!$groups->isEmpty())
    <div class="card">
        <div class="card-block">
            <table class="table table-hover table-striped" id="tablelistsorter">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th class="text-center">Type</th>
                        <th>Discount</th>
                        <th class="text-center">Discount applied on</th>
                        <th class="text-center">Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groups as $key => $group)
                    <tr id="{{ $group->id }}">
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>
                            {{ $group->name }}
                        </td>
                        <td class="text-center">
                            <span class="label label-primary">
                                {{ $group->type }}
                            </span>
                        </td>
                        <td>
                            {{ $group->discount }} %
                        </td>
                        <td class="text-center">
                            <span class="label label-success">
                                {{ $group->applied_on ? $group->applied_on : '---'}}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('diller-groups.edit', $group->id) }}">
                            <i class="fa fa-edit"></i>
                            </a>
                        </td>
                        <td class="destroy-element">
                            @if ($key !== 0)
                                <form action="{{ route('diller-groups.destroy', $group->id) }}" method="post">
                                    {{ csrf_field() }} {{ method_field('DELETE') }}
                                    <button type="submit" class="btn-link">
                                        <a href=""><i class="fa fa-trash"></i></a>
                                    </button>
                                </form>
                            @endif
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
    </div>
    @else
        <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
    @endif
</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
