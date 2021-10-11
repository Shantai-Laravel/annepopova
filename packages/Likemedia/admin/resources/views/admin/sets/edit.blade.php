@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/back/product-collections') }}">Collections</a></li>
        <li class="breadcrumb-item active" aria-current="set">Edit set</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Edit setului </h3>
</div>
@include('admin::admin.alerts')
<div class="list-content">
    <div class="card">
        <div class="card-block">
            <form class="form-reg" role="form" method="POST" action="{{ route('sets.update', $set->id) }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="row">
                    <div class="col-md-8">
                        <div class="tab-area">
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                @if (!empty($langs))
                                @foreach ($langs as $lang)
                                <li class="nav-item">
                                    <a href="#{{ $lang->lang }}" class="nav-link  {{ $loop->first ? ' open active' : '' }}"
                                        data-target="#{{ $lang->lang }}">{{ $lang->lang }}</a>
                                </li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                        @if (!empty($langs))
                        @foreach ($langs as $lang)
                        <div class="tab-content {{ $loop->first ? ' active-content' : '' }}" id={{ $lang->
                            lang }}>
                            <div class="form-group">
                                @foreach($set->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <label for="name-{{ $lang->lang }}">Title [{{ $lang->lang }}]</label>
                                <input type="text" name="title_{{ $lang->lang }}" value="{{ $translation->name }}" class="form-control">
                                @endif
                                @endforeach
                            </div>
                            <div class="form-group">
                                @foreach($set->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <label for="name-{{ $lang->lang }}">Subtitle [{{ $lang->lang }}]</label>
                                <input type="text" name="subtitle_{{ $lang->lang }}" value="{{ $translation->subtitle }}" class="form-control">
                                @endif
                                @endforeach
                            </div>
                            <div class="form-group">
                                @foreach($set->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <label for="description-{{ $lang->lang }}">Description [{{ $lang->lang }}]</label>
                                <textarea name="description_{{ $lang->lang }}" id="body-{{ $lang->lang }}" class="form-control">{{ $translation->description }}</textarea>
                                <script>
                                    CKEDITOR.replace('body-{{ $lang->lang }}', {
                                        language: '{{$lang}}',
                                        height: '200px'
                                    });
                                </script>
                                @endif
                                @endforeach
                            </div>

                            <div class="form-group">
                                @foreach($set->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <label for="meta_title_{{ $lang->lang }}">Seo Title [{{ $lang->lang }}]</label>
                                <input type="text" name="seo_title_{{ $lang->lang }}" class="form-control" value="{{ $translation->seo_title }}">
                                @endif
                                @endforeach
                            </div>
                            <div class="form-group">
                                @foreach($set->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <label for="seo_descr_{{ $lang->lang }}">Seo Description [{{ $lang->lang }}]</label>
                                <input type="text" name="seo_descr_{{ $lang->lang }}" class="form-control" value="{{ $translation->seo_description }}" >
                                @endif
                                @endforeach
                            </div>

                            <div class="form-group">
                                @foreach($set->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <label for="seo_keywords_{{ $lang->lang }}">Seo Keywords [{{ $lang->lang }}]</label>
                                <input type="text" name="seo_keywords_{{ $lang->lang }}" class="form-control" value="{{ $translation->seo_keywords }}">
                                @endif
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <div class="col-md-4">

                        {{-- <div class="form-check">
                            <input type="checkbox" class="form-check-input" id=".com{{ $set->id }}" name="com" {{ $set->com ? 'checked' : '' }}>
                            <label class="form-check-label" for=".com{{ $set->id }}">.com</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id=".md{{ $set->id }}" name="md"  {{ $set->md ? 'checked' : '' }}>
                            <label class="form-check-label" for=".md{{ $set->id }}">.md</label>
                        </div> --}}

                        <div class="form-group">
                            <label>
                                <input class="checkbox" type="checkbox" name="loungewear" {{ $set->homewear == 1 ? 'checked' : ''}}>
                                <span>Homewear</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                <input class="checkbox" type="checkbox" name="jewelry" {{ $set->bijoux == 1 ? 'checked' : ''}}>
                                <span>Bijoux</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                <input  class="checkbox" type="checkbox" name="active" {{ $set->active ? 'checked' : '' }}>
                                <span>Active</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input  class="checkbox" type="checkbox" name="on_home" {{ $set->on_home ? 'checked' : '' }}>
                                <span>On homepage</span>
                            </label>
                        </div><hr>
                        <div class="form-group">
                            <label for="discount">Collection</label>
                            <select name="collection_id" class="form-control" required>
                                @if ($collections->count() > 0)
                                    @foreach ($collections as $key => $collection)
                                        <option value="{{ $collection->id }}" {{ $set->collection_id == $collection->id ? 'selected' : '' }}>{{ $collection->translation($lang->id)->first()->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="code">Code </label>
                            <input type="text" name="code" class="form-control" value="{{ $set->code }}"/>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="discount">Material</label>
                            @php $material = GetParameterValues('material'); @endphp
                            @if (!is_null($material))
                            <select name="material" class="form-control">
                                <option value="0">---</option>
                                @if ($material->parameterValues()->count() > 0)
                                    @foreach ($material->parameterValues as $key => $materialValue)
                                        <option value="{{ $materialValue->id }}" {{ $materialValue->id == $set->material_id ? 'selected' : '' }}>{{ $materialValue->translation->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="discount">Color</label>
                            @php $color = GetParameterValues('color'); @endphp
                            @if (!is_null($color))
                            <select name="color" class="form-control">
                                <option value="0">---</option>
                                @if ($color->parameterValues()->count() > 0)
                                @foreach ($color->parameterValues as $key => $colorValue)
                                <option value="{{ $colorValue->id }}" {{ $colorValue->id == $set->color_id ? 'selected' : '' }}>{{ $colorValue->translation->name }}</option>
                                @endforeach
                                @endif
                            </select>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="discount">Room</label>
                            @php $room = GetParameterValues('room'); @endphp
                            @if (!is_null($room))
                            <select name="room" class="form-control">
                                <option value="0">---</option>
                                @if ($room->parameterValues()->count() > 0)
                                    @foreach ($room->parameterValues as $key => $roomValue)
                                        <option value="{{ $roomValue->id }}" {{ $roomValue->id == $set->room_id ? 'selected' : '' }}>{{ $roomValue->translation->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="discount">Employment</label>
                            @php $employment = GetParameterValues('employment'); @endphp
                            @if (!is_null($employment))
                                <select name="employment" class="form-control">
                                    <option value="0">---</option>
                                    @if ($employment->parameterValues()->count() > 0)
                                        @foreach ($employment->parameterValues as $key => $employmentValue)
                                            <option value="{{ $employmentValue->id }}" {{ $employmentValue->id == $set->employment_id ? 'selected' : '' }}>{{ $employmentValue->translation->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            @endif
                        </div>

                        <hr>
                        <div class="form-group">
                            <label for="discount">Discount %</label>
                            <input type="number" name="discount" class="form-control" value="{{ $set->discount }}"/>
                        </div>

                        @if ($set->prices()->get()->count())
                            @foreach ($set->prices()->get() as $key => $price)
                                <div class="form-group">
                                    <label for="price">
                                        Price {{ $price->currency->abbr }} {{ $key == 0 ? '[main currency]' : '' }}
                                        @if ($key !== 0)
                                            @if ($price->currency->exchange_dependable == 1)
                                                <small class="text-success">[exchange dependable]</small>
                                            @else
                                                <small class="text-danger">non exchange dependable</small>
                                            @endif
                                        @endif
                                    </label>
                                    <input type="number" name="price[{{ $price->id }}]" class="form-control" value="{{ $price->old_price }}"/>
                                </div>
                            @endforeach
                        @endif


                        <div class="form-group">
                            <div class="form-group">
                                <label for="img">Set Banner</label>
                                <input type="file" name="img" id="img"/>
                                <br>
                                @if ($set->banner_desktop)
                                    <img src="{{ asset('images/sets/'. $set->banner_desktop ) }}" style="width: 60%;">
                                    <input type="hidden" name="img_old" value="{{ $set->banner_desktop }}"/>
                                @else
                                    <img src="{{ asset('/admin/img/noimage.jpg') }}" style="width: 60%;">
                                @endif
                                <hr>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-group">
                                <label for="img">Set Banner Mobile</label>
                                <input type="file" name="img_mobile" id="img"/>
                                <br>
                                @if ($set->banner_mobile)
                                    <img src="{{ asset('images/sets/'. $set->banner_mobile ) }}" style="width: 60%;">
                                    <input type="hidden" name="img_old_mobile" value="{{ $set->banner_mobile }}"/>
                                @else
                                    <img src="{{ asset('/admin/img/noimage.jpg') }}" style="width: 60%;">
                                @endif
                                <hr>
                            </div>
                        </div>

                        <div class="form-group">
                            {{-- <label>
                            <input type="checkbox" name="exchange" {{ $set->dependable_price ? 'checked' : '' }} disabled checked>
                            <span>Exchange dependable</span>
                            </label> --}}
                        </div>
                        <hr>
                    </div>
                    <h6 class="text-center">Photo/Video Gallery</h6> <br><br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                @if ($set->galleryItems()->get())
                                @foreach ($set->galleryItems()->get() as $key => $item)
                                @if ($item->type == 'photo')
                                <div class="col-md-3 wrapp-full">
                                    <img src="/images/sets/og/{{ $item->src }}" alt="" class="full-width">
                                    <section>
                                        <a href="{{ url('back/sets/delete/gallery-item/'. $item->id) }}" class="close"><i class="fa fa-times-circle"></i> </a>
                                        @if ($item->main != 1)
                                        <a href="{{ url('back/sets/setmain/gallery-item/'. $item->id) }}" class="close"><i class="fa fa-edit"></i> </a>
                                        @endif
                                    </section>
                                </div>
                                @endif
                                @endforeach
                                @endif
                            </div>
                            <hr>
                            <div>
                                <label for="photos">Add photos here</label>
                                <input type="file" name="photos[]" value="" multiple>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <hr>
                                <div class="col-md-6">
                                    <label>Video</label>
                                    <input type="hidden" name="video_old" value="{{ $set->video }}">
                                    <input type="file" name="video" value="">
                                </div>
                                <div class="col-md-6">
                                    @if ($set->video)
                                        <video src="/videos/{{ $set->video }}" type='video/mp4' style="height: 300px;" controls="controls"></video>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                    <hr>
                    <div class="col-md-12">
                        <input type="submit" value="Save" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
