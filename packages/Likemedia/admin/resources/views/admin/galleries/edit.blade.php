@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('galleries.index') }}">Galleries</a></li>
        <li class="breadcrumb-item active" aria-current="gallery">Edit gallery</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Editarea gallery </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('galleries.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">
    <form class="form-reg" role="form" method="POST" action="{{ route('galleries.update', $gallery->id) }}" id="add-form" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
        <div class="tab-content active-content">
            <div class="part full-part">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 text-center">
                        <div class="form-group">
                            <label for="alias">Alias (Short Code)</label>
                            <input type="text" name="alias" class="form-control" id="alias" value="{{ $gallery->alias }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        Upload  Images/Videos Desktop
                        <div class="form-group">
                            <input type="file" id="upload_file" name="images[]" multiple/><hr>
                        </div>
                        <div>
                            @if (!empty($imagesDesktop))
                                @foreach ($imagesDesktop as $key => $image)
                                    <div class="row">
                                        @if ($image->type == 'image')
                                            <div class="col-md-11">
                                                <img src="{{ $image->src }}" height="350px" class="{{ $image->main == 1 ? 'main-image' : '' }}">
                                            </div>
                                            <div class="col-md-1">
                                                <a href="#" class="delete-btn" data-id="{{ $image->id }}"><i class="fa fa-trash"></i></a>
                                            </div>
                                        @else
                                            <div class="col-md-11">
                                                <video src="{{ $image->src }}"  poster="posterimage.jpg" height="250px" autostart="false" controls></video>
                                                <img src="{{ $image->src }}" alt="" class="{{ $image->main == 1 ? 'main-image' : '' }}">
                                            </div>
                                            <div class="col-md-1">
                                                <a href="#" class="delete-btn" data-id="{{ $image->id }}"><i class="fa fa-trash"></i></a>
                                            </div>
                                        @endif

                                    </div> <hr>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        Upload  Images/Videos Mobile
                        <div class="form-group">
                            <input type="file" id="upload_file" name="images_mobile[]" multiple/><hr>
                        </div>
                        <div>
                            @if (!empty($imagesMobile))
                                @foreach ($imagesMobile as $key => $image)
                                    <div class="row">
                                        @if ($image->type == 'image')
                                            <div class="col-md-11">
                                                <img src="{{ $image->src }}" height="350px" class="{{ $image->main == 1 ? 'main-image' : '' }}">
                                            </div>
                                            <div class="col-md-1">
                                                <a href="#" class="delete-btn" data-id="{{ $image->id }}"><i class="fa fa-trash"></i></a>
                                            </div>
                                        @else
                                            <div class="col-md-11">
                                                <video src="{{ $image->src }}"  poster="posterimage.jpg" height="250px" autostart="false" controls="true"></video>
                                                <img src="{{ $image->src }}" alt="" class="{{ $image->main == 1 ? 'main-image' : '' }}">
                                            </div>
                                            <div class="col-md-1">
                                                <a href="#" class="delete-btn" data-id="{{ $image->id }}"><i class="fa fa-trash"></i></a>
                                            </div>
                                        @endif

                                    </div> <hr>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="submit" value="{{trans('variables.save_it')}}" class="btn btn-primary">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


<script>
    function preview_image(){
        var total_file=document.getElementById("upload_file").files.length;
        for(var i=0; i < total_file; i++){
            $('#image_preview').append(
                "<div class='row append'><div class='col-md-12'><img src='"+URL.createObjectURL(event.target.files[i])+"'alt=''></div><div class='col-md-12'>@foreach ($langs as $key => $lang)<label for=''>Text[{{ $lang->lang }}]</label><input type='text' name='text[{{ $lang->id }}][]'> <label for=''>Alt[{{ $lang->lang }}]</label><input type='text' name='alt[{{ $lang->id }}][]'><label for=''>Title[{{ $lang->lang }}]</label><input type='text' name='title[{{ $lang->id }}][]'><hr><br><br> @endforeach </div>"
            );
        }
    }

    $().ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            }
        });

        $('.delete-btn').on('click', function(){
            $id = $(this).attr('data-id');
            $galleryId = '{{ $gallery->id }}';

            $.ajax({
                type: "POST",
                url: '/back/gallery/images/delete',
                data: {
                    id: $id,
                    galleryId: $galleryId,
                },
                success: function(data) {
                    if (data === 'true') {
                        location.reload();
                    }
                }
            });
        });
    });
</script>


@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
