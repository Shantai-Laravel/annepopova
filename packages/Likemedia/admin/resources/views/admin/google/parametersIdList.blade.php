@extends('admin::admin.google.app')
@section('content')

<div class="wrapp">
    <div class="row">
        <div class="col-md-4">
            <h5 class="text-center">Product List:</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th> Code </th>
                        <th> Title </th>
                        <th> ID </th>
                    </tr>
                </thead>
                @foreach ($products as $key => $product)
                <tr>
                    <td>
                        {{ $product->code }}
                    </td>
                    <td>
                        {{ $product->translation->name }}
                    </td>
                    <td>
                        {{ $product->id }}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="col-md-4">
            <h5 class="text-center">Parameter List:</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th> Parameter Value </th>
                        <th> ID </th>
                    </tr>
                </thead>
                @foreach ($parameters as $key => $parameter)
                @if ($parameter->key !== 'size')
                <tr>
                    <td colspan="2" class="text-center">
                        <b>{{ $parameter->key }}</b>
                    </td>
                </tr>
                @if ($parameter->parameterValues->count() > 0)
                    @foreach ($parameter->parameterValues as $key => $value)
                        <tr>
                            <td>
                                <small>{{ $value->translation->name }}</small>
                            </td>
                            <td>
                                <small>{{ $value->id }}</small>
                            </td>
                        </tr>
                    @endforeach
                @else

                @endif
                @endif

                @endforeach
            </table>
        </div>
        <div class="col-md-4">
            <h5 class="text-center">Promotion List:</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th> Title </th>
                        <th> Discount </th>
                        <th> ID </th>
                    </tr>
                </thead>
                @foreach ($promotions as $key => $promotion)
                <tr>
                    <td>
                        {{ $promotion->translation->name }}
                    </td>
                    <td>
                        {{ $promotion->disocunt ?? 0 }}
                    </td>
                    <td>
                        {{ $promotion->id }}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

@stop
