<div class="row">
    @if (count($parameters) > 0)
        @foreach ($parameters as $key => $parameter)

            <div class="row clear">

                    {{-- text parameters --}}
                    @if ($parameter->type == 'text')
                        <div class="parent col-md-12">
                            @if ($parameter->multilingual == 1)
                                @foreach ($langs as $key => $onelang)
                                    @php
                                        $value = '';
                                        if ($parameter->pramProduct($product->id)->first()) {
                                            $value = $parameter->pramProduct($product->id)->first()->transByLang($onelang->id)->first();
                                        }
                                    @endphp
                                    <div class="child col">
                                        <div class="form-group">
                                            <label for="">{{ $parameter->translation->name }} [{{ $onelang->lang }}]</label>
                                            <input type="text" name="propText[{{ $parameter->id }}][{{ $onelang->id }}]" value="{{ @$value->value }}" class="form-control">
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                @php
                                    $value = '';
                                    if ($parameter->pramProduct($product->id)->first()) {
                                        $value = $parameter->pramProduct($product->id)->first()->transByLang(1)->first();
                                    }
                                @endphp
                                <div class="child col">
                                    <div class="form-group">
                                        <label for="">{{ $parameter->translation->name }}</label>
                                        <input type="text" name="propText[{{ $parameter->id }}][1]" value="{{ @$value->value }}" class="form-control">
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- textarea parameters --}}
                    @if ($parameter->type == 'textarea')
                        <div class="parent col-md-12">
                            @if ($parameter->multilingual == 1)
                                @foreach ($langs as $key => $onelang)
                                    @php
                                        $value = '';
                                        if ($parameter->pramProduct($product->id)->first()) {
                                            $value = $parameter->pramProduct($product->id)->first()->transByLang($onelang->id)->first();
                                        }
                                    @endphp
                                    <div class="child col">
                                        <div class="form-group">
                                            <label for="">{{ $parameter->translation->name }} [{{ $onelang->lang }}]</label>
                                            <textarea type="text" name="propText[{{ $parameter->id }}][{{ $onelang->id }}]" class="form-control">{{ @$value->value }}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                @php
                                    $value = '';
                                    if ($parameter->pramProduct($product->id)->first()) {
                                        $value = $parameter->pramProduct($product->id)->first()->transByLang(1)->first();
                                    }
                                @endphp
                                <div class="child col">
                                    <div class="form-group">
                                        <label for="">{{ $parameter->translation->name }}</label>
                                        <textarea type="text" name="propText[{{ $parameter->id }}][1]" class="form-control">{{ @$value->value }}</textarea>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- select parameters --}}
                    @if ($parameter->type == 'select')
                        <div class="parent col-md-12">
                            <div class="child col">
                                <div class="form-group">
                                    <label for="">{{ $parameter->translation->name }}</label>
                                    <select class="form-control" name="prop[{{ $parameter->id }}]">
                                        <option value="0">---</option>
                                        @foreach ($parameter->parameterValues as $key => $value)
                                            <option value="{{ $value->id }}" {{ getParamProdValue($parameter->id, $product->id) ==  $value->id ? 'selected' : ''  }}>{{ $value->translation->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- checkbox parameters --}}
                    @if ($parameter->type == 'checkbox')

                        <div class="parent col-md-12 checkbox-area">
                            <label>{{ $parameter->translation->name }}: </label>
                            @php
                                $checkedItems = getParameterChechedItems($parameter->id, $product->id);
                            @endphp
                            @foreach ($parameter->parameterValues as $key => $value)
                            <div class="child col">
                                <div class="form-group">
                                    <label for="">
                                        <input type="checkbox" name="prop[{{ $parameter->id }}][{{$value->id}}]" {{ in_array($value->id, $checkedItems) ? 'checked' : '' }}>
                                        <span>{{ str_limit($value->translation->name, 5) }}</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach

                        </div>
                    @endif

            </div>


        @endforeach
    @endif
    {{-- @if (!empty($poperties))
    <div class="col-md-12">
        <div class="list-content part full-part">
            @foreach ($poperties as $key => $property)
            <div class="form-group">
                @if (!is_null($category->property))
                @if ($category->property->property_id != $property->id)
                <label>
                <a href="{{ route('properties.edit', [$property->id]) }}" target="_blank"><i class="fa fa-eye"></i></a>
                {{ $property->translation()->first()->name }}
                ({{ $property->translation()->first()->unit }})
                <b>{{ $property->group->translation()->first()->name }}</b>
                </label>
                @if ($property->type == 'select')
                <select name="prop[{{ $property->id }}]" class="form-control">
                    <option value="0">--</option>
                    @if (!empty($property->multidata)))
                    @foreach ($property->multidata as $key => $multidata)
                     $value = getMultiDataList($multidata->id, 1); ?>
                    <option value="{{ $value->property_multidata_id }}" {{ getPropertiesData(Request::segment(3), $property->id) ==  $value->property_multidata_id ? 'selected' : ''  }}>{{ $value->name}} {{ $property->translationByLanguage($lang->id)->first()->unit }}</option>
                    @endforeach
                    @endif
                </select>
                @elseif ($property->type == 'text')
                <div class="row">
                    @foreach ($langs as $key => $oneLang)
                    <div class="col-md-6">
                        [{{ $oneLang->lang }}]<input type="text" name="propText[{{ $property->id }}][{{ $oneLang->id }}]" value="{{ getPropertiesDataByLang(Request::segment(3), $property->id, $oneLang->id) }}" class="form-control">
                    </div>
                    @endforeach
                </div>
                @elseif ($property->type == 'textarea')
                <div class="row">
                    @foreach ($langs as $key => $oneLang)
                    <div class="col-md-6">
                        [{{ $oneLang->lang }}]<textarea name="propText[{{ $property->id }}][{{ $oneLang->id }}]" class="form-control">{{ getPropertiesData(Request::segment(3), $property->id, $oneLang->id) ?? $property->translation->first()->value }}</textarea>
                    </div>
                    @endforeach
                </div>
                @elseif ($property->type == 'number')
                <input type="number" min="1" step="any" name="prop[{{ $property->id }}]" value="{{ getPropertiesData(Request::segment(3), $property->id, 0) ?? $property->translation->first()->value }}" class="form-control">
                @elseif ($property->type == 'checkbox')
                @if (!empty($property->multidata))
                 $chekboxArray =  getPropertiesData(Request::segment(3), $property->id, 0); ?>
                <ul>
                    @foreach ($property->multidata as $key => $multidata)

                        $value = getMultiDataList($multidata->id, 1);
                        if (is_array(getPropertiesData(Request::segment(3), $property->id))) {
                            $selected = getPropertiesData(Request::segment(3), $property->id);
                        }else{
                            $selected = [];
                        }
                        ?>
                    <ol>
                        @if (is_array($chekboxArray))
                        <label>
                        <input class="checkbox" type="checkbox" name="prop[{{ $property->id }}][]" value="{{ $value->property_multidata_id }}"
                        {{ in_array($value->property_multidata_id, $selected) ? 'checked' : $selected[0]  }} >
                        <span>{{ $value->name }}</span>
                        </label>
                        @else
                        <label>
                        <input class="checkbox" type="checkbox" name="prop[{{ $property->id }}][]" value="{{ $value->property_multidata_id }}"
                        {{ in_array($value->property_multidata_id, $selected) ? 'checked' : $value->id  }} >
                        <span>{{ $value->name }}</span>
                        </label>
                        @endif
                    </ol>
                    @endforeach
                </ul>
                @endif
                @endif
                @endif
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif --}}
</div>
