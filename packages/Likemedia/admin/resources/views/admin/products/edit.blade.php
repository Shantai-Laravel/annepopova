@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')


<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        @if (Request::get('set'))
            <li class="breadcrumb-item"><a href="{{ url('/back/products/sets/'.Request::get('set')) }}">Set</a></li>
        @endif
        <li class="breadcrumb-item"><a href="{{ route('products.category' , ['category' => Request::get('category')]) }}">Produse</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit products</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Edit products
        @if (!is_null($category))
            @if (!is_null($category->translation)))
                [ {{ $category->translation->name }} ]
            @endif
        @endif
    </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('products.create', ['category' => Request::get('category')]),
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">
    <form class="form-reg" method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
        {{ csrf_field() }} {{ method_field('PATCH') }}
        <input type="hidden" id="category_id" name="categories_id" value="">

        <div class="row">
            <div class="col-md-4">
                <ul>
                    <li>
                        <label>Categorie</label>
                        <select name="category_id">
                        @foreach($categories as $categoryItem)
                            <option {{ $categoryItem->id == $product->category_id ? 'selected' : '' }} value="{{ $categoryItem->id }}">{{ $categoryItem->translation->name }}</option>
                        @endforeach
                        </select>

                        @if ($product->category_id > 0)
                            <a class="btn btn-primary btn-sm" href="{{ url('/back/products/category/'.$product->category_id) }}"><< Back to category</a>
                        @endif
                    </li>
                </ul>
            </div>

            <div class="col-md-4">
                <ul>
                    <li>
                        <label>Promotion</label>
                        <select name="prommotion_id">
                        <option value="0">---</option>
                        @foreach($promotions as $promotion)
                        <option value="{{ $promotion->id }}" {{ $product->promotion_id == $promotion->id ? 'selected' : '' }}>{{ $promotion->translation($lang->id)->name }}</option>
                        @endforeach
                        </select>
                        @if ($product->promotion_id)
                            <a class="btn btn-primary btn-sm" href="{{ url('/back/promotions') }}"><< Back to promotion</a>
                        @endif
                    </li>
                </ul>
            </div>

            <div class="col-md-4">
                <ul>

                    <li class="form-group">
                        <label>Colectii</label>
                        <div class="dropdown show">
                          <button type="button" class="btn btn-default dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100%;">
                            Alege colectie
                          </button>
                          <div class="dropdown-menu padding10" aria-labelledby="dropdownMenuLink" style="width: 100%;">
                              <small class="text-danger text-center">Bifand una sau mai multe colectii, vor fi create seturi care vor contine produsul dat.</small>
                              <div class="form-group">
                                  @foreach($collections as $collection)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>
                                              <input class="checkbox" type="checkbox" name="collection_id[]" value="{{ $collection->id }}">
                                              <span>{{ $collection->translation->name }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                              </div>
                          </div>
                        </div>
                        @if ($product->set_id)
                            <a class="btn btn-primary btn-sm" href="{{ url('/back/products/sets/'.$product->set_id) }}"><< Back to set</a>
                        @endif
                    </li>
                </ul>
            </div>
           <div class="col-md-12">
                <ul>
                    <li class="form-group">
                        <label>Seturi [imagini - {{ count($product->setImages()->get()) }}]</label>
                        <div class="dropdown show">
                          <button type="button" class="btn btn-default dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100%;">
                            Alege set
                        </button>
                          <div class="dropdown-menu padding10" aria-labelledby="dropdownMenuLink" style="width: 100%;">
                              <div class="form-group">

                                @foreach($sets as $set)
                                    <div class="row">
                                      <div class="col-md-4">
                                          <label>
                                              <input class="checkbox" type="checkbox" name="set_id[]" value="{{ $set->id }}" {{ !is_null($set->setProduct($product->id)->first()) ? 'checked' : '' }}>
                                              <span>{{ $set->translation()->first()->name }}</span>
                                          </label>
                                      </div>
                                      <div class="col-md-4">
                                          <input type="file" name="imagesSets[{{ $set->id }}]" value="">
                                      </div>
                                      <div class="col-md-4">
                                          @if (!is_null($product->setImage($set->id)->first()))
                                              <img style="height: 40px; width: auto;" src="/images/products/sm/{{ $product->setImage($set->id)->first()->image }}" alt="">
                                          @endif
                                      </div>
                                  </div>
                             @endforeach
                              </div>
                          </div>
                        </div>
                        @if ($product->set_id)
                            <a class="btn btn-primary btn-sm" href="{{ url('/back/products/sets/'.$product->set_id) }}"><< Back to set</a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
        @if (!empty($langs))
        <div class="tab-area" style="margin-top: 25px;">
            <ul class="nav nav-tabs nav-tabs-bordered">
                @if (!empty($langs))
                @foreach ($langs as $key => $lang)
                <li class="nav-item">
                    <a href="#{{ $lang->lang }}" class="nav-link  {{ $key == 0 ? ' open active' : '' }}"
                        data-target="#{{ $lang->lang }}">{{ $lang->lang }}</a>
                </li>
                @endforeach
                @endif
            </ul>
        </div>
        @foreach ($langs as $lang)
        <div class="tab-content {{ $loop->first ? ' active-content' : '' }}" id={{ $lang->
            lang }}>
            <div class="part left-part">
                <ul style="padding: 25px 0;">
                    <li>
                        <label>{{trans('variables.title_table')}} [{{ $lang->lang }}]</label>
                        <input type="text" name="name_{{ $lang->lang }}" class="name" data-lang="{{ $lang->lang }}"
                        @foreach($product->translations as $translation)
                        @if ($translation->lang_id == $lang->id)
                        value="{{ $translation->name }}"
                        @endif
                        @endforeach
                        >
                    </li>
                    <li>
                        <label for="">{{trans('variables.description')}} [{{ $lang->lang }}]</label>
                        <textarea name="description_{{ $lang->lang }}" id="description-{{ $lang->lang }}"
                            data-type="ckeditor">
                                         @foreach($product->translations as $translation)
                                            @if ($translation->lang_id == $lang->id)
                                                {!! $translation->description !!}
                                            @endif
                                        @endforeach
                                    </textarea>
                        <script>
                            CKEDITOR.replace('description-{{ $lang->lang }}', {
                                language: '{{$lang->lang}}',
                            });
                        </script>
                    </li>
                    <li>
                        <label for="">{{trans('variables.body')}} [{{ $lang->lang }}]</label>
                        <textarea name="body_{{ $lang->lang }}" id="body-{{ $lang->lang }}"
                            data-type="ckeditor">
                                         @foreach($product->translations as $translation)
                                            @if ($translation->lang_id == $lang->id)
                                                {!! $translation->body !!}
                                            @endif
                                        @endforeach
                                    </textarea>
                        <script>
                            CKEDITOR.replace('body-{{ $lang->lang }}', {
                                language: '{{$lang->lang}}',
                            });
                        </script>
                    </li>
                </ul>
            </div>

            <div class="part right-part">
                <ul>
                    <h6>Seo texts</h6>
                    <li>
                        <label>{{trans('variables.meta_title_page')}} [{{ $lang->lang }}]</label>
                        <input type="text" name="meta_title_{{ $lang->lang }}"
                        @foreach($product->translations as $translation)
                        @if ($translation->lang_id == $lang->id)
                        value="{{ $translation->seo_title }}"
                        @endif
                        @endforeach
                        >
                    </li>
                    <li>
                        <label>{{trans('variables.meta_keywords_page')}} [{{ $lang->lang }}]</label>
                        <input type="text" name="meta_keywords_{{ $lang->lang }}"
                        @foreach($product->translations as $translation)
                        @if ($translation->lang_id == $lang->id)
                        value="{{ $translation->seo_keywords }}"
                        @endif
                        @endforeach
                        >
                    </li>
                    <li>
                        <label>{{trans('variables.meta_description_page')}} [{{ $lang->lang }}]</label>
                        <input type="text" name="meta_description_{{ $lang->lang }}"
                        @foreach($product->translations as $translation)
                        @if ($translation->lang_id == $lang->id)
                        value="{{ $translation->seo_description }}"
                        @endif
                        @endforeach
                        >
                    </li>
                </ul>
            </div>
        </div>
        @endforeach
        @endif

        <div class="part left-part">
            {{-- @include('admin::admin.products.parameters', ['category' => $product->category]) --}}
            <ul>
                <li>
                    <label>Alias</label>
                    <input type="text" name="alias" value="{{ $product->alias }}">
                </li>
                <li>
                    <label>Code</label>
                    <input type="text" name="code" value="{{ $product->code }}">
                </li>
                <li>
                    <label>Stock</label>
                    <input type="number" name="stock" value="{{ $product->stock }}" step="any">
                </li>
                {{-- <li>
                    <label>Price  <small>with discount - {{ $product->actual_price }} euro</small></label>
                    <input type="number" name="price" value="{{ $product->price }}" step="any">
                </li> --}}
                <li>
                    <label>Disount</label>
                    <input type="number" name="discount" value="{{ $product->discount }}" step="any">
                </li>
                <li>
                    {{-- <div class="col-md-6">
                        <label>Video</label>
                        <input type="hidden" name="video_old" value="{{ $product->video }}">
                        <input type="file" name="video" value="">
                    </div>
                    <div class="col-md-6">
                        @if ($product->video)
                            <video src="/videos/{{ $product->video }}" type='video/mp4' style="height: 300px;" controls="controls"></video>
                        @endif
                    </div> --}}
                </li>
                <li>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="hit" {{$product->hit == 1 ? 'checked' : ''}}>
                            <span>Top</span>
                        </label>
                    </div>
                </li>

                <li>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="recomended" {{$product->recomended == 1 ? 'checked' : ''}}>
                            <span>Recomended</span>
                        </label>
                    </div>
                </li>
                <li>
                    <div class="row">
                        <hr>
                        {{-- <div class="col-md-4">
                            Upload  images
                            <div class="form-group">
                                  <label for="upload">choice images</label>
                                  <input type="file" id="upload_file" name="images[]" onchange="preview_image();" multiple/>
                                  <div id="image_preview"></div>
                                  <hr>
                            </div>
                        </div> --}}


                        {{-- <div class="col-md-8">
                            Gallery
                            @if (!empty($images))
                                @foreach ($images as $key => $image)
                                    <div class="row image-list">
                                        <div class="col-md-5 text-center">
                                            @if ($image->main == 1)
                                                <small><b> <i class="fa fa-check"></i> Main</b></small>
                                            @elseif ($image->first == 1)
                                                <small><b><i class="fa fa-edit"></i> First</b></small>
                                            @endif
                                            <img src="{{ asset('/images/products/og/'.$image->src) }}" alt="" class="{{ $image->main == 1 ? 'main-image' : '' }} {{ $image->first == 1 ? 'main-image' : '' }}" style="height:200px; width: auto;">
                                        </div>
                                        <div class="col-md-6">
                                            @foreach ($langs as $key => $lang)
                                                <div class="form-group row">
                                                   <div class="col-md-4 text-right">
                                                        <label for="">Alt [{{ $lang->lang }}]</label>
                                                   </div>
                                                   <div class="col-md-8">
                                                       <input type="text" name="alt[{{ $image->id }}][{{ $lang->id }}]" class="form-control" value="{{ !is_null($image->translationByLanguage($lang->id)->first()) ? $image->translationByLanguage($lang->id)->first()->alt : '' }}">
                                                   </div>
                                                </div>
                                                <div class="form-group row">
                                                   <div class="col-md-4 text-right">
                                                        <label for="">Title [{{ $lang->lang }}]</label>
                                                   </div>
                                                   <div class="col-md-8">
                                                       <input type="text" name="title[{{ $image->id }}][{{ $lang->id }}]" class="form-control" value="{{ !is_null($image->translationByLanguage($lang->id)->first()) ?  $image->translationByLanguage($lang->id)->first()->title : '' }}">
                                                   </div>
                                               </div><br>
                                            @endforeach
                                        </div>

                                        <div class="col-md-1">
                                            @if (($image->main == 0 ))
                                                <a href="#" class="main-btn" data-id="{{ $image->id }}"><i class="fa fa-check"></i> </a>
                                            @endif

                                            @if (($image->first !== 1))
                                                <a href="{{ url('/back/products/gallery/first/'.$image->id ) }}"><i class="fa fa-edit"></i> </a>
                                            @endif

                                            <a href="#" class="delete-btn" data-id="{{ $image->id }}"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div> --}}
                    </div>
                    <div class="row">
                        {{-- @include('admin::admin.products.subproductsImages') --}}
                    </div>
                </li>
                {{-- @include('admin::admin.products.subproducts') --}}
            </ul>
        </div>

        {{-- <div class="part right-part">
            <hr>
            <h6>Similar products</h6>
            <li>
              @php
                  $property = 0;
              @endphp
              @include('admin::admin.products.editCategoriesTree')
            </li>
        </div> --}}

        <div class="part full-part">
            <li>
                <input type="submit" value="{{trans('variables.save_it')}}">
            </li>
        </div>
    </form>


    {{-- gallery modal window --}}
    {{-- @include('admin::admin.products.gallery') --}}

</div>

@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
    <script>
        $('button.tag').click(function(e) {
            e.preventDefault();

            $input = $(this).siblings().last().clone().val('');
            $(this).parent().append($input);
        });

    </script>

    <script>
    function preview_image(){
        var total_file=document.getElementById("upload_file").files.length;
        for(var i=0; i < total_file; i++){
            $('#image_preview').append(
                "<div class='row append'><div class='col-md-12'><img src='"+URL.createObjectURL(event.target.files[i])+"'alt=''></div><div class='col-md-12'>@foreach ($langs as $key => $lang)<label for=''>Alt[{{ $lang->lang }}]</label><input type='text' name='alt_[{{ $lang->id }}][]'><label for=''>Title[{{ $lang->lang }}]</label><input type='text' name='title_[{{ $lang->id }}][]'>@endforeach </div><hr><br>"
            );
        }
    }

    function preview_image_one(el){
        // var total_file=document.getElementByClassName("upload_file").files.length;
        // for(var i=0; i < total_file; i++){
        // console.log($(el).next());
            $(el).next('.image_preview').append(
                "<img src='"+URL.createObjectURL(event.target.files[0])+">"
            );
        // }
    }

    $().ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            }
        });

        $('.main-btn').on('click', function(){
            $id = $(this).attr('data-id');
            $productId = '{{ $product->id }}';

            $.ajax({
                type: "POST",
                url: '/back/products/gallery/main',
                data: {
                    id: $id,
                    productId: $productId,
                },
                success: function(data) {
                    if (data === 'true') {
                        location.reload();
                    }
                }
            });
        });

        $('.delete-btn').on('click', function(){
            var conf = confirm("Do you want delete this element?");

            if(conf != true)
                e.preventDefault();
            else{
                $id = $(this).attr('data-id');
                $productId = '{{ $product->id }}';
                $.ajax({
                    type: "POST",
                    url: '/back/products/gallery/delete',
                    data: {
                        id: $id,
                        productId: $productId,
                    },
                    success: function(data) {
                        if (data === 'true') {
                            location.reload();
                        }
                    }
                });
            }

        });
    });
</script>
</footer>
@stop
