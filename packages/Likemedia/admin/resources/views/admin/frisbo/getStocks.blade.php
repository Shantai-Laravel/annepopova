@extends('admin::admin.google.app')
@section('content')

<div class="wrapp">
    <div class="row">
        <div class="col-md-12">
            <h5 class="text-center">Product Stocks:</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th> Code </th>
                        <th> Title </th>
                        <th> Stock </th>
                        <th> Type </th>
                    </tr>
                </thead>
                @foreach ($products as $key => $product)
                    @if ($product->subproducts->count() > 0)
                        @foreach ($product->subproducts as $key => $subproduct)
                            <tr>
                                <td>
                                    {{ $subproduct->code }}
                                </td>
                                <td>
                                    <small>{{ $product->translation->name }}</small>
                                    {{ $subproduct->parameterValue->translation->name }}
                                </td>
                                <td>
                                    {{ $subproduct->warehouseFrisbo->stock }}
                                </td>
                                <td><small>Subproduct</small></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>
                                {{ $product->code }}
                            </td>
                            <td>
                                <small>{{ $product->translation->name }}</small>
                            </td>
                            <td>
                                {{$product->warehouseFrisbo ? $product->warehouseFrisbo->stock : 0 }}
                            </td>
                            <td><small>Product</small></td>
                        </tr>
                    @endif

                @endforeach
            </table>
        </div>
    </div>
</div>

@stop
