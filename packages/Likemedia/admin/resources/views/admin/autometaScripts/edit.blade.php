@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/back/autometa-scripts') }}">Scripts</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Script</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Edit Script </h3>
</div>
@include('admin::admin.alerts')
<div class="list-content">
    <div class="card">
        <div class="card-block">
            <form class="form-reg" role="form" method="POST" action="{{ route('autometa-scripts.update', $script->id) }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="row">
                    <div class="col-md-12 alert-warning"> <br>
                        @if ($script->type == 'product')
                            <input type="hidden" name="type" value="product">
                            <h6>Editing script for <b>Products</b>. </h6> <hr>
                            <h6>Shortcodes to use:</h6>
                            <p>
                                <span class="label label-primary"> {Prod_Name} </span>
                                <span class="label label-primary"> {Cat_Name} </span>
                                <span class="label label-primary"> {Params} </span>
                                <span class="label label-primary"> {Attributes} </span>
                                <span class="label label-primary"> {var1} - {var15} </span>
                                <span class="label label-primary"> {#} </span>
                            </p>
                        @elseif ($script->type == 'category')
                            <input type="hidden" name="type" value="category">
                            <h6>Creating script for <b>Categories</b>. </h6><hr>
                            <h6>Shortcodes to use:</h6>
                            <p>
                                <span class="label label-primary"> {Cat_Name} </span>
                                <span class="label label-primary"> {var1} - {var15} </span>
                            </p>
                        @elseif ($script->type == 'collection')
                            <input type="hidden" name="type" value="collection">
                            <h6>Creating script for <b>Collections</b>. </h6><hr>
                            <h6>Shortcodes to use:</h6>
                            <p>
                                <span class="label label-primary"> {Col_Name} </span>
                                <span class="label label-primary"> {var1} - {var15} </span>
                            </p>
                            <span class="label label-primary"> {#} </span>
                        @endif
                        <div class="form-group">
                            <label for="">Script Name</label>
                            <input type="text" name="name" value="{{ $script->name }}" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="container-scroll">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Vars</th>
                                @foreach ($langs as $key => $lang)
                                    <th>{{ $lang->lang }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="label label-success">{var1}</span></td>
                                @foreach ($langs as $key => $lang)
                                    <td class="input-block"> <input type="text" name="var1[{{$lang->id}}]" value="{{ $script->translationByLang($lang->id)->first()->var1 }}"/> </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><span class="label label-success">{var2}</span></td>
                                @foreach ($langs as $key => $lang)
                                    <td class="input-block"> <input type="text" name="var2[{{$lang->id}}]" value="{{ $script->translationByLang($lang->id)->first()->var2 }}"/> </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><span class="label label-success">{var3}</span></td>
                                @foreach ($langs as $key => $lang)
                                    <td class="input-block"> <input type="text" name="var3[{{$lang->id}}]" value="{{ $script->translationByLang($lang->id)->first()->var3 }}"/> </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><span class="label label-success">{var4}</span></td>
                                @foreach ($langs as $key => $lang)
                                    <td class="input-block"> <input type="text" name="var4[{{$lang->id}}]" value="{{ $script->translationByLang($lang->id)->first()->var4 }}"/> </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><span class="label label-success">{var5}</span></td>
                                @foreach ($langs as $key => $lang)
                                    <td class="input-block"> <input type="text" name="var5[{{$lang->id}}]" value="{{ $script->translationByLang($lang->id)->first()->var5 }}"/> </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><span class="label label-success">{var6}</span></td>
                                @foreach ($langs as $key => $lang)
                                    <td class="input-block"> <input type="text" name="var6[{{$lang->id}}]" value="{{ $script->translationByLang($lang->id)->first()->var6 }}"/> </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><span class="label label-success">{var7}</span></td>
                                @foreach ($langs as $key => $lang)
                                    <td class="input-block"> <input type="text" name="var7[{{$lang->id}}]" value="{{ $script->translationByLang($lang->id)->first()->var7 }}"/> </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><span class="label label-success">{var8}</span></td>
                                @foreach ($langs as $key => $lang)
                                    <td class="input-block"> <input type="text" name="var8[{{$lang->id}}]" value="{{ $script->translationByLang($lang->id)->first()->var8 }}"/> </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><span class="label label-success">{var9}</span></td>
                                @foreach ($langs as $key => $lang)
                                    <td class="input-block"> <input type="text" name="var9[{{$lang->id}}]" value="{{ $script->translationByLang($lang->id)->first()->var9 }}"/> </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><span class="label label-success">{var10}</span></td>
                                @foreach ($langs as $key => $lang)
                                    <td class="input-block"> <input type="text" name="var10[{{$lang->id}}]" value="{{ $script->translationByLang($lang->id)->first()->var10 }}"> </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><span class="label label-success">{var11}</span></td>
                                @foreach ($langs as $key => $lang)
                                    <td class="input-block"> <input type="text" name="var11[{{$lang->id}}]" value="{{ $script->translationByLang($lang->id)->first()->var11 }}"> </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><span class="label label-success">{var12}</span></td>
                                @foreach ($langs as $key => $lang)
                                    <td class="input-block"> <input type="text" name="var12[{{$lang->id}}]" value="{{ $script->translationByLang($lang->id)->first()->var12 }}"> </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><span class="label label-success">{var13}</span></td>
                                @foreach ($langs as $key => $lang)
                                    <td class="input-block"> <input type="text" name="var13[{{$lang->id}}]" value="{{ $script->translationByLang($lang->id)->first()->var13 }}"> </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><span class="label label-success">{var14}</span></td>
                                @foreach ($langs as $key => $lang)
                                    <td class="input-block"> <input type="text" name="var14[{{$lang->id}}]" value="{{ $script->translationByLang($lang->id)->first()->var14 }}"> </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><span class="label label-success">{var15}</span></td>
                                @foreach ($langs as $key => $lang)
                                    <td class="input-block"> <input type="text" name="var15[{{$lang->id}}]" value="{{ $script->translationByLang($lang->id)->first()->var15 }}"> </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="container-bottom">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                              @foreach ($langs as $key => $lang)
                                  <div class="panel panel-default">
                                    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo{{ $lang->id }}" role="tab" id="headingTwo">
                                      <h4 class="panel-title">
                                        <a class="collapsed" role="button"  aria-expanded="false" aria-controls="collapseTwo{{ $lang->id }}">
                                          <span class="label label-danger text-uppercase">{{ $lang->lang }}</span> <small> open to edit script</small>
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapseTwo{{ $lang->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                      <div class="panel-body">
                                          <div class="form-group">
                                              <label for="">Description</label>
                                              <textarea name="description[{{$lang->id}}]" rows="2" cols="80" class="form-control">{{ $script->translationByLang($lang->id)->first()->description }}</textarea>
                                              <label for="">Meta Title</label>
                                              <textarea name="meta_title[{{$lang->id}}]" rows="2" cols="80" class="form-control">{{ $script->translationByLang($lang->id)->first()->meta_title }}</textarea>
                                              <label for="">Meta Description</label>
                                              <textarea name="meta_description[{{$lang->id}}]" rows="2" cols="80" class="form-control">{{ $script->translationByLang($lang->id)->first()->meta_description }}</textarea>
                                              <label for="">Meta Keywords</label>
                                              <textarea name="meta_keywords[{{$lang->id}}]" rows="2" cols="80" class="form-control">{{ $script->translationByLang($lang->id)->first()->meta_keywords }}</textarea>
                                          </div>
                                      </div>
                                    </div>
                                  </div>
                              @endforeach
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-12 text-center">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-primary submit-btn">Update only empty cells</button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-primary submit-btn all">Update all cells</button>
                    </div>
                    <input type="hidden" name="all_cells" value="true" class="all-cells">
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .input-block{
        padding: 0 !important;
        margin: 0;
    }
    .input-block input{
        width: 100%;
        min-height: 35px;
        padding: 5px;
        border: none;
        font-size: 12px;
        background-color: transparent;
    }
    .panel-heading{
        cursor: pointer;
    }
    .label-primary{
        margin-right: 20px;
    }
</style>

<script>

    $(document).ready(function(){
        $('.submit-btn').on('click', function(e){
            if (!$(this).hasClass('all')) {
                $('.all-cells').remove();
                $('.form-reg').submit();
            }else{
                if (confirm("Do you really want to update all cells?")) {
                    $('.form-reg').submit();
                }
            }

        });
    });

</script>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
