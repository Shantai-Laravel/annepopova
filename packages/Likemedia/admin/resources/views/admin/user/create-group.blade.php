@extends('admin.app')
@include('admin.nav-bar')
@include('admin.left-menu')
@section('content')

@include('admin.speedbar')

@if($groupSubRelations->new == 1)
    @include('admin.list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.add_element') => urlForFunctionLanguage($lang, 'createGroup/createitem'),
            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'groupCart/cartitems')
        ]
    ])
@else
    @include('admin.list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'groupCart/cartitems')
        ]
    ])
@endif

<div class="add-in-elem">
    @if(!is_null($modules_submenu_name))
        <div class="title">{{trans('variables.add_element_info_lent')}} "{{ $modules_submenu_name->{'name_'.$lang} }}"</div>
    @elseif(!is_null($modules_name))
        <div class="title">{{trans('variables.add_element_info_lent')}} "{{$modules_name->{'name_'.$lang} }}"</div>
    @endif
        <form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'savelist') }}" id="add-form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <ul>
                <li>
                    <label for="title">{{trans('variables.title_table')}}</label>
                    <input type="text" id="title" name="name">
                </li>
                @foreach($menu as $val)
                    <li>
                        <label for="modules_id[{{$val->id}}]">
                            <input class="radio squared" type="checkbox" id="modules_id[{{$val->id}}]" name="modules_id[{{$val->id}}]" value="{{$val->id}}" onclick="ChangeActionDisplay('{{$val->id}}')">
                            <span>{{$val->name_ru}}</span>
                        </label>
                        <ul class="hidden-checkboxes children-rights" id="taction[{{$val->id}}]">
                            <li>
                                <label for="new[{{$val->id}}]">
                                    <input class="radio squared" type="checkbox" id="new[{{$val->id}}]" name="new[{{$val->id}}]">
                                    <span>{{trans('variables.create_new_rights')}}</span>
                                </label>
                            </li>
                            <li>
                                <label for="save[{{$val->id}}]">
                                    <input class="radio squared" type="checkbox" id="save[{{$val->id}}]" name="save[{{$val->id}}]">
                                    <span>{{trans('variables.save_rights')}}</span>
                                </label>
                            </li>
                            <li>
                                <label for="active[{{$val->id}}]">
                                    <input class="radio squared" type="checkbox" id="active[{{$val->id}}]" name="active[{{$val->id}}]">
                                    <span>{{trans('variables.active_rights')}}</span>
                                </label>
                            </li>
                            <li>
                                <label for="del_to_rec[{{$val->id}}]">
                                    <input class="radio squared" type="checkbox" id="del_to_rec[{{$val->id}}]" name="del_to_rec[{{$val->id}}]">
                                    <span>{{trans('variables.del_to_rec_rights')}}</span>
                                </label>
                            </li>
                            <li>
                                <label for="del_from_rec[{{$val->id}}]">
                                    <input class="radio squared" type="checkbox" id="del_from_rec[{{$val->id}}]" name="del_from_rec[{{$val->id}}]">
                                    <span>{{trans('variables.del_from_rec_rights')}}</span>
                                </label>
                            </li>
                        </ul>
                    </li>
                @endforeach
                @if($groupSubRelations->save == 1)
                    <li>
                        <input type="submit" class="btn btn-primary" value="{{trans('variables.save_it')}}" onclick="saveForm(this)" data-form-id="add-form">
                    </li>
                @endif
            </ul>
        </form>

</div>

@stop

@section('footer')
    <footer>
        @include('admin.footer')
    </footer>
@stop
