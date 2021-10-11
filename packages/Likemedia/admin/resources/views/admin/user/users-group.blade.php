@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

@include('admin::admin.speedbar')

@if($groupSubRelations->new == 1)
    @include('admin::admin.list-elements', [
        'actions' => [
            'Список групп' => urlForFunctionLanguage($lang, ''),
            'Добавить группу' => urlForFunctionLanguage($lang, 'createGroup/createitem'),
            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'groupCart/cartitems')
        ]
    ])
@else
    @include('admin::admin.list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'groupCart/cartitems')
        ]
    ])
@endif

@if(!$user_group->isEmpty())
    <h4><small><i class="fa fa-thumb-tack"></i> Группы Пользователей</small></h4>
    <table class="el-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название группы</th>
                <th>Зайти в группу</th>
                <th>Редактировать права</th>
                @if($groupSubRelations->del_to_rec == 1)
                    <th>{{trans('variables.delete_table')}}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($user_group as $key => $group)
                <tr>
                    <td>#{{ $key + 1}}</td>
                    <td>{{$group->name}}</td>
                    <td><a href="{{urlForFunctionLanguage($lang, str_slug($group->name).'/memberslist')}}"><i class="fa fa-sign-in"></i></a></td>
                    <td>
                        <a href="{{urlForFunctionLanguage($lang, str_slug($group->name).'/editlist/'.$group->id)}}"><i class="fa fa-edit"></i></a>
                    </td>
                    @if($groupSubRelations->del_to_rec == 1)
                        <td class="destroy-element"><a href="{{urlForFunctionLanguage($lang, str_slug($group->name).'/destroygroup/'.$group->id)}}"><i class="fa fa-trash"></i></a></td>
                    @endif
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan=5>{!! $user_group->links() !!}</td>
            </tr>
        </tfoot>

    </table>
@else
    <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
@endif
@stop

@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
