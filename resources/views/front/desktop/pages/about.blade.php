@extends('front.desktop.app')
@section('content')
@include('front.desktop.partials.header')
<main class="aboutContent">
    <h3>{{ trans('vars.About.title') }}</h3>
    <section>
        <div class="container">
            <div class="row">
                <div class="col block1">
                    <div class="tranText">
                        <span>{{ trans('vars.About.row1-1') }}</span> <br />
                        <span>{{ trans('vars.About.row1-2') }}</span>
                    </div>
                    <div class="scaleIn">
                        <img id="scaleIn" src="{{ Banner('Banner_About_Section_1_left', 'desktop') }}" alt="aboout" />
                    </div>
                    <div class="colorRed">{{ trans('vars.About.section1Title') }}</div>
                </div>
                <div class="col block2">
                    <div class="imgContainer">
                        <div class="scaleOut">
                            <img id="scaleOut" src="{{ Banner('Banner_About_Section_1_right', 'desktop') }}" alt="about" />
                        </div>
                        <img class="crumbsabout" src="/fronts/img/about/crumbs.png"  alt="about" />
                    </div>
                    <p>
                        {{ trans('vars.About.section1Body') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row">
                <div class="col block1">
                    <p>
                        {{ trans('vars.About.section2-1') }}
                    </p>
                    <div class="imgContainer">
                        <div class="scaleOut">
                            <img id="scaleOut2" src="{{ Banner('Banner_About_Section_2_left', 'desktop') }}" alt="about" />
                        </div>
                        <img class="crumbsabout" src="/fronts/img/about/crumbs2.png" alt="about" />
                    </div>
                </div>
                <div class="col block2">
                    <img class="top" id="top" src="{{ Banner('Banner_About_Section_2_right', 'desktop') }}" alt="anout" />
                    <div class="tranText">
                        {{ trans('vars.About.section2-2') }}
                    </div>
                    <p>
                        {{ trans('vars.About.section2-3') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p>{{ trans('vars.About.section3Title') }}</p>
                    <p>
                        {{ trans('vars.About.section3Body') }}
                    </p>
                </div>
            </div>
        </div>
        <div id="animateFon" class="imgContainer">
          <img id="animateFonSrc" src="{{ Banner('Banner_About_Section_3_fulwidth', 'desktop') }}" alt="about" />
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h4>{{ trans('vars.About.outBijouxTitle') }}</h4>
                </div>
                <div class="col-md-3">
                    <div class="top">
                        <img id="top2" src="{{ Banner('Banner_About_Section_4_bijoux_1', 'desktop') }}" alt="about" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="imgContainer">
                        <img class="crumbsabout" src="/fronts/img/about/crumbs3.png" alt="about" />
                        <div class="scaleOut">
                            <img id="scaleOut3" src="{{ Banner('Banner_About_Section_4_bijoux_2', 'desktop') }}" alt="about" />
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bottom">
                        <img id="bottom" src="{{ Banner('Banner_About_Section_4_bijoux_3', 'desktop') }}" alt="about" />
                    </div>

                </div>
                <div class="col-10">
                  <p>
                      {{ trans('vars.About.outBijouxBody') }}
                  </p>
                </div>
                <div class="col-md-6">
                    <p class="subtext">
                        {{ trans('vars.About.outBijouxSubtitle') }}
                    </p>
                </div>
                <div class="col-12 text-right">
                    <a href="{{ url('/'.$lang->lang.'/bijoux') }}">
                        <span>{{ trans('vars.About.ourBijouxLink') }}</span>
                        <ul>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h4>{{ trans('vars.About.ourHomewareTitle') }}</h4>
                </div>
                <div class="col-md-3">
                    <div class="top">
                        <img id="top3" src="{{ Banner('Banner_About_Section_5_homewear_1', 'desktop') }}" alt="about" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="imgContainer">
                        <img class="crumbsabout" src="/fronts/img/about/crumbs3.png" alt="about" />
                        <div class="scaleOut">
                            <img id="scaleOut4" src="{{ Banner('Banner_About_Section_5_homewear_2', 'desktop') }}" alt="about" />
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bottom">
                        <img id="bottom2" src="{{ Banner('Banner_About_Section_5_homewear_3', 'desktop') }}" alt="about" />
                    </div>
                </div>
                <div class="col-10">
                  <p>
                      {{ trans('vars.About.ourHomewareBody') }}
                  </p>
                </div>
                <div class="col-md-6">
                    <p class="subtext">
                        {{ trans('vars.About.ourHomewareSubtitle') }}
                    </p>
                </div>
                <div class="col-12 text-right">
                    <a href="{{ url('/'.$lang->lang.'/homewear') }}">
                        <span>{{ trans('vars.About.ourHomewareLink') }}</span>
                        <ul>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@include('front.desktop.partials.footer')
@stop
