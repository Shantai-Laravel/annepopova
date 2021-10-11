@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Crop</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Crop </h3>
</div>

<div class="list-content">
  <div class="tab-area">
      @include('admin::admin.alerts')
  </div>
    <form class="form-reg" role="form" method="POST" action="{{ route('crop.update') }}">
        {{ csrf_field() }}
        <ul>
            <h3 class="title"> Product </h3>
            {{-- <div style="display: flex; flex-wrap: wrap">
                <li style="flex: 1; margin: 5px">
                    <label>BG from</label>
                    <input type="text" name="product_bgfrom" value="{{ $crop['product']['0']['bgfrom'] ?? '' }}">
                </li>
                <li style="flex: 1; margin: 5px;">
                    <label>BG to</label>
                    <input type="text" name="product_bgto" value="{{ $crop['product']['0']['bgto'] ?? '' }}">
                </li>
            </div>

            <div style="display: flex; flex-wrap: wrap">
                <li style="flex: 1; margin: 5px">
                    <label>MD from</label>
                    <input type="text" name="product_mdfrom" value="{{ $crop['product']['1']['mdfrom'] ?? '' }}">
                </li>
                <li style="flex: 1; margin: 5px;">
                    <label>MD to</label>
                    <input type="text" name="product_mdto" value="{{ $crop['product']['1']['mdto'] ?? '' }}">
                </li>
            </div> --}}

            <div style="display: flex; flex-wrap: wrap">
                <li style="flex: 1; margin: 5px">
                    <label>SM from</label>
                    <input type="text" name="product_smfrom" value="{{ $crop['product']['2']['smfrom'] ?? '' }}">
                </li>
                <li style="flex: 1; margin: 5px;">
                    <label>SM to</label>
                    <input type="text" name="product_smto" value="{{ $crop['product']['2']['smto'] ?? '' }}">
                </li>
            </div>

            <h3 class="title"> Gallery </h3>
            {{-- <div style="display: flex; flex-wrap: wrap">
                <li style="flex: 1; margin: 5px">
                    <label>BG from</label>
                    <input type="text" name="gallery_bgfrom" value="{{ $crop['gallery']['0']['bgfrom'] ?? '' }}">
                </li>
                <li style="flex: 1; margin: 5px;">
                    <label>BG to</label>
                    <input type="text" name="gallery_bgto" value="{{ $crop['gallery']['0']['bgto'] ?? '' }}">
                </li>
            </div>

            <div style="display: flex; flex-wrap: wrap">
                <li style="flex: 1; margin: 5px">
                    <label>MD from</label>
                    <input type="text" name="gallery_mdfrom" value="{{ $crop['gallery']['1']['mdfrom'] ?? '' }}">
                </li>
                <li style="flex: 1; margin: 5px;">
                    <label>MD to</label>
                    <input type="text" name="gallery_mdto" value="{{ $crop['gallery']['1']['mdto'] ?? '' }}">
                </li>
            </div> --}}

            <div style="display: flex; flex-wrap: wrap">
                <li style="flex: 1; margin: 5px">
                    <label>SM from</label>
                    <input type="text" name="gallery_smfrom" value="{{ $crop['gallery']['2']['smfrom'] ?? '' }}">
                </li>
                <li style="flex: 1; margin: 5px;">
                    <label>SM to</label>
                    <input type="text" name="gallery_smto" value="{{ $crop['gallery']['2']['smto'] ?? '' }}">
                </li>
            </div>
        </ul>
        <ul>
            <li>
                <input type="submit" value="Save">
            </li>
        </ul>
    </form>
</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
