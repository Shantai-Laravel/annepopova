@php
    $i = 1;
@endphp
<hr>
<h6 class="text-center">Subproducts</h6>

@if (count($product->subproducts()->get()))
    @foreach ($product->subproducts()->get() as $key => $subproduct)

    <div class="col-md-6">
        <ul>
            <li>
            <hr><hr>
                <h6>
                    <input type="checkbox" name="subproduct_active[]" value="{{ $subproduct->id }}" {{ $subproduct->active ? 'checked' : '' }}>
                    <small># {{ $key+1 }} subproduct, code -</small> {{ $subproduct->code }}
                </h6>
            </li>
            <li>
                <label for="">{{ $subproduct->parameter->translation->name }} <small>[dependable]</small> </label>
                <input type="text" disabled name="" value="{{ $subproduct->parameterValue->translation->name }}">
            </li>
            <li>
              <label for="">Price <small>[insertable]</small> </label>
              <input class="{{ $key % 2 == 0 ? 'from'.$i : 'toprice_'.$i }} copy" data-id="price_{{ $i }}" type="number" class="form-control" name="subprod[{{$subproduct->id}}][price]" value="{{intval($subproduct->price)}}">
            </li>
            <li>
              <label for="">Discount %<small>[insertable]</small> </label>
              <input class="{{ $key % 2 == 0 ? 'from'.$i : 'todiscount_'.$i }} copy" data-id="discount_{{ $i }}" type="number" class="form-control" name="subprod[{{$subproduct->id}}][discount]" value="{{intval($subproduct->discount)}}">
            </li>
            <li>
              <label for="">Stock <small>[insertable]</small> </label>
              <input class="{{ $key % 2 == 0 ? 'from'.$i : 'tostock_'.$i }} copy"  data-id="stock_{{ $i }}" type="number" class="form-control" name="subprod[{{$subproduct->id}}][stock]" value="{{intval($subproduct->stoc)}}">
            </li>
        </ul>
    </div>
    @php
        if ($key % 2 == 1) {
            $i++;
        }
    @endphp
    @endforeach
@endif

 <script>
     $('.copy').keyup(function () {
         var id = $(this).data('id');
         $('.to'+id).val($(this).val());
     })
 </script>
