@extends('admin::admin.google.app')
@section('content')

<div class="wrapp">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th> group key </th>
                <th> trans key </th>
                @foreach ($langs as $key => $lang)
                    <th>{{ $lang->lang }}</th>
                @endforeach
            </tr>
        </thead>
        @foreach ($translationGroups as $key => $group)
            @foreach ($group->translations as $key => $translation)
                <tr>
                    <td>
                        {{ $group->key }}
                    </td>
                    <td>
                        {{ $translation->key }}
                    </td>
                    @foreach ($translation->lines as $key => $line)
                        <td>{{ $line->line }}</td>
                    @endforeach
                </tr>
            @endforeach
        @endforeach
    </table>
</div>

@stop
