@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('promotions.index') }}">Promotions</a></li>
        <li class="breadcrumb-item active" aria-current="promotion">Edit promotion</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Edit promotion </h3>
    @include('admin::admin.list-elements', [
        'actions' => [
                "Add new" => route('promotions.create'),
            ]
    ])
</div>
@include('admin::admin.alerts')
<div class="list-content">
    <div class="card">
        <div class="card-block">
            <form class="form-reg" role="form" method="POST" action="{{ route('promotions.update', $promotion->id) }}" id="add-form" enctype="multipart/form-data">
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
                        <div class="tab-content {{ $loop->first ? ' active-content' : '' }}" id={{ $lang->lang }}>
                            @foreach($promotion->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <div class="form-group">
                                    <label for="name-{{ $lang->lang }}">Title [{{ $lang->lang }}]</label>
                                    <input type="text" name="title_{{ $lang->lang }}" class="form-control" value="{{ $translation->name }}">
                                </div>
                                @endif
                            @endforeach
                            @foreach($promotion->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <div class="form-group">
                                    <label for="descr-{{ $lang->lang }}">Description [{{ $lang->lang }}]</label>
                                    <textarea name="description_{{ $lang->lang }}" class="form-control">{{ $translation->description }} </textarea>
                                </div>
                                @endif
                            @endforeach
                            @foreach($promotion->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <div class="form-group">
                                    <label for="body_{{ $lang->lang }}">Body [{{ $lang->lang }}]</label>
                                    <textarea  name="body_{{ $lang->lang }}" id="body-{{ $lang->lang }}" class="form-control">{{ $translation->body }}</textarea>
                                </div>
                                <script>
                                    CKEDITOR.replace('body-{{ $lang->lang }}', {
                                        language: '{{$lang}}',
                                        height: '200px'
                                    });
                                </script>
                                @endif
                            @endforeach
                            @foreach($promotion->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <div class="form-group">
                                    <label for="meta_title_{{ $lang->lang }}">Button Text [{{ $lang->lang }}]</label>
                                    <input type="text" name="btn_text_{{ $lang->lang }}" class="form-control" value="{{ $translation->btn_text }}">
                                </div>
                                @endif
                            @endforeach
                            <hr>
                            @foreach($promotion->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <div class="form-group">
                                    <label for="meta_title_{{ $lang->lang }}">Seo Title [{{ $lang->lang }}]</label>
                                    <input type="text" name="seo_title_{{ $lang->lang }}" class="form-control" value="{{ $translation->seo_title }}">
                                </div>
                                @endif
                            @endforeach
                            @foreach($promotion->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <div class="form-group">
                                    <label for="seo_descr_{{ $lang->lang }}">Seo Description [{{ $lang->lang }}]</label>
                                    <input type="text" name="seo_descr_{{ $lang->lang }}" class="form-control" value="{{ $translation->seo_description }}">
                                </div>
                                @endif
                            @endforeach
                            @foreach($promotion->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <div class="form-group">
                                    <label for="seo_keywords_{{ $lang->lang }}">Seo Keywords [{{ $lang->lang }}]</label>
                                    <input type="text" name="seo_keywords_{{ $lang->lang }}" class="form-control" value="{{ $translation->seo_keywords }}">
                                </div>
                                @endif
                            @endforeach
                            <hr>
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    @foreach($promotion->translations as $translation)
                                    @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                        @if ($translation->banner)
                                            <img src="{{ asset('/images/promotions/'. $translation->banner ) }}" style="height: 100px;">
                                            <input type="hidden" name="old_image_{{ $lang->lang }}" value="{{ $translation->banner }}"/>
                                        @else
                                            <img src="{{ asset('/admin/img/noimage.jpg') }}" style="height: 100px;">
                                        @endif
                                    @endif
                                    @endforeach

                                    <label for="img-{{ $lang->lang }}">Banner desktop [{{ $lang->lang }}]</label>
                                    <input type="file" name="image_{{ $lang->lang }}" id="img-{{ $lang->lang }}"/>
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-6">
                                @foreach($promotion->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                    @if ($translation->banner_mob)
                                        <img src="{{ asset('/images/promotions/'. $translation->banner_mob ) }}" style="height: 100px;">
                                        <input type="hidden" name="old_image_mob_{{ $lang->lang }}" value="{{ $translation->banner_mob }}"/>
                                    @else
                                        <img src="{{ asset('/admin/img/noimage.jpg') }}" style="height: 100px;">
                                    @endif
                                @endif
                                @endforeach

                                <label for="img_mob-{{ $lang->lang }}">Banner mobile [{{ $lang->lang }}]</label>
                                <input type="file" name="image_mob_{{ $lang->lang }}" id="img_mob-{{ $lang->lang }}"/>
                            </div> --}}
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>
                                <input class="checkbox" type="checkbox" name="homewear" {{ $promotion->homewear == 1 ? 'checked' : ''}}>
                                <span>Homewear</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                <input class="checkbox" type="checkbox" name="bijoux" {{ $promotion->bijoux == 1 ? 'checked' : ''}}>
                                <span>Bijoux</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="">Discount %</label>
                            <input type="text" name="discount" value="{{ $promotion->discount }}" class="form-control">
                        </div>

                        <div class="form-group">
                            <div class="form-group">
                                <label for="img">Promotion Banner</label>
                                <input type="file" name="img" id="img"/>
                                <br>
                                @if ($promotion->img)
                                    <img src="{{ asset('images/promotions/'. $promotion->img ) }}" style="width: 60%;">
                                    <input type="hidden" name="img_old" value="{{ $promotion->img }}"/>
                                @else
                                    <img src="{{ asset('/admin/img/noimage.jpg') }}" style="width: 60%;">
                                @endif
                                <hr>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-group">
                                <label for="img">Promotion Banner Mobile</label>
                                <input type="file" name="img_mobile" id="img"/>
                                <br>
                                @if ($promotion->img_mobile)
                                    <img src="{{ asset('images/promotions/'. $promotion->img_mobile ) }}" style="width: 60%;">
                                    <input type="hidden" name="img_old_mobile" value="{{ $promotion->img_mobile }}"/>
                                @else
                                    <img src="{{ asset('/admin/img/noimage.jpg') }}" style="width: 60%;">
                                @endif
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group text-center">
                    <input type="submit" value="{{trans('variables.save_it')}}" class="btn btn-primary">
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
