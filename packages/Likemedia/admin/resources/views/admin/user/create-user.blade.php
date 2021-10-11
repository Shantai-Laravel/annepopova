@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

@include('admin::admin.speedbar')

@if($groupSubRelations->new == 1)
    @include('admin::admin.list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
            trans('variables.add_element') => urlForLanguage($lang, 'createuser'),
        ]
    ])
@else
    @include('admin::admin.list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
        ]
    ])
@endif

<div class="list-content">
    <form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'save') }}" id="add-form">
        <div class="part left-part">
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <ul>
                <li>
                    <label for="login">{{trans('variables.login_text')}}</label>
                    <input type="text" name="login" id="login">
                </li>
                <li>
                    <label for="name">{{trans('variables.name_text')}}</label>
                    <input type="text" name="name" id="name">
                </li>
                <li>
                    <label for="email">{{trans('variables.email_text')}}</label>
                    <input type="email" name="email" id="email">
                </li>
                <li>
                    <label for="password">{{trans('variables.password_text')}}</label>
                    <input type="password" name="password" id="password" autocomplete="off">
                </li>
            </ul>
            @if($groupSubRelations->save == 1)
                <input type="submit" class="btn btn-primary" value="{{trans('variables.save_it')}}" onclick="saveForm(this)" data-form-id="add-form">
            @endif
        </div>

    </form>
</div>
@stop

@section('footer')
    <footer>
        @include('admin::admin.footer')
    </footer>
@stop
