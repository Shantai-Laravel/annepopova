<!-- Modal -->
<div class="modal fade" id="gallery-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
        <h5 class="modal-title" id="exampleModalLabel">Product Gallery</h5>
      </div>

      <div class="modal-body">

          <div class="row">
              <div class="col-md-4">
                  Upload  images
                  <div class="form-group">
                      <form action="{{ route('products.images.add', ['product' => $product->id]) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <label for="upload">choice images</label>
                        <input type="file" id="upload_file" name="images[]" onchange="preview_image();" multiple/>
                        <div id="image_preview"></div>
                        <hr>
                        <input type='submit' class="btn btn-primary" value='Salveaza'>
                      </form>
                  </div>
              </div>
              <div class="col-md-8">
                  Gallery
                  <form action="{{ route('products.images.edit', ['product' => $product->id]) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}

                      @if (!empty($images))
                          @foreach ($images as $key => $image)
                              <div class="row image-list">
                                  <div class="col-md-5">
                                      <img src="/images/products/og/{{ $image->src }}" alt="" class="{{ $image->main == 1 ? 'main-image' : '' }}">
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
                                      <a href="#" class="main-btn" data-id="{{ $image->id }}"><i class="fa fa-check"></i></a>
                                      <a href="#" class="delete-btn" data-id="{{ $image->id }}"><i class="fa fa-trash"></i></a>
                                  </div>
                              </div>
                          @endforeach
                      @endif
                      <input type="submit" class="btn btn-primary" value="Salveaza">
                  </form>
              </div>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<script>
    function preview_image(){
        var total_file=document.getElementById("upload_file").files.length;
        for(var i=0; i < total_file; i++){
            $('#image_preview').append(
                "<div class='row append'><div class='col-md-12'><img src='"+URL.createObjectURL(event.target.files[i])+"'alt=''></div><div class='col-md-12'>@foreach ($langs as $key => $lang)<label for=''>Alt[{{ $lang->lang }}]</label><input type='text' name='alt[{{ $lang->id }}][]'><label for=''>Title[{{ $lang->lang }}]</label><input type='text' name='title[{{ $lang->id }}][]'>@endforeach </div><hr><br>"
            );
        }
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
        });
    });
</script>
