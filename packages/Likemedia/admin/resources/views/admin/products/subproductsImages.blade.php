<div class="col-md-12">
    @if (!is_null($category->propertyMain))
        <h5 class="text-center">Subproducts images</h5>
        <div class="row">
            @if (!is_null($category->propertyMain->property))
                <label class="text-center">Parametrul Main - {{ $category->propertyMain->property->translationByLanguage(1)->first()->name }}</label>
                @if (count($category->propertyMain->property->multidata))
                    @foreach ($category->propertyMain->property->multidata as $key => $pramValue)
                        <div class="col-md-4"><br>
                            <p class="text-center"><b>{{ $pramValue->translationByLanguage(1)->first()->name }}</b></p>
                            <input type="file" name="subprod_image[{{ $pramValue->id }}]" value="" onchange="preview_image_one(this);">
                            <div class="image_preview">
                                {{-- {{ dd($pramValue->subProduct($product->id, $pramValue->id)) }} --}}
                                @if (!is_null($pramValue->subProduct($product->id, $pramValue->id)))

                                @if ($pramValue->subProduct($product->id, $pramValue->id)->image()->first())
                                    <img height="40px" src="/images/subproducts/og/{{ $pramValue->subProduct($product->id, $pramValue->id)->image()->first()->src }}" alt="">
                                @else
                                    <img height="40px" src="/images/empty.png" alt="">
                                @endif
                            @endif

                            </div>
                            <hr>
                        </div>
                    @endforeach
                @endif
            @endif
        </div>
    @endif
</div>
