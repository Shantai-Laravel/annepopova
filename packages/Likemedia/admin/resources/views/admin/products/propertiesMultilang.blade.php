<div class="row">
    @if (!empty($poperties['multilang']))
        @foreach ($poperties['multilang'] as $key => $property)
            <div class="col-md-12">
                <div class="form-group">
                    <label>
                        <a href="{{ route('properties.edit', [$property->id]) }}" target="_blank"><i class="fa fa-eye"></i></a>
                        {{ $property->translation->first()->name }}
                        ({{ $property->translation->first()->unit }})
                        [{{ $lang->lang }}]
                        <b>{{ $property->group->translationByLanguage($lang->id)->first()->name }}</b>
                    </label>

                    @if ($property->type == 'select')
                        <select name="prop[{{ $property->id }}][{{ $lang->id }}]" class="form-control">
                            @if (!empty($property->multidata)))
                                @foreach ($property->multidata as $key => $multidata)
                                    <?php $value = getMultiDataList($multidata->id, $lang->id); ?>
                                    <option value="{{ $value->value  }}" {{ getPropertiesData(Request::segment(3), $property->id, $lang->id) ==  $value->value ? 'selected' : ''  }}>{{ $value->value }} {{ $property->translationByLanguage($lang->id)->first()->unit }}</option>
                                @endforeach
                            @endif
                        </select>

                    @elseif ($property->type == 'text')

                        <input type="text" name="prop[{{ $property->id }}][{{ $lang->id }}]" value="{{ getPropertiesData(Request::segment(3), $property->id, $lang->id) ?? $property->translation->first()->value }}" class="form-control">

                    @elseif ($property->type == 'textarea')

                        <textarea name="prop[{{ $property->id }}][{{ $lang->id }}]" class="form-control">{{ getPropertiesData(Request::segment(3), $property->id, $lang->id) ?? $property->translation->first()->value }}</textarea>

                    @elseif ($property->type == 'number')

                        <input type="number" min="1" step="any" name="prop[{{ $property->id }}][{{ $lang->id }}]" value="{{  getPropertiesData(Request::segment(3), $property->id, $lang->id) ?? $property->translation->first()->value }}" class="form-control">

                    @elseif ($property->type == 'checkbox')
                        @if (!empty($property->multidata))
                            <?php $chekboxArray =  getPropertiesData(Request::segment(3), $property->id, $lang->id); ?>
                            <ul>
                            @foreach ($property->multidata as $key => $multidata)
                                <ol>
                                    @if (is_array($chekboxArray))
                                    <label>
                                        <input class="checkbox" type="checkbox" name="prop[{{ $property->id }}][{{ $lang->id }}][]" value="{{ getMultiDataList($multidata->id, $lang->id)->name }}"
                                        {{ in_array(getMultiDataList($multidata->id, $lang->id)->name, $chekboxArray) == false ? '' : 'checked'}} >

                                        <span>{{ getMultiDataList($multidata->id, $lang->id)->name }} {{ $property->translation->first()->unit }}</span>
                                    </label>
                                    @else
                                        <label>
                                            <input class="checkbox" type="checkbox" name="prop[{{ $property->id }}][{{ $lang->id }}][]" value="{{ getMultiDataList($multidata->id, $lang->id)->name }}"
                                            >

                                            <span>{{ getMultiDataList($multidata->id, $lang->id)->name }} {{ $property->translation->first()->unit }}</span>
                                        </label>
                                    @endif

                                </ol>
                            @endforeach
                            {{-- {{  dd(getPropertiesData(Request::segment(3), $property->id, $lang->id)) }} --}}
                            </ul>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>
