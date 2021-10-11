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

    <form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'save/'.$user->id) }}" id="edit-form">
        <div class="part left-part">

            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <ul>
                <li>
                    <label for="login">{{trans('variables.login_text')}}</label>
                    <input type="text" name="login" id="login" value="{{$user->login or ''}}">
                </li>
                <li>
                    <label for="name">{{trans('variables.name_text')}}</label>
                    <input type="text" name="name" id="name" value="{{$user->name or ''}}">
                </li>
                <li>
                    <label for="email">{{trans('variables.email_text')}}</label>
                    <input type="email" name="email" id="email" value="{{$user->email or ''}}" autocomplete="off">
                </li>
                <li>
                    <label for="password">{{trans('variables.password_text')}}</label>
                    <input type="password" name="password" id="password" placeholder="{{trans('variables.empty_pass')}}" autocomplete="off">
                </li>

            </ul>
        </div>

        <div class="part right-part">
            <ul>
                <li>
                    <label>Создан</label>
                    <input type="text" value="{{$user->created_at or ''}}" readonly>
                </li>
                <li>
                    <label>Обновлён</label>
                    <input type="text" value="{{$user->updated_at or ''}}" readonly>
                </li>
                @if($groupSubRelations->save == 1)
                    <input type="submit" value="{{trans('variables.edit')}}" onclick="saveForm(this)" data-form-id="edit-form">
                @endif
            </ul>
        </div>

    </form>
</div>
@stop

@section('footer')
    <footer>
        @include('admin::admin.footer')
    </footer>
    {{--<footer style="position: fixed; bottom: 0; right: 0; left:0;">--}}
        {{--@include('footer')--}}
    {{--</footer>--}}
@stop
