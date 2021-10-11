@extends('front.app')
@section('content')
    <div class="fullWidthHeader">
        @include('front.partials.header')
    </div>
    <main>
        <div class="cabinet" id="bodyScroll">
            <ul class="crumbs">
                <li>
                    <a href="{{ url('/'.$lang->lang) }}">Home</a>
                </li>
                <li><a href="#">{{ trans('vars.Cabinet.returns') }}</a></li>
            </ul>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h5>{{ trans('vars.Cabinet.returns') }}</h5>
                    </div>
                    <div class="col-lg-4 col-md-5">
                        <div class="cabinetNavBloc">
                            <div class="cabNavTitle">
                                {{ trans('vars.General.hello') }}, {{ $userdata->name }}
                            </div>
                            
                            @include('front.account.accountMenu')
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-7">
                        <div>
                            <a href="{{ url('/'.$lang->lang.'/account/returns/create') }}" class="butt">Add new return</a>
                        </div>
                        @if ($returns->count() > 0)
                            @foreach ($returns as $key => $return)
                            <div class="row align-items-center justify-content-between historyItem">
                                <div class="col-md-8">
                                    <div>
                                        Return din {{ trans('vars.Cabinet.order') }} <small>Nr. {{ $return->order->order_hash }}</small>
                                    </div>
                                    <div>{{ trans('vars.Cabinet.atDate') }}: {{ date('d-m-Y', strtotime($return->datetime)) }}</div>
                                </div>
                                <div class="col-md-4 buttGroup">
                                    <a href="{{ url('/'.$lang->lang.'/account/returns/'.$return->id) }}" class="butt">{{ trans('vars.Cabinet.returnDetails') }}</a>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('front.partials.footer')
@stop
