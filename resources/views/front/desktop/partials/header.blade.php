@php
    $class = "jewerly";
    if ($site == "homewear") {
        $class = "loungewear";
    }
@endphp

<header class="{{ $class }}">
    <div class="cont">
        <div class="container tabHeader">
            <div id="jrly">
                <div class="innerTab">
                    <div class="logo">
                        <img src="/fronts/img/jewrly/logo.png" alt="" />
                    </div>
                        <a class="buttHeader" id="" href="{{ url('/'.$lang->lang.'/bijoux') }}">
                            <svg width="24px" height="12px" viewBox="0 0 24 12" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" > <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Header--Jewelry" transform="translate(-1538.000000, -54.000000)" fill="#5C591A"> <g id="Header" transform="translate(5.000000, -129.000000)"> <g id="Loungewear"> <g transform="translate(981.000000, 129.000000)"> <g id="Button-cos" transform="translate(541.000000, 29.000000)"> <g id="left-arrow" transform="translate(23.000000, 31.000000) scale(-1, 1) translate(-23.000000, -31.000000) translate(11.000000, 25.000000)" > <path d="M23.0625007,5.03225758 L3.20823416,5.03225758 L6.49716922,1.65367503 C6.86415332,1.27664256 6.86555957,0.663916412 6.50030984,0.285093618 C6.13506011,-0.0937775623 5.54143555,-0.0951807889 5.17445145,0.281803294 L0.275830079,5.31406419 C0.275501954,5.31435451 0.275267579,5.31469322 0.274986329,5.31498354 C-0.0910602746,5.69201601 -0.0922321487,6.30672603 0.274892579,6.68501657 C0.275220704,6.68530689 0.275455079,6.6856456 0.275736329,6.68593593 L5.1743577,11.7181968 C5.54129493,12.0951325 6.13491949,12.0938261 6.50021609,11.7149065 C6.86546582,11.3360837 6.86405957,10.7233575 6.49707547,10.3463251 L3.20823416,6.96774254 L23.0625007,6.96774254 C23.5802816,6.96774254 24,6.53448423 24,6 C24,5.46551588 23.5802816,5.03225758 23.0625007,5.03225758 Z" id="Path" ></path> </g> </g> </g> </g> </g> </g> </g> </svg>
                            {{ trans('vars.General.BijouxBoutique') }}
                        </a>
                    <div class="activeTab">{{ trans('vars.General.BijouxBoutique') }}</div>
                </div>
            </div>
            <div id="lngwr">
                <div class="innerTab">
                    <div class="logo">
                        <img src="/fronts/img/icons/logo.png" alt="" />
                    </div>
                        <a class="buttHeader" id="" href="{{ url('/'.$lang->lang.'/homewear') }}">
                            <svg width="24px" height="12px" viewBox="0 0 24 12" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" > <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Header--Jewelry" transform="translate(-1538.000000, -54.000000)" fill="#B22D00"> <g id="Header" transform="translate(5.000000, -129.000000)"> <g id="Loungewear"> <g transform="translate(981.000000, 129.000000)"> <g id="Button-cos" transform="translate(541.000000, 29.000000)"> <g id="left-arrow" transform="translate(23.000000, 31.000000) scale(-1, 1) translate(-23.000000, -31.000000) translate(11.000000, 25.000000)" > <path d="M23.0625007,5.03225758 L3.20823416,5.03225758 L6.49716922,1.65367503 C6.86415332,1.27664256 6.86555957,0.663916412 6.50030984,0.285093618 C6.13506011,-0.0937775623 5.54143555,-0.0951807889 5.17445145,0.281803294 L0.275830079,5.31406419 C0.275501954,5.31435451 0.275267579,5.31469322 0.274986329,5.31498354 C-0.0910602746,5.69201601 -0.0922321487,6.30672603 0.274892579,6.68501657 C0.275220704,6.68530689 0.275455079,6.6856456 0.275736329,6.68593593 L5.1743577,11.7181968 C5.54129493,12.0951325 6.13491949,12.0938261 6.50021609,11.7149065 C6.86546582,11.3360837 6.86405957,10.7233575 6.49707547,10.3463251 L3.20823416,6.96774254 L23.0625007,6.96774254 C23.5802816,6.96774254 24,6.53448423 24,6 C24,5.46551588 23.5802816,5.03225758 23.0625007,5.03225758 Z" id="Path" ></path> </g> </g> </g> </g> </g> </g> </g> </svg>
                            {{ trans('vars.General.HomewearStore') }}
                        </a>
                    <div class="activeTab">{{ trans('vars.General.HomewearStore') }}</div>
                </div>
            </div>
        </div>
    </div>

    @if ($site == 'homewear')
    <div class="loungewearContent">
        <div class="container">
            <div class="headerTop">
                <ul>
                    <li class="submenu hoverThis" id="submenu">
                        <span><a href="#">{{ trans('vars.HeaderFooter.collections') }}</a></span>
                        <ul class="firstLvl" id="navCollections">
                            @if ($collectionsMenuLoungewear->count() > 0)
                            @foreach ($collectionsMenuLoungewear as $key => $collection)
                            <li class="onHover">
                                <a href="{{ url('/'.$lang->lang.'/'.$site.'/collection/'. $collection->alias) }}">{{ $collection->translation->name }}</a>
                                @if ($collection->sets->count() > 0)
                                    <ul class="nextLvl" id="navSubcollections">
                                        @foreach ($collection->sets as $key => $set)
                                            <li><a href="{{ url('/'.$lang->lang.'/'.$site.'/collection/'. $collection->alias.'?order='.$set->id) }}">{{ $set->translation->name }}</a></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                            @endforeach
                            @endif
                        </ul>
                    </li>
                    {{-- <li>
                        <span><a href="{{ url('/'.$lang->lang.'/'.$site.'/promotions') }}">{{ trans('vars.HeaderFooter.promo') }}</a></span>
                    </li> --}}
                    <li>
                        <span><a href="{{ url('/'.$lang->lang.'/'.$site.'/sale') }}">{{ trans('vars.HeaderFooter.outlet') }}</a></span>
                    </li>
                </ul>
                <a href="{{ url('/'.$lang->lang.'/'.$site.'') }}" class="logo">
                    <img src="/fronts/img/icons/logo.png" alt="logo" />
                </a>
                <ul class="userSettings">
                    <li>
                        <a href="{{ url('/'.$lang->lang.'/wish') }}" class="animated"  id="wish">
                            <wish-counter :items="{{ $wishProducts }}" site="loungewear"></wish-counter>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/'.$lang->lang.'/cart') }}" class="animated"  id="cart">
                            <cart-counter :items="{{ $cartProducts }}" site="loungewear"></cart-counter>
                        </a>
                    </li>
                    <li>
                        @if (Auth::guard('persons')->user())
                            <a href="{{ url('/'.$lang->lang.'/account/personal-data') }}" id="avatar">
                        @else
                            <a href="" id="avatar" data-toggle="modal" data-target="#auth">
                        @endif
                            <svg viewBox="0 0 28 28" version="1.1" xmlns="http://www.w3.org/2000/svg"> <g id="Symbols" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd"> <g id="project_20200129_150142" transform="translate(-1603.000000, -68.000000)"> <g transform="translate(-359.000000, -1053.000000)" id="Group-8"> <g> <g id="Group-16" transform="translate(355.500000, 1024.000000)"> <g id="Group-5" transform="translate(1606.500000, 97.000000)"> <path d="M14.4899419,27.375 C19.1843623,27.375 23,25.448744 23,23.5485015 C23,21.648259 19.1944204,14.625 14.5,14.625 C9.80557963,14.625 6,21.648259 6,23.5485015 C6,25.448744 9.79552154,27.375 14.4899419,27.375 Z" id="Oval-Copy" fill="none" opacity="0.946289062" ></path> <path d="M13.9898467,5.0655891e-05 C8.33156474,5.0655891e-05 3.23053052,3.40856172 1.06529337,8.63617717 C-1.09994384,13.8635529 0.0967607314,19.8806675 4.09767994,23.8818509 C7.62931266,27.4281256 12.7864312,28.8175386 17.6216642,27.5254349 C22.4568971,26.2335708 26.2334792,22.4567259 27.5255751,17.6217029 C28.8174312,12.7864403 27.4280267,7.62929018 23.8817738,4.0976358 C21.2640274,1.46549269 17.7019558,-0.00996461351 13.9898467,5.0655891e-05 Z M6.4275749,24.2634182 L6.4275749,23.052805 C6.4275749,18.8764169 9.81324461,15.4904868 13.9898467,15.4904868 C18.1664488,15.4904868 21.5521185,18.8764169 21.5521185,23.052805 L21.5521185,24.2634182 C17.0565071,27.5815195 10.9231863,27.5815195 6.4275749,24.2634182 Z M22.779262,23.2347205 L22.779262,23.052805 C22.779262,18.2065171 18.8363445,14.2635754 13.9898467,14.2635754 C9.14334894,14.2635754 5.2004314,18.2065171 5.2004314,23.052805 L5.2004314,23.2347205 C2.66249733,20.8295524 1.22563691,17.4862849 1.22755241,13.9896235 C1.22755241,6.95243969 6.95270599,1.22725285 13.9898467,1.22725285 C21.0269874,1.22725285 26.7521406,6.95243969 26.7521406,13.9896235 C26.7538168,17.4862849 25.3171961,20.8295524 22.7790223,23.2347205 L22.779262,23.2347205 Z" id="Shape" fill="#42261D" fillRule="nonzero" ></path> <circle id="Oval" fill="none" opacity="0.946289062" cx="14" cy="9" r="4"></circle> <path d="M14,4 C11.2386438,4 9,6.23864382 9,9 C9,11.7613562 11.2386438,14 14,14 C16.7613561,14 19,11.7613562 19,9 C18.9967156,6.239907 16.7600929,4.0032843 14,4 Z M14,12.7064827 C11.95311,12.7064827 10.2935173,11.04689 10.2935173,8.99999997 C10.2935173,6.95285737 11.95311,5.29351726 14,5.29351726 C16.0471427,5.29351726 17.7064827,6.95285737 17.7064827,8.99999997 C17.7042089,11.0461321 16.046132,12.704209 14,12.7064827 Z" id="Shape" fill="#42261D" fillRule="nonzero" ></path> </g> </g> </g> </g> </g> </g> </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" id="settings" data-toggle="modal" data-target="#userSettings">
                            @if ($country->iso != "MD")
                                {{ $currency->abbr }} /
                            @else
                                MDL /
                            @endif
                            {{ $lang->lang }} /
                            <span><img src="/images/flags/24x24/{{ $country->flag }}" alt="icon" /></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="headerBottom">
            <div class="utilBloc"></div>
            <ul>
                @if ($categoriesMenuLoungewear->count() > 0)
                @foreach ($categoriesMenuLoungewear as $key => $category)
                <li>
                    <span><a href="{{ url('/'.$lang->lang.'/'.$site.'/catalog/'. $category->alias) }}">{{ $category->translation->name }}</a></span>
                </li>
                @endforeach
                @endif
            </ul>
            <div class="utilBloc"></div>
        </div>
    </div>
