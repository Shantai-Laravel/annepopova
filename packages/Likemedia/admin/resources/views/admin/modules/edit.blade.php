@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('modules.index') }}">Modules</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Module</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Edit Module </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
            'Add new' => route('modules.create'),
        ]
    ])
</div>
<div class="list-content">
    <form class="form-reg" method="POST" action="{{ route('modules.update', $module->id) }}">
        {{ csrf_field() }} {{ method_field('PATCH') }}
        <div class="part left-part">
            <ul>
                <li>
                    <label for="name">Title</label>
                    <input type="text" name="name" value="{{$module->name}}"/>
                </li>
                <li>
                    <label>Description</label>
                    <textarea name="description">{{$module->description}}</textarea>
                </li>
                <li>
                    <label for="src">Link</label>
                    <input type="text" name="src" id="src" value="{{ $module->src }}">
                </li>
                <li>
                    <label for="table_name">Table name</label>
                    <input type="text" name="table_name" id="table_name" value="{{ $module->table_name }}">
                </li>
                <li>
                    <label for="icon">Icon</label>
                    <input type="text" name="icon" id="icon" value="{{ $module->icon }}">
                </li>
                <div class="text-center alert alert-warning">
                    <a target="_blank" class="pull-center" href="http://fontawesome.io/icons/">Font awesome</a>
                </div>
                <li>
                    <label for="parent_id">Includes</label>
                    <select name="parent_id">
                        <option value="0">---</option>
                        @if (!empty($allModules))
                            @foreach ($allModules as $key => $oneModule)
                                <option value="{{ $oneModule->id }}" {{ $oneModule->id == $module->parent_id ? 'selected' : '' }}>{{ $oneModule->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </li><br>
                <input type="submit" value="Save" class="btn btn-primary">
            </ul>
        </div>
    </form>
</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
