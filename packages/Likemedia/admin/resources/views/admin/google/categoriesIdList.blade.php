@extends('admin::admin.google.app')
@section('content')

<div class="wrapp">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th> Title </th>
                <th> ID </th>
            </tr>
        </thead>
        @foreach ($categories as $key => $category)
        <tr>
            <td>
                {{ $category->translation->name }}
            </td>
            <td>
                {{ $category->id }}
            </td>
        </tr>
        @endforeach
    </table>
</div>

@stop
