@extends('admin::admin.google.app')
@section('content')

<div class="wrapp">
    <div class="row">

        <div class="col-md-12">
            <h5 class="text-center">Products/Subproducts List:</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th> Code <small>(product or subproduct)</small> </th>
                        <th> Title </th>
                        <th> Product ID </th>
                        <th> Subroduct ID </th>
                    </tr>
                </thead>
                @foreach ($products as $key => $product)
                    @if ($product->subproducts->count() > 0)
                        @foreach ($product->subproducts as $key => $subproduct)
                            <tr>
                                <td>
                                    <small>{{ $subproduct->code }}</small>
                                </td>
                                <td>
                                    <small>{{ $product->translation->name }}</small>
                                </td>
                                <td>
                                    ---
                                </td>
                                <td>
                                    <small>{{ $subproduct->id }}</small>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>
                                <small>{{ $product->code }}</small>
                            </td>
                            <td>
                                <small>{{ $product->translation->name }}</small>
                            </td>
                            <td>
                                <small>{{ $product->id }}</small>
                            </td>
                            <td>
                                ---
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>

    </div>
</div>

@stop
