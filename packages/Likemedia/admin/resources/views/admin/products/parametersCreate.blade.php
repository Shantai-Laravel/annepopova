<div class="row">
    @if (count($parameters) > 0)
        @foreach ($parameters as $key => $parameter)

            <div class="row clear">

                    {{-- text parameters --}}
                    @if ($parameter->type == 'text')
                        <div class="parent col-md-12">
                            @if ($parameter->multilingual == 1)
                                @foreach ($langs as $key => $onelang)
                                    <div class="child col">
                                        <div class="form-group">
                                            <label for="">{{ $parameter->translation->name }} [{{ $onelang->lang }}]</label>
                                            <input type="text" name="propText[{{ $parameter->id }}][{{ $onelang->id }}]" value="" class="form-control">
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="child col">
                                    <div class="form-group">
                                        <label for="">{{ $parameter->translation->name }}</label>
                                        <input type="text" name="propText[{{ $parameter->id }}][1]" value="" class="form-control">
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
                                    <div class="child col">
                                        <div class="form-group">
                                            <label for="">{{ $parameter->translation->name }} [{{ $onelang->lang }}]</label>
                                            <textarea type="text" name="propText[{{ $parameter->id }}][{{ $onelang->id }}]" class="form-control"></textarea>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="child col">
                                    <div class="form-group">
                                        <label for="">{{ $parameter->translation->name }}</label>
                                        <textarea type="text" name="propText[{{ $parameter->id }}][1]" class="form-control"></textarea>
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
                                            <option value="{{ $value->id }}">{{ $value->translation->name }}</option>
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
                            @foreach ($parameter->parameterValues as $key => $value)
                            <div class="child col">
                                <div class="form-group">
                                    <label for="">
                                        <input type="checkbox" name="prop[{{ $parameter->id }}][{{$value->id}}]">
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
</div>
