@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
@include('admin::admin.alerts')

<article class="dashboard-page">
    <section class="section">

        <div class="alert alert-warning text-center alert-block">
            Please wait..<br>
            do not reload the page until the process is completed
            <div class="">
                <img src="/admin/4V0b.gif">
            </div>
        </div>
        <div class="row">
            {{-- <div class="col-md-3 card-block text-center">
                <h6>Frisbo Stocks</h6>
                <a href="/back/frisbo/synchronize-stocks" class="btn btn-primary" onclick="return confirm('Are you sure you want to synchronize the product stocks?');">
                    handle
                </a>
            </div> --}}
            {{-- <div class="col-md-3 card-block text-center">
                <h6>Optimize Images </h6>
                <a href="/back/optimize/all-images" class="btn btn-primary" onclick="return confirm('Are you sure you want to optimize all images?');">
                    handle
                </a>
            </div> --}}
            <div class="col-md-3 card-block text-center">
                <h6>Translations</h6>
                <a href="/back/get-translations" class="btn btn-primary" onclick="return confirm('Are you sure you want to synchronize translations?');">
                    handle
                </a>
            </div>
            <div class="col-md-3 card-block text-center">
                <h6>Update</h6>
                <a href="/back/update-prices" class="btn btn-primary" onclick="return confirm('Are you sure you want to update all prices?');">
                    handle Prices
                </a>
                <a href="/back/update-stocks" class="btn btn-primary" onclick="return confirm('Are you sure you want to update all stocks?');">
                    handle Stocks
                </a>
            </div>
            <div class="col-md-3 card-block text-center">
                <h6>Handle Images</h6>
                <a href="/back/handle-products-images" class="btn btn-primary run-alert" onclick="return confirm('Are you sure you want to handle all images?');">
                    handle
                </a>
            </div>
            <div class="col-md-3 card-block text-center">
                <h6>Check Products Stocks</h6>
                <a href="/back/check-products-stocks" class="btn btn-primary" onclick="return confirm('Are you sure you want to handle all images?');">
                    handle
                </a>
                <a href="/back/check-products-stocks-show-all" class="btn btn-primary" onclick="return confirm('Are you sure you want to show all producs?');">
                    show all
                </a>
            </div>
            <div class="col-md-3 card-block text-center">
                <h6>Frisbo v2.0.0</h6>
                <a href="/back/frisbo/send-products" class="btn btn-primary" onclick="return confirm('Are you sure you want to send all active products?');">
                    send
                </a>
                <a href="/back/frisbo/synchronize-stocks" class="btn btn-primary" onclick="return confirm('Are you sure you want to synhronize all stocks?');">
                    synch
                </a>
                <a href="/back/frisbo/get-stocks" class="btn btn-primary">
                    get
                </a>
            </div>
            <div class="col-md-3 card-block text-center">
                <h6>Regenerate Subproducts</h6>
                <a href="/back/regenerate-subproducts" class="btn btn-primary" onclick="return confirm('Are you sure you want to regenerate all subproducts?. Saved data will be lost');">
                    handle
                </a>
            </div>
            <div class="col-md-3 card-block text-center">
                <h6>Clean translations</h6>
                <a href="/back/clean-translations" class="btn btn-primary" onclick="return confirm('Are you sure you want to clean all tranaslations?');">
                    handle
                </a>
            </div>
            <div class="col-md-3 card-block text-center">
                <h6> <small>Generate aditionalls for products</small> </h6>
                <a href="/back/generate-aditionalls-for-products" class="btn btn-primary" onclick="return confirm('Are you sure you want to generate aditionalls fields for productss?');">
                    handle
                </a>
            </div>
        </div>

        <div class="row sameheight-container">
            @if(!is_null($menu))
            @foreach($menu as $item)
                @if ($item->on_dashboard)

            <div class="col col-xs-12 col-sm-12 col-md-6 col-xl-6 stats-col">
                <div class="card sameheight-item stats" data-exclude="xs">
                    <div class="card-block">
                        <div class="title-block">
                            <h4 class="title">
                                <a href="{{ url('/back/' . $item->src) }}">
                                {{ $item->name }}
                                </a>
                            </h4>
                        </div>
                        <div class="row row-sm stats-container">
                            <div class="col-xs-12 col-sm-6 stat-col">
                                <div class="stat-icon"> <i class="fa {{ $item->icon }}"></i> </div>
                                <div class="stat">
                                    <div class="value"> {{ moduleItems($item->table_name) }} </div>
                                    <div class="name"> quantity  </div>
                                </div>
                                <progress class="progress stat-progress" value="90" max="100">
                                    <div class="progress">
                                        <span class="progress-bar" style="width: 15%;"></span>
                                    </div>
                                </progress>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @endforeach
            @endif
        </div>
    </section>
</article>

@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
<style media="screen">
    .alert-block{
        display: none;
        position: relative;
    }
    .alert-block img{
        width: 50px;
        position: absolute;
        right: 10px;
        top: 10px;
    }
</style>
<script>
    $('.run-alert').on('click', function(){
        $('.alert-block').show();
    });
</script>
@stop
