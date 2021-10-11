@extends('../front.desktop.app')
@section('content')
@include('front.desktop.partials.header')


    <main class="wishContent">
      <div class="container">

            <wish-block :products="{{ $data['products'] }}"
                            :sets="{{ $data['sets'] }}"
                            site="{{ $site }}">
            </wish-block>

      </div>
    </main>

@include('front.desktop.partials.footer')
@stop
