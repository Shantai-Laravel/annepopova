@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('currencies.index') }}">Parameter Groups</a></li>
        <li class="breadcrumb-item active" aria-current="blog">Edit parameter Group</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Edit parameter Group </h3>
</div>
@include('admin::admin.alerts')
<div class="list-content">
    <div class="card">
        <div class="card-block">
            <div class="row">
                <div class="col-md-12">


            <form class="form-reg" role="form" method="POST" action="{{ route('parameter-groups.update', $parameterGroup->id) }}" id="add-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Key</label>
                            @if ($translationKeys->count() > 0)
                                <select class="form-control" name="key">
                                    @foreach ($translationKeys as $key => $translationKey)
                                        <optgroup label="{{ $translationKey->key }}">
                                            @if ($translationKey->translations()->count() > 0)
                                                @foreach ($translationKey->translations as $key => $translation)
                                                    <option value="{{ $translation->id }}" {{ $parameterGroup->translation_group_id ==  $translation->id ? 'selected' : ''}}>{{ $translation->key }}</option>
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Key (string)</label>
                            <input type="text" name="key_string" value="{{ $parameterGroup->key }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" value="Save" class="btn btn-primary">
                </div>
            </form>
        </div>
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