@else
    <div class="jewerlyContent">
        <div class="container">
            <div class="headerTop">
                <ul>
                    <li class="submenu hoverThis" id="submenu">
                        <span><a href="#">{{ trans('vars.HeaderFooter.collections') }}</a></span>
                        <ul class="firstLvl" id="navCollections">
                            @if ($collectionsMenuJewelry->count() > 0)
                            @foreach ($collectionsMenuJewelry as $key => $collection)
                            <li class="onHover">
                                <a href="{{ url('/'.$lang->lang.'/'.$site.'/collection/'. $collection->alias) }}">{{ $collection->translation->name }}</a>
                                @if ($collection->sets->count() > 0)
                                    <ul class="nextLvl" id="navSubcollections">
                                        @foreach ($collection->sets as $key => $set)
                                            <li><a href="{{ url('/'.$lang->lang.'/'.$site.'/collection/'. $collection->alias.'?order='.$set->id) }}">{{ $set->translation->name }}</a></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                            @endforeach
                            @endif
                        </ul>
                    </li>
                    {{-- <li>
                        <span><a href="{{ url('/'.$lang->lang.'/'.$site.'/promotions') }}">{{ trans('vars.HeaderFooter.promo') }}</a></span>
                    </li> --}}
                    <li>
                        <span><a href="{{ url('/'.$lang->lang.'/'.$site.'/sale') }}">{{ trans('vars.HeaderFooter.outlet') }}</a></span>
                    </li>
                </ul>
                <a href="{{ url('/'.$lang->lang.'/'.$site.'') }}" class="logo">
                <img src="/fronts/img/jewrly/logo.png" alt="logo" />
                </a>
                <ul class="userSettings">
                    <li>
                        <a href="{{ url('/'.$lang->lang.'/wish') }}" class="animated"  id="wish">
                            <wish-counter :items="{{ $wishProducts }}" site="loungewear"></wish-counter>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/'.$lang->lang.'/cart') }}" class="animated"  id="cart">
                            <cart-counter :items="{{ $cartProducts }}" site="loungewear"></cart-counter>
                        </a>
                    </li>
                    <li>
                        @if (Auth::guard('persons')->user())
                            <a href="{{ url('/'.$lang->lang.'/account/personal-data') }}" id="avatar">
                        @else
                            <a href="" id="avatar" data-toggle="modal" data-target="#auth">
                        @endif
                            <svg viewBox="0 0 28 28" version="1.1" xmlns="http://www.w3.org/2000/svg"> <g id="Symbols" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd"> <g id="project_20200129_150142" transform="translate(-1603.000000, -68.000000)"> <g transform="translate(-359.000000, -1053.000000)" id="Group-8"> <g> <g id="Group-16" transform="translate(355.500000, 1024.000000)"> <g id="Group-5" transform="translate(1606.500000, 97.000000)"> <path d="M14.4899419,27.375 C19.1843623,27.375 23,25.448744 23,23.5485015 C23,21.648259 19.1944204,14.625 14.5,14.625 C9.80557963,14.625 6,21.648259 6,23.5485015 C6,25.448744 9.79552154,27.375 14.4899419,27.375 Z" id="Oval-Copy" fill="none" opacity="0.946289062" ></path> <path d="M13.9898467,5.0655891e-05 C8.33156474,5.0655891e-05 3.23053052,3.40856172 1.06529337,8.63617717 C-1.09994384,13.8635529 0.0967607314,19.8806675 4.09767994,23.8818509 C7.62931266,27.4281256 12.7864312,28.8175386 17.6216642,27.5254349 C22.4568971,26.2335708 26.2334792,22.4567259 27.5255751,17.6217029 C28.8174312,12.7864403 27.4280267,7.62929018 23.8817738,4.0976358 C21.2640274,1.46549269 17.7019558,-0.00996461351 13.9898467,5.0655891e-05 Z M6.4275749,24.2634182 L6.4275749,23.052805 C6.4275749,18.8764169 9.81324461,15.4904868 13.9898467,15.4904868 C18.1664488,15.4904868 21.5521185,18.8764169 21.5521185,23.052805 L21.5521185,24.2634182 C17.0565071,27.5815195 10.9231863,27.5815195 6.4275749,24.2634182 Z M22.779262,23.2347205 L22.779262,23.052805 C22.779262,18.2065171 18.8363445,14.2635754 13.9898467,14.2635754 C9.14334894,14.2635754 5.2004314,18.2065171 5.2004314,23.052805 L5.2004314,23.2347205 C2.66249733,20.8295524 1.22563691,17.4862849 1.22755241,13.9896235 C1.22755241,6.95243969 6.95270599,1.22725285 13.9898467,1.22725285 C21.0269874,1.22725285 26.7521406,6.95243969 26.7521406,13.9896235 C26.7538168,17.4862849 25.3171961,20.8295524 22.7790223,23.2347205 L22.779262,23.2347205 Z" id="Shape" fill="#42261D" fillRule="nonzero" ></path> <circle id="Oval" fill="none" opacity="0.946289062" cx="14" cy="9" r="4"></circle> <path d="M14,4 C11.2386438,4 9,6.23864382 9,9 C9,11.7613562 11.2386438,14 14,14 C16.7613561,14 19,11.7613562 19,9 C18.9967156,6.239907 16.7600929,4.0032843 14,4 Z M14,12.7064827 C11.95311,12.7064827 10.2935173,11.04689 10.2935173,8.99999997 C10.2935173,6.95285737 11.95311,5.29351726 14,5.29351726 C16.0471427,5.29351726 17.7064827,6.95285737 17.7064827,8.99999997 C17.7042089,11.0461321 16.046132,12.704209 14,12.7064827 Z" id="Shape" fill="#42261D" fillRule="nonzero" ></path> </g> </g> </g> </g> </g> </g> </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" id="settings" data-toggle="modal" data-target="#userSettings">
                            @if ($country->iso != "MD")
                                {{ $currency->abbr }} /
                            @else
                                MDL /
                            @endif
                            {{ $lang->lang }} /
                            <span><img src="/images/flags/24x24/{{ $country->flag }}" alt="icon" /></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="headerBottom">
            <div class="utilBloc" style="background-position: 100%;"></div>
            <ul>
                @if ($categoriesMenuJewelry->count() > 0)
                @foreach ($categoriesMenuJewelry as $key => $category)
                <li>
                    <span><a href="{{ url('/'.$lang->lang.'/'.$site.'/catalog/'. $category->alias) }}">{{ $category->translation->name }}</a></span>
                </li>
                @endforeach
                @endif
            </ul>
            <div class="utilBloc"></div>
        </div>
    </div>
@endif

</header>
