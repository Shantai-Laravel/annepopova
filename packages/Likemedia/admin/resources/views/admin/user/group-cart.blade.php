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

@if(!$deleted_group->isEmpty())
    <table class="el-table">
        <thead>
            <tr>
                <th>{{trans('variables.title_table')}}</th>
                <th>{{trans('variables.reestablish_table')}}</th>
                @if($groupSubRelations->del_from_rec == 1)
                    <th>{{trans('variables.delete_table')}}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($deleted_group as $group)
                <tr>
                    <td>{{$group->name}}</td>
                    <td>
                        <a href="{{urlForFunctionLanguage($lang, str_slug($group->name).'/restore/'.$group->id)}}"><img src="/images/restore.gif" alt=""></a>
                    </td>
                    @if($groupSubRelations->del_from_rec == 1)
                        <td class="destroy-element"><a href="{{urlForFunctionLanguage($lang, str_slug($group->name).'/destroygroup/'.$group->id)}}"><img src="/images/trash_ico.png" height="15" width="12" alt=""></a></td>
                    @endif
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan=5>{!! $deleted_group->links() !!}</td>
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
