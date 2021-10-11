@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

@include('admin::admin.speedbar')

@if($groupSubRelations->new == 1)
    @include('admin::admin.list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.add_element') => urlForFunctionLanguage($lang, 'createGroup/createitem'),
            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'groupCart/cartitems'),
            trans('variables.edit_element') => urlForFunctionLanguage($lang, str_slug($group->name).'/editlist/'.$group->id)
        ]
    ])
@else
    @include('admin::admin.list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'groupCart/cartitems'),
            trans('variables.edit_element') => urlForFunctionLanguage($lang, str_slug($group->name).'/editlist/'.$group->id)
        ]
    ])
@endif

<div class="add-in-elem">
    <form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'savelist/'.$group->id) }}" id="edit-form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <ul class="form-reg-ul">
            <li>
                <label for="title">{{trans('variables.title_table')}}</label>
                <input type="text" id="title" name="name" value="{{$group->name}}">
            </li>
            <ul>
                @foreach($result as $key => $userRoles)
                    <li>
                        <label for="modules_id[{{$userRoles['name_'.$lang]['id']}}]">
                            <input class="radio squared" type="checkbox" id="modules_id[{{$userRoles['name_'.$lang]['id']}}]" name="modules_id[{{$userRoles['name_'.$lang]['id']}}]" value="{{$userRoles['name_'.$lang]['id']}}" onclick="ChangeActionDisplay('{{$userRoles['name_'.$lang]['id']}}')" {{in_array($userRoles['name_'.$lang]['id'], $arr) ? 'checked' : ''}}>
                             <span>{{$userRoles['name_'.$lang]['name_'.$lang]}}</span>
                        </label>
                        @foreach($userRoles['roles'] as $manyRoles)
                            <ul id="taction[{{$userRoles['name_'.$lang]['id']}}]" class="{{in_array($userRoles['name_'.$lang]['id'], $arr) ? '' : 'hidden-checkboxes' }} children-rights">
                                <li>
                                    <label for="new[{{$userRoles['name_'.$lang]['id']}}]">
                                        <input class="radio squared" type="checkbox" id="new[{{$userRoles['name_'.$lang]['id']}}]" name="new[{{$userRoles['name_'.$lang]['id']}}]" {{in_array('1'.$userRoles['name_'.$lang]['id'], $new) ? 'checked' : '' }}>
                                        <span>{{trans('variables.create_new_rights')}}</span>
                                    </label>
                                </li>
                                <li>
                                    <label for="save[{{$userRoles['name_'.$lang]['id']}}]">
                                        <input class="radio squared" type="checkbox" id="save[{{$userRoles['name_'.$lang]['id']}}]" name="save[{{$userRoles['name_'.$lang]['id']}}]" {{in_array('1'.$userRoles['name_'.$lang]['id'], $save) ? 'checked' : '' }}>
                                        <span>{{trans('variables.save_rights')}}</span>
                                    </label>
                                </li>
                                <li>
                                    <label for="active[{{$userRoles['name_'.$lang]['id']}}]">
                                        <input class="radio squared" type="checkbox" id="active[{{$userRoles['name_'.$lang]['id']}}]" name="active[{{$userRoles['name_'.$lang]['id']}}]" {{in_array('1'.$userRoles['name_'.$lang]['id'], $active) ? 'checked' : '' }}>
                                        <span>{{trans('variables.active_rights')}}</span>
                                    </label>
                                </li>
                                <li>
                                    <label for="del_to_rec[{{$userRoles['name_'.$lang]['id']}}]">
                                        <input class="radio squared" type="checkbox" id="del_to_rec[{{$userRoles['name_'.$lang]['id']}}]" name="del_to_rec[{{$userRoles['name_'.$lang]['id']}}]" {{in_array('1'.$userRoles['name_'.$lang]['id'], $del_to_rec) ? 'checked' : '' }}>
                                        <span>{{trans('variables.del_to_rec_rights')}}</span>
                                    </label>
                                </li>
                                <li>
                                    <label for="del_from_rec[{{$userRoles['name_'.$lang]['id']}}]">
                                        <input class="radio squared" type="checkbox" id="del_from_rec[{{$userRoles['name_'.$lang]['id']}}]" name="del_from_rec[{{$userRoles['name_'.$lang]['id']}}]" {{in_array('1'.$userRoles['name_'.$lang]['id'], $del_from_rec) ? 'checked' : '' }}>
                                        <span>{{trans('variables.del_from_rec_rights')}}</span>
                                    </label>
                                </li>
                            </ul>
                            @break
                        @endforeach
                    </li>
                @endforeach
            </ul>
            @if($groupSubRelations->save == 1)
                <li>
                    <input type="submit" value="{{trans('variables.save_it')}}" onclick="saveForm(this)" data-form-id="edit-form">

            @endif
        </ul>
    </form>

</div>

@stop

@section('footer')
    <footer>
        @include('admin::admin.footer')
    </footer>
@stop
