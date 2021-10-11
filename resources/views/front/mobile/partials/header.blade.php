    @if ($site == "homewear")
    <header class="loungewear">
        <div class="tabHeader">
                <a class="buttHeader" id="jewerlyButton" href="{{ url('/'.$lang->lang.'/bijoux') }}">
                    <svg
                        width="24px"
                        height="12px"
                        viewBox="0 0 24 12"
                        version="1.1"
                        xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink"
                        >
                        <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="Header--Jewelry" transform="translate(-1538.000000, -54.000000)" fill="#5C591A">
                                <g id="Header" transform="translate(5.000000, -129.000000)">
                                    <g id="Loungewear">
                                        <g transform="translate(981.000000, 129.000000)">
                                            <g id="Button-cos" transform="translate(541.000000, 29.000000)">
                                                <g
                                                    id="left-arrow"
                                                    transform="translate(23.000000, 31.000000) scale(-1, 1) translate(-23.000000, -31.000000) translate(11.000000, 25.000000)"
                                                    >
                                                    <path
                                                        d="M23.0625007,5.03225758 L3.20823416,5.03225758 L6.49716922,1.65367503 C6.86415332,1.27664256 6.86555957,0.663916412 6.50030984,0.285093618 C6.13506011,-0.0937775623 5.54143555,-0.0951807889 5.17445145,0.281803294 L0.275830079,5.31406419 C0.275501954,5.31435451 0.275267579,5.31469322 0.274986329,5.31498354 C-0.0910602746,5.69201601 -0.0922321487,6.30672603 0.274892579,6.68501657 C0.275220704,6.68530689 0.275455079,6.6856456 0.275736329,6.68593593 L5.1743577,11.7181968 C5.54129493,12.0951325 6.13491949,12.0938261 6.50021609,11.7149065 C6.86546582,11.3360837 6.86405957,10.7233575 6.49707547,10.3463251 L3.20823416,6.96774254 L23.0625007,6.96774254 C23.5802816,6.96774254 24,6.53448423 24,6 C24,5.46551588 23.5802816,5.03225758 23.0625007,5.03225758 Z"
                                                        id="Path"
                                                        ></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                    {{ trans('vars.General.BijouxBoutique') }}
                </a>
                <a class="buttHeader" id="loungewearButton" href="{{ url('/'.$lang->lang.'/homewear') }}">
                    <svg
                    width="24px"
                    height="12px"
                    viewBox="0 0 24 12"
                    version="1.1"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    >
                    <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="Header--Jewelry" transform="translate(-1538.000000, -54.000000)" fill="#B22D00">
                            <g id="Header" transform="translate(5.000000, -129.000000)">
                                <g id="Loungewear">
                                    <g transform="translate(981.000000, 129.000000)">
                                        <g id="Button-cos" transform="translate(541.000000, 29.000000)">
                                            <g
                                                id="left-arrow"
                                                transform="translate(23.000000, 31.000000) scale(-1, 1) translate(-23.000000, -31.000000) translate(11.000000, 25.000000)"
                                                >
                                                <path
                                                    d="M23.0625007,5.03225758 L3.20823416,5.03225758 L6.49716922,1.65367503 C6.86415332,1.27664256 6.86555957,0.663916412 6.50030984,0.285093618 C6.13506011,-0.0937775623 5.54143555,-0.0951807889 5.17445145,0.281803294 L0.275830079,5.31406419 C0.275501954,5.31435451 0.275267579,5.31469322 0.274986329,5.31498354 C-0.0910602746,5.69201601 -0.0922321487,6.30672603 0.274892579,6.68501657 C0.275220704,6.68530689 0.275455079,6.6856456 0.275736329,6.68593593 L5.1743577,11.7181968 C5.54129493,12.0951325 6.13491949,12.0938261 6.50021609,11.7149065 C6.86546582,11.3360837 6.86405957,10.7233575 6.49707547,10.3463251 L3.20823416,6.96774254 L23.0625007,6.96774254 C23.5802816,6.96774254 24,6.53448423 24,6 C24,5.46551588 23.5802816,5.03225758 23.0625007,5.03225758 Z"
                                                    id="Path"
                                                    ></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </g>
                    </svg>
                    {{ trans('vars.General.HomewearStore') }}
                </a>
        </div>
        <ul class="nav">
            <li class="burger">
                <div id="burger">
                    <div class="iconBar"></div>
                </div>
            </li>
            <li class="logoCenter">
              <a href="{{ url('/'.$lang->lang.'/'.$site.'') }}"><img src="/fronts_mobile/img/icons/logo.png" alt="" /></a>
            </li>
            <li>

                    <cart-counter  class="animated"  id="cart" :items="{{ $cartProducts }}" site="loungewear"></cart-counter>

            </li>

        </ul>
        <div class="navOpen" id="navOpen">
            <!-- <a href="{{ url('/'.$lang->lang.'/'.$site.'/about') }}" class="navAbout lngNavAbout">
            <span>{{ trans('vars.HeaderFooter.about') }}</span>
            <img src="/fronts_mobile/img/icons/logo.png" alt="" />
            </a> -->
            {{-- <a href="{{ url('/'.$lang->lang.'/'.$site.'/sale') }}">{{ trans('vars.HeaderFooter.outlet') }}</a> --}}
            <a href="{{ url('/'.$lang->lang.'/'.$site.'/about') }}" class="navAbout jrwNavAbout">
            <span>{{ trans('vars.HeaderFooter.about') }}</span>
            <img src="/fronts_mobile/img/icons/jrwlogo.png" alt="" />
            </a>

            <ul class="settings">
              <li class="widthSettings">
                  <p>
                    {{-- {{ trans('vars.General.youAreIn') }} --}}
                    <!-- You are in  -->
                    {{-- <img src="/images/flags/24x24/{{ $country->flag }}" alt="icon" /> --}}
                    {{ $currency->abbr }} / {{ $lang->lang }} / <img src="/images/flags/24x24/{{ $country->flag }}" alt="icon" />
                  </p>
                  <p>|</p>
                  <a href="" data-toggle="modal" data-target="#userSettings">
                    {{ trans('vars.TehButtons.change') }}
                    <!-- change -->
                  </a>
                  <!-- {{ $lang->lang }} /  -->
              </li>
            </ul>

            <div class="navCollection">
                <span id="collectionButton">{{ trans('vars.HeaderFooter.collections') }}</span>
                <ul class="navOpen" id="collectionsOpen">
                    <li class="navBack"><span>{{ trans('vars.HeaderFooter.collections') }}</span></li>
                    @if ($collectionsMenuLoungewear->count() > 0)
                        @foreach ($collectionsMenuLoungewear as $key => $collection)
                            @if ($collection->sets->count() > 0)
                                <li class="collButton">
                                    <a href="{{ url('/'.$lang->lang.'/'.$site.'/collection/'. $collection->alias) }}">
                                        <span>{{ $collection->translation->name }}</span>
                                    </a>
                                    {{-- <span>{{ $collection->translation->name }}</span>
                                    <ul class="navOpen navOneCollection">
                                        <li class="navBack"><span>{{ trans('vars.HeaderFooter.collections') }}</span></li>
                                        @foreach ($collection->sets as $key => $set)
                                            <li><a href="{{ url('/'.$lang->lang.'/'.$site.'/collection/'. $collection->alias.'?order='.$set->id) }}">{{ $set->translation->name }}</a></li>
                                        @endforeach
                                    </ul> --}}
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>
            @if ($categoriesMenuLoungewear->count() > 0)
                @foreach ($categoriesMenuLoungewear as $key => $category)
                        <a href="{{ url('/'.$lang->lang.'/'.$site.'/catalog/'. $category->alias) }}">{{ $category->translation->name }}</a>
                @endforeach
            @endif

            <a href="{{ url('/'.$lang->lang.'/'.$site.'/sale') }}">{{ trans('vars.HeaderFooter.outlet') }}</a>
            <a href="{{ url('/'.$lang->lang.'/'.$site.'/about') }}">{{ trans('vars.PagesNames.pageAboutAnne') }}</a>
            <!-- <a href="{{ url('/'.$lang->lang.'/'.$site.'/new') }}">{{ trans('vars.HeaderFooter.newIn') }}</a>
            <a href="{{ url('/'.$lang->lang.'/'.$site.'/promotions') }}">{{ trans('vars.HeaderFooter.promo') }}</a> -->
            <!-- <div class="navCollection">
                <span id="categoryButton">{{ trans('vars.HeaderFooter.catalog') }}</span>
                <ul class="navOpen navOneCollection" id="categoryOpen">
                    @if ($categoriesMenuLoungewear->count() > 0)
                        <li class="navBack"><span>{{ trans('vars.HeaderFooter.catalog') }}</span></li>
                        @foreach ($categoriesMenuLoungewear as $key => $category)
                            <li>
                                <span><a href="{{ url('/'.$lang->lang.'/'.$site.'/catalog/'. $category->alias) }}">{{ $category->translation->name }}</a></span>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div> -->
            <!-- <a href="{{ url('/'.$lang->lang.'/'.$site.'/sale') }}">{{ trans('vars.HeaderFooter.outlet') }}</a> -->
              @if (Auth::guard('persons')->user())
              <a  href="{{ url('/'.$lang->lang.'/account/personal-data') }}" id="avatar">
                Account
                @else
              <a href="" id="avatar" data-toggle="modal" data-target="#auth">
                Account
                @endif
                  <!-- <svg viewBox="0 0 28 28" version="1.1" xmlns="http://www.w3.org/2000/svg">
                          <g id="Symbols" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                              <g id="project_20200129_150142" transform="translate(-1603.000000, -68.000000)">
                                  <g transform="translate(-359.000000, -1053.000000)" id="Group-8">
                                      <g>
                                          <g id="Group-16" transform="translate(355.500000, 1024.000000)">
                                              <g id="Group-5" transform="translate(1606.500000, 97.000000)">
                                                  <path
                                                      d="M14.4899419,27.375 C19.1843623,27.375 23,25.448744 23,23.5485015 C23,21.648259 19.1944204,14.625 14.5,14.625 C9.80557963,14.625 6,21.648259 6,23.5485015 C6,25.448744 9.79552154,27.375 14.4899419,27.375 Z"
                                                      id="Oval-Copy"
                                                      fill="none"
                                                      opacity="0.946289062"
                                                      ></path>
                                                  <path
                                                      d="M13.9898467,5.0655891e-05 C8.33156474,5.0655891e-05 3.23053052,3.40856172 1.06529337,8.63617717 C-1.09994384,13.8635529 0.0967607314,19.8806675 4.09767994,23.8818509 C7.62931266,27.4281256 12.7864312,28.8175386 17.6216642,27.5254349 C22.4568971,26.2335708 26.2334792,22.4567259 27.5255751,17.6217029 C28.8174312,12.7864403 27.4280267,7.62929018 23.8817738,4.0976358 C21.2640274,1.46549269 17.7019558,-0.00996461351 13.9898467,5.0655891e-05 Z M6.4275749,24.2634182 L6.4275749,23.052805 C6.4275749,18.8764169 9.81324461,15.4904868 13.9898467,15.4904868 C18.1664488,15.4904868 21.5521185,18.8764169 21.5521185,23.052805 L21.5521185,24.2634182 C17.0565071,27.5815195 10.9231863,27.5815195 6.4275749,24.2634182 Z M22.779262,23.2347205 L22.779262,23.052805 C22.779262,18.2065171 18.8363445,14.2635754 13.9898467,14.2635754 C9.14334894,14.2635754 5.2004314,18.2065171 5.2004314,23.052805 L5.2004314,23.2347205 C2.66249733,20.8295524 1.22563691,17.4862849 1.22755241,13.9896235 C1.22755241,6.95243969 6.95270599,1.22725285 13.9898467,1.22725285 C21.0269874,1.22725285 26.7521406,6.95243969 26.7521406,13.9896235 C26.7538168,17.4862849 25.3171961,20.8295524 22.7790223,23.2347205 L22.779262,23.2347205 Z"
                                                      id="Shape"
                                                      fill="#42261D"
                                                      fillRule="nonzero"
                                                      ></path>
                                                  <circle id="Oval" fill="none" opacity="0.946289062" cx="14" cy="9" r="4"></circle>
                                                  <path
                                                      d="M14,4 C11.2386438,4 9,6.23864382 9,9 C9,11.7613562 11.2386438,14 14,14 C16.7613561,14 19,11.7613562 19,9 C18.9967156,6.239907 16.7600929,4.0032843 14,4 Z M14,12.7064827 C11.95311,12.7064827 10.2935173,11.04689 10.2935173,8.99999997 C10.2935173,6.95285737 11.95311,5.29351726 14,5.29351726 C16.0471427,5.29351726 17.7064827,6.95285737 17.7064827,8.99999997 C17.7042089,11.0461321 16.046132,12.704209 14,12.7064827 Z"
                                                      id="Shape"
                                                      fill="#42261D"
                                                      fillRule="nonzero"
                                                      ></path>
                                              </g>
                                          </g>
                                      </g>
                                  </g>
                              </g>
                          </g>
                  </svg> -->
              </a>
              <div class="navCollection">
                  <span id="categoryButton">
                    <!-- Help & Information -->
                    {{ trans('vars.General.helpInformation') }}
                    <svg width="6px" height="14px" viewBox="0 0 6 14" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <!-- Generator: Sketch 58 (84663) - https://sketch.com -->
                        <title>Shape</title>
                        <desc>Created with Sketch.</desc>
                        <g id="AnaPopova-Site" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="Burger_375---new" transform="translate(-310.000000, -173.000000)" fill="#42261D" fill-rule="nonzero">
                                <path d="M316,179.999496 C315.994424,179.76069 315.929214,179.61673 315.845738,179.513407 L311.263173,173.291465 C311.046736,172.9548 310.556192,172.901525 310.249549,173.174905 C309.942871,173.448285 309.926585,173.959445 310.172633,174.263643 L313.818842,179.221754 C314.053173,179.555878 314.170339,179.815125 314.170339,179.999496 C314.170339,180.183867 314.053173,180.443114 313.818842,180.777238 L310.172633,185.735345 C309.893521,186.042592 309.972834,186.58149 310.267456,186.870577 C310.494222,187.093127 311.098625,187.022197 311.263173,186.707523 L315.845738,180.485585 C315.965078,180.324437 316.001411,180.204626 316,179.999496 Z" id="Shape"></path>
                            </g>
                        </g>
                    </svg>
                  </span>
                  <ul class="navOpen navOneCollection" id="categoryOpen">
                          <!-- <li class="navBack"><span>back</span></li> -->
                              <li>
                                  <span><a href="{{ url('/'.$lang->lang.'/'.$site.'/contacts') }}">{{ trans('vars.Contacts.contactsTitle') }}</a></span>
                              </li>
                              <li>
                                <span><a href="{{ url('/'.$lang->lang.'/'.$site.'/livrare-achitare-retur') }}">{{ trans('vars.DetailsProductSet.shippingPaymentReturns') }}</a></span>
                              </li>
                              <li>
                                <span><a href="{{ url('/'.$lang->lang.'/'.$site.'/size-guide') }}">{{ trans('vars.PagesNames.pageSizeGuide') }}</a></span>
                              </li>
                  </ul>
              </div>



            <!-- <li><wish-counter class="animated"  id="wish" :items="{{ $wishProducts }}" site="loungewear">Favorites</wish-counter></li> -->

            <div class="networks">
                <p>{{ trans('vars.HeaderFooter.followUs') }}:</p>
                <ul class="dspflex">
                    <!-- <li>
                        <a href="/">
                            <svg
                                width="19px"
                                height="23px"
                                viewBox="0 0 19 23"
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                >
                                <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g
                                        id="Footer"
                                        transform="translate(-1410.000000, -427.000000)"
                                        fill="#B22D00"
                                        fill-rule="nonzero"
                                        >
                                        <g id="social">
                                            <g transform="translate(1410.000000, 427.000000)">
                                                <g id="pinterest-social-logo">
                                                    <path
                                                        d="M9.90954753,0.878619946 C3.56405595,0.878619946 0.364648438,5.18305599 0.364648438,8.77278897 C0.364648438,10.94618 1.23424931,12.8794597 3.09914205,13.6002444 C3.40527583,13.7184981 3.67951076,13.6045241 3.7685419,13.2842254 C3.82995909,13.0623588 3.97588445,12.503075 4.04087242,12.2699462 C4.13037965,11.9530262 4.09538613,11.8419803 3.84876514,11.5658296 C3.31100758,10.9655511 2.96726171,10.1884551 2.96726171,9.08768178 C2.96726171,5.89415513 5.49250836,3.03534287 9.5431868,3.03534287 C13.1291894,3.03534287 15.0997769,5.10895032 15.0997769,7.87811498 C15.0997769,11.5216816 13.3955686,14.5967292 10.865799,14.5967292 C9.46843874,14.5967292 8.42291807,13.503389 8.7580941,12.1625042 C9.15944834,10.5616865 9.93668536,8.83383042 9.93668536,7.67854772 C9.93668536,6.6442217 9.35036537,5.78130728 8.13582838,5.78130728 C6.70752145,5.78130728 5.56035294,7.17940433 5.56035294,9.05209304 C5.56035294,10.2449917 5.98622645,11.05182 5.98622645,11.05182 C5.98622645,11.05182 4.52435431,16.9115743 4.26868737,17.9377915 C3.75806765,19.9812161 4.1920349,22.4866186 4.22869478,22.739569 C4.24988133,22.8895823 4.45341507,22.925171 4.54577892,22.8120979 C4.67789731,22.6492456 6.37877281,20.6623576 6.95699906,18.676821 C7.1205402,18.114609 7.89587281,15.2035398 7.89587281,15.2035398 C8.36007257,16.0407763 9.71624999,16.7784544 11.1586019,16.7784544 C15.4516165,16.7784544 18.3646484,13.0751978 18.3646484,8.11777589 C18.3648865,4.36947028 15.0090794,0.878619946 9.90954753,0.878619946 Z"
                                                        id="Shape"
                                                        ></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="/">
                            <svg
                                width="19px"
                                height="16px"
                                viewBox="0 0 19 16"
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                >
                                <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g
                                        id="Footer"
                                        transform="translate(-1474.000000, -431.000000)"
                                        fill="#B22D00"
                                        fill-rule="nonzero"
                                        >
                                        <g id="social">
                                            <g transform="translate(1410.000000, 427.000000)">
                                                <g
                                                    id="instagram-social-network-logo-of-photo-camera"
                                                    transform="translate(64.000000, 4.000000)"
                                                    >
                                                    <path
                                                        d="M18.6716793,2.57379137 C18.4390703,1.35298625 17.6125825,0.452310726 16.61762,0.317955814 C14.261522,0.000195783787 11.8767892,-0.00146291883 9.50303963,0.000195783787 C7.12889779,-0.00146291883 4.74377272,0.000195783787 2.38767475,0.317955814 C1.39330059,0.452310726 0.567401268,1.35298625 0.334792245,2.57379137 C0.00372645147,4.31234867 0,6.21014143 0,8.00011851 C0,9.79009559 0,11.6876514 0.331065794,13.4259717 C0.563282559,14.6465399 1.38937801,15.5472154 2.38434055,15.6818073 C4.7402424,15.9998043 7.12517134,16.001463 9.49931318,15.9998043 C11.8738473,16.001463 14.2581878,15.9998043 16.6138936,15.6818073 C17.6082677,15.5474524 18.4349515,14.6467769 18.6675606,13.4259717 C18.9988225,11.6874144 19,9.78985863 19,8.00011851 C19,6.21014143 19.0027451,4.31234867 18.6716793,2.57379137 Z M7,12 C7,9.32409402 7,6.67615569 7,4 C9.00019482,5.33770328 10.987142,6.66641696 13,8.01236071 C10.9933762,9.34631832 9.0054549,10.6670412 7,12 Z"
                                                        id="Shape"
                                                        ></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </li> -->
                    <li>
                        <a href="{{ trans('vars.Contacts.instagram') }}">
                            <svg
                                width="18px"
                                height="18px"
                                viewBox="0 0 18 18"
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                >
                                <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g
                                        id="Footer"
                                        transform="translate(-1548.000000, -431.000000)"
                                        fill="#B22D00"
                                        fill-rule="nonzero"
                                        >
                                        <g id="social">
                                            <g transform="translate(1410.000000, 427.000000)">
                                                <g
                                                    id="instagram-social-network-logo-of-photo-camera"
                                                    transform="translate(64.000000, 4.000000)"
                                                    >
                                                    <path
                                                        d="M76.3103887,0 L89.6897961,0 C90.9604082,0 92,0.940705375 92,2.31018019 L92,15.6898198 C92,17.0592946 90.9604082,18 89.6897961,18 L76.3103887,18 C75.0394069,18 74,17.0592946 74,15.6898198 L74,2.31018019 C74,0.940705375 75.0394069,0 76.3103887,0 L76.3103887,0 Z M87.8865291,2 C87.398665,2 87,2.40944718 87,2.91045938 L87,5.08954062 C87,5.59034519 87.398665,6 87.8865291,6 L90.1130663,6 C90.6009304,6 91,5.59034519 91,5.08954062 L91,2.91045938 C91,2.40944718 90.6009304,2 90.1130663,2 L87.8865291,2 L87.8865291,2 Z M89.9996295,8 L88.4115078,8 C88.5617606,8.4706196 88.6430935,8.96914225 88.6430935,9.48490436 C88.6430935,12.3640726 86.1315936,14.6979761 83.0340894,14.6979761 C79.9367705,14.6979761 77.4256412,12.3640726 77.4256412,9.48490436 C77.4256412,8.96878679 77.5067888,8.47044187 77.6572268,8 L76,8 L76,15.3118432 C76,15.6902228 76.3227377,16 76.7173597,16 L89.2826403,16 C89.6772623,16 90,15.6904006 90,15.3118432 L90,8 L89.9996295,8 Z M83.4998211,6 C81.5670467,6 80,7.56690234 80,9.5 C80,11.4330977 81.5670467,13 83.4998211,13 C85.4327744,13 87,11.4330977 87,9.5 C87,7.56690234 85.4329533,6 83.4998211,6 Z"
                                                        id="Shape"
                                                        ></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </li>
                    <!-- <li>
                        <a href="/">
                            <svg
                                width="21px"
                                height="21px"
                                viewBox="0 0 21 21"
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                >
                                <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g
                                        id="Footer"
                                        transform="translate(-1609.000000, -428.000000)"
                                        fill="#B22D00"
                                        fill-rule="nonzero"
                                        >
                                        <g id="social">
                                            <g transform="translate(1410.000000, 427.000000)">
                                                <g id="twitter-logo-button" transform="translate(199.000000, 1.000000)">
                                                    <path
                                                        d="M10.5,0 C4.71032385,0 0,4.71032385 0,10.5 C0,16.2892532 4.71032385,21 10.5,21 C16.2892532,21 21,16.2892532 21,10.5 C21,4.71032385 16.2900991,0 10.5,0 Z M14.7728105,7.48950555 C14.7775504,7.598206 14.7801357,7.7077902 14.7801357,7.8173744 C14.7801357,11.1539481 12.304212,15 7.77421092,15 C6.38371216,15 5.08930303,14.5833149 4,13.8665989 C4.19261015,13.8900181 4.38866746,13.9019487 4.58731014,13.9019487 C5.74124744,13.9019487 6.80254228,13.4980779 7.64537326,12.8211303 C6.5681353,12.8008042 5.65851557,12.070832 5.34525477,11.0673413 C5.49520629,11.0965048 5.64989766,11.112854 5.8080362,11.112854 C6.03253259,11.112854 6.25056555,11.0828068 6.45696434,11.0249216 C5.33060433,10.7933808 4.48217171,9.77309885 4.48217171,8.54911405 C4.48217171,8.53850912 4.48217171,8.52746233 4.48260261,8.51729928 C4.8143919,8.70597853 5.19401056,8.81998144 5.59732845,8.83279572 C4.93719703,8.3807609 4.50242379,7.60748531 4.50242379,6.73169546 C4.50242379,6.26861385 4.62350533,5.83469577 4.83550576,5.461756 C6.0493375,6.98974857 7.86426802,7.99456498 9.91015835,8.1006142 C9.86793063,7.91546993 9.84681676,7.72325571 9.84681676,7.52485529 C9.84681676,6.13074986 10.9490466,5 12.308521,5 C13.0169126,5 13.6559302,5.30665901 14.1057848,5.79713667 C14.6672412,5.6840175 15.1929333,5.47457028 15.6699343,5.18426053 C15.4846494,5.77415934 15.095551,6.26861385 14.585802,6.58145906 C15.0843477,6.52048076 15.560056,6.385268 16,6.18421634 C15.6716579,6.68971764 15.2541204,7.13468252 14.7728105,7.48950555 Z"
                                                        id="Shape"
                                                        ></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="/">
                            <svg
                                width="20px"
                                height="19px"
                                viewBox="0 0 20 19"
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                >
                                <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g
                                        id="Footer"
                                        transform="translate(-1667.000000, -429.000000)"
                                        fill="#B22D00"
                                        fill-rule="nonzero"
                                        >
                                        <g id="social">
                                            <g transform="translate(1410.000000, 427.000000)">
                                                <g id="linkedin-logotype-button" transform="translate(257.000000, 2.000000)">
                                                    <path
                                                        d="M16.2500123,0 L3.74998771,0 C1.67875733,0 0,1.59481947 0,3.56248832 L0,15.4375117 C0,17.4052179 1.67875733,19 3.74998771,19 L16.2500123,19 C18.321282,19 20,17.4051805 20,15.4375117 L20,3.56248832 C20,1.59481947 18.321282,0 16.2500123,0 Z M7.50000983,14.1015832 L5.00000983,14.1015832 L5.00000983,5.78908556 L7.50000983,5.78908556 L7.50000983,14.1015832 Z M6.32751185,5.27963557 C5.68002376,5.27963557 5.15626751,4.78087151 5.15626751,4.16575782 C5.15626751,3.55064413 5.68128231,3.05188007 6.32751185,3.05188007 C6.97499995,3.0530757 7.50001475,3.55180239 7.50001475,4.16575782 C7.50001475,4.78087151 6.97499995,5.27963557 6.32751185,5.27963557 Z M16.2500123,14.1015832 L13.7499975,14.1015832 L13.7499975,8.96206041 C13.7499975,8.35998655 13.5687752,7.93845264 12.7900039,7.93845264 C11.4987641,7.93845264 11.2499975,8.96206041 11.2499975,8.96206041 L11.2499975,14.1015832 L8.74999754,14.1015832 L8.74999754,5.78908556 L11.2499975,5.78908556 L11.2499975,6.58350622 C11.6075112,6.32345694 12.4999853,5.79024383 13.7499975,5.79024383 C14.5600066,5.79024383 16.2500123,6.2510093 16.2500123,9.03450805 L16.2500123,14.1015832 Z"
                                                        id="Shape"
                                                        ></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </li> -->
                    <li>
                        <a href="{{ trans('vars.Contacts.facebook') }}">
                            <svg
                                width="10px"
                                height="19px"
                                viewBox="0 0 10 19"
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                >
                                <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g
                                        id="Footer"
                                        transform="translate(-1732.000000, -428.000000)"
                                        fill="#B22D00"
                                        fill-rule="nonzero"
                                        >
                                        <g id="social">
                                            <g transform="translate(1410.000000, 427.000000)">
                                                <path
                                                    d="M330.187221,4.15465514 C328.766054,4.15465514 328.490905,4.82104815 328.490905,5.79901691 L328.490905,7.95536596 L331.880335,7.95536596 L331.879135,11.3329419 L328.490905,11.3329419 L328.490905,20 L324.955596,20 L324.955596,11.3329419 L322,11.3329419 L322,7.95536596 L324.955596,7.95536596 L324.955596,5.46473443 C324.955596,2.57406965 326.745162,1 329.358574,1 L332,1.00414645 L331.9998,4.15386534 L330.187221,4.15465514 Z"
                                                    id="Shape-Copy"
                                                    transform="translate(327.000000, 10.500000) rotate(-360.000000) translate(-327.000000, -10.500000) "
                                                    ></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </header>
@else
    <header class="bijoux jewerly">
        <div class="tabHeader">
                <a class="buttHeader" id="jewerlyButton" href="{{ url('/'.$lang->lang.'/bijoux') }}">
                    <svg
                        width="24px"
                        height="12px"
                        viewBox="0 0 24 12"
                        version="1.1"
                        xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink"
                        >
                        <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="Header--Jewelry" transform="translate(-1538.000000, -54.000000)" fill="#5C591A">
                                <g id="Header" transform="translate(5.000000, -129.000000)">
                                    <g id="Loungewear">
                                        <g transform="translate(981.000000, 129.000000)">
                                            <g id="Button-cos" transform="translate(541.000000, 29.000000)">
                                                <g
                                                    id="left-arrow"
                                                    transform="translate(23.000000, 31.000000) scale(-1, 1) translate(-23.000000, -31.000000) translate(11.000000, 25.000000)"
                                                    >
                                                    <path
                                                        d="M23.0625007,5.03225758 L3.20823416,5.03225758 L6.49716922,1.65367503 C6.86415332,1.27664256 6.86555957,0.663916412 6.50030984,0.285093618 C6.13506011,-0.0937775623 5.54143555,-0.0951807889 5.17445145,0.281803294 L0.275830079,5.31406419 C0.275501954,5.31435451 0.275267579,5.31469322 0.274986329,5.31498354 C-0.0910602746,5.69201601 -0.0922321487,6.30672603 0.274892579,6.68501657 C0.275220704,6.68530689 0.275455079,6.6856456 0.275736329,6.68593593 L5.1743577,11.7181968 C5.54129493,12.0951325 6.13491949,12.0938261 6.50021609,11.7149065 C6.86546582,11.3360837 6.86405957,10.7233575 6.49707547,10.3463251 L3.20823416,6.96774254 L23.0625007,6.96774254 C23.5802816,6.96774254 24,6.53448423 24,6 C24,5.46551588 23.5802816,5.03225758 23.0625007,5.03225758 Z"
                                                        id="Path"
                                                        ></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                    {{ trans('vars.General.BijouxBoutique') }}
                </a>
                <a class="buttHeader" id="loungewearButton" href="{{ url('/'.$lang->lang.'/homewear') }}">
                    <svg
                    width="24px"
                    height="12px"
                    viewBox="0 0 24 12"
                    version="1.1"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    >
                    <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="Header--Jewelry" transform="translate(-1538.000000, -54.000000)" fill="#B22D00">
                            <g id="Header" transform="translate(5.000000, -129.000000)">
                                <g id="Loungewear">
                                    <g transform="translate(981.000000, 129.000000)">
                                        <g id="Button-cos" transform="translate(541.000000, 29.000000)">
                                            <g
                                                id="left-arrow"
                                                transform="translate(23.000000, 31.000000) scale(-1, 1) translate(-23.000000, -31.000000) translate(11.000000, 25.000000)"
                                                >
                                                <path
                                                    d="M23.0625007,5.03225758 L3.20823416,5.03225758 L6.49716922,1.65367503 C6.86415332,1.27664256 6.86555957,0.663916412 6.50030984,0.285093618 C6.13506011,-0.0937775623 5.54143555,-0.0951807889 5.17445145,0.281803294 L0.275830079,5.31406419 C0.275501954,5.31435451 0.275267579,5.31469322 0.274986329,5.31498354 C-0.0910602746,5.69201601 -0.0922321487,6.30672603 0.274892579,6.68501657 C0.275220704,6.68530689 0.275455079,6.6856456 0.275736329,6.68593593 L5.1743577,11.7181968 C5.54129493,12.0951325 6.13491949,12.0938261 6.50021609,11.7149065 C6.86546582,11.3360837 6.86405957,10.7233575 6.49707547,10.3463251 L3.20823416,6.96774254 L23.0625007,6.96774254 C23.5802816,6.96774254 24,6.53448423 24,6 C24,5.46551588 23.5802816,5.03225758 23.0625007,5.03225758 Z"
                                                    id="Path"
                                                    ></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </g>
                    </svg>
                    {{ trans('vars.General.HomewearStore') }}
                </a>
        </div>
        <ul class="nav">
            <li class="burger">
                <div id="burger">
                    <div class="iconBar"></div>
                </div>
            </li>
            <li class="logoCenter"><a href="{{ url('/'.$lang->lang.'/'.$site.'') }}"><img src="/fronts_mobile/img/icons/jrwlogo.png" alt="" /></a></li>

            <!-- <li>
                <a href="{{ url('/'.$lang->lang.'/wish') }}" class="animated"  id="wish">
                    <wish-counter :items="{{ $wishProducts }}" site="loungewear"></wish-counter>
                </a>
            </li> -->
            <li>
                <!-- <a href="{{ url('/'.$lang->lang.'/cart') }}" class="animated"  id="cart"></a> -->

                <cart-counter class="animated"  id="cart" :items="{{ $cartProducts }}" site="loungewear"></cart-counter>
            </li>

        </ul>
        <div class="navOpen" id="navOpen">
            <!-- <a href="{{ url('/'.$lang->lang.'/'.$site.'/about') }}" class="navAbout lngNavAbout">
            <span>{{ trans('vars.HeaderFooter.about') }}</span>
            <img src="/fronts_mobile/img/icons/logo.png" alt="" />
            </a> -->
            <!-- <a href="{{ url('/'.$lang->lang.'/'.$site.'/about') }}" class="navAbout jrwNavAbout">
            <span>{{ trans('vars.HeaderFooter.about') }}</span>
            <img src="/fronts_mobile/img/icons/jrwlogo.png" alt="" />
            </a>
            <a href="{{ url('/'.$lang->lang.'/'.$site.'/new') }}">{{ trans('vars.HeaderFooter.newIn') }}</a>
            <a href="{{ url('/'.$lang->lang.'/'.$site.'/promotions') }}">{{ trans('vars.HeaderFooter.promo') }}</a> -->
            <ul class="settings">
              <li class="widthSettings">
                  <p>
                    {{-- Your are in  --}}
                    {{-- <img src="/images/flags/24x24/{{ $country->flag }}" alt="icon" /> --}}
                    {{ $currency->abbr }} / {{ $lang->lang }} / <img src="/images/flags/24x24/{{ $country->flag }}" alt="icon" />
                  </p>
                  <p>|</p>
                  <a href="#" data-toggle="modal" data-target="#userSettings">Change</a>
              </li>
            </ul>
            <div class="navCollection">
                <span id="collectionButton">{{ trans('vars.HeaderFooter.collections') }}</span>
                <ul class="navOpen" id="collectionsOpen">
                    <li class="navBack"><span>{{ trans('vars.HeaderFooter.collections') }}</span></li>
                    @if ($collectionsMenuJewelry->count() > 0)
                        @foreach ($collectionsMenuJewelry as $key => $collection)
                            <li class="collButton">
                                <a href="{{ url('/'.$lang->lang.'/'.$site.'/collection/'. $collection->alias) }}">
                                    <span>{{ $collection->translation->name }}</span>
                                </a>
                                {{-- <span>{{ $collection->translation->name }}</span> --}}
                                @if ($collection->sets->count() > 0)
                                    {{-- <ul class="navOpen navOneCollection">
                                        <li class="navBack"><span>{{ trans('vars.HeaderFooter.collections') }}</span></li>
                                        @foreach ($collection->sets as $key => $set)
                                            <li><a href="{{ url('/'.$lang->lang.'/'.$site.'/collection/'. $collection->alias.'?order='.$set->id) }}">{{ $set->translation->name }}</a></li>
                                        @endforeach
                                    </ul> --}}
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <!-- <div class="navCollection">
                <span id="categoryButton">{{ trans('vars.HeaderFooter.catalog') }}</span>
                <ul class="navOpen navOneCollection" id="categoryOpen">
                    @if ($categoriesMenuJewelry->count() > 0)
                        <li class="navBack"><span>{{ trans('vars.HeaderFooter.catalog') }}</span></li>
                        @foreach ($categoriesMenuJewelry as $key => $category)
                            <li>
                                <span><a href="{{ url('/'.$lang->lang.'/'.$site.'/catalog/'. $category->alias) }}">{{ $category->translation->name }}</a></span>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div> -->
            @if ($categoriesMenuJewelry->count() > 0)
                @foreach ($categoriesMenuJewelry as $key => $category)

                    <a href="{{ url('/'.$lang->lang.'/'.$site.'/catalog/'. $category->alias) }}">{{ $category->translation->name }}</a>

                @endforeach
            @endif
            {{-- <a href="{{ url('/'.$lang->lang.'/'.$site.'/sale') }}">{{ trans('vars.HeaderFooter.outlet') }}</a> --}}
            @if (Auth::guard('persons')->user())
            <a  href="{{ url('/'.$lang->lang.'/account/personal-data') }}" id="avatar">
            @else
            <a href="" id="avatar" data-toggle="modal" data-target="#auth">
                @endif
                Account
                <!-- <svg viewBox="0 0 28 28" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <g id="Symbols" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                        <g id="project_20200129_150142" transform="translate(-1603.000000, -68.000000)">
                            <g transform="translate(-359.000000, -1053.000000)" id="Group-8">
                                <g>
                                    <g id="Group-16" transform="translate(355.500000, 1024.000000)">
                                        <g id="Group-5" transform="translate(1606.500000, 97.000000)">
                                            <path
                                                d="M14.4899419,27.375 C19.1843623,27.375 23,25.448744 23,23.5485015 C23,21.648259 19.1944204,14.625 14.5,14.625 C9.80557963,14.625 6,21.648259 6,23.5485015 C6,25.448744 9.79552154,27.375 14.4899419,27.375 Z"
                                                id="Oval-Copy"
                                                fill="none"
                                                opacity="0.946289062"
                                                ></path>
                                            <path
                                                d="M13.9898467,5.0655891e-05 C8.33156474,5.0655891e-05 3.23053052,3.40856172 1.06529337,8.63617717 C-1.09994384,13.8635529 0.0967607314,19.8806675 4.09767994,23.8818509 C7.62931266,27.4281256 12.7864312,28.8175386 17.6216642,27.5254349 C22.4568971,26.2335708 26.2334792,22.4567259 27.5255751,17.6217029 C28.8174312,12.7864403 27.4280267,7.62929018 23.8817738,4.0976358 C21.2640274,1.46549269 17.7019558,-0.00996461351 13.9898467,5.0655891e-05 Z M6.4275749,24.2634182 L6.4275749,23.052805 C6.4275749,18.8764169 9.81324461,15.4904868 13.9898467,15.4904868 C18.1664488,15.4904868 21.5521185,18.8764169 21.5521185,23.052805 L21.5521185,24.2634182 C17.0565071,27.5815195 10.9231863,27.5815195 6.4275749,24.2634182 Z M22.779262,23.2347205 L22.779262,23.052805 C22.779262,18.2065171 18.8363445,14.2635754 13.9898467,14.2635754 C9.14334894,14.2635754 5.2004314,18.2065171 5.2004314,23.052805 L5.2004314,23.2347205 C2.66249733,20.8295524 1.22563691,17.4862849 1.22755241,13.9896235 C1.22755241,6.95243969 6.95270599,1.22725285 13.9898467,1.22725285 C21.0269874,1.22725285 26.7521406,6.95243969 26.7521406,13.9896235 C26.7538168,17.4862849 25.3171961,20.8295524 22.7790223,23.2347205 L22.779262,23.2347205 Z"
                                                id="Shape"
                                                fill="#42261D"
                                                fillRule="nonzero"
                                                ></path>
                                            <circle id="Oval" fill="none" opacity="0.946289062" cx="14" cy="9" r="4"></circle>
                                            <path
                                                d="M14,4 C11.2386438,4 9,6.23864382 9,9 C9,11.7613562 11.2386438,14 14,14 C16.7613561,14 19,11.7613562 19,9 C18.9967156,6.239907 16.7600929,4.0032843 14,4 Z M14,12.7064827 C11.95311,12.7064827 10.2935173,11.04689 10.2935173,8.99999997 C10.2935173,6.95285737 11.95311,5.29351726 14,5.29351726 C16.0471427,5.29351726 17.7064827,6.95285737 17.7064827,8.99999997 C17.7042089,11.0461321 16.046132,12.704209 14,12.7064827 Z"
                                                id="Shape"
                                                fill="#42261D"
                                                fillRule="nonzero"
                                                ></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg> -->
            </a>
            <!-- <a href="{{ url('/'.$lang->lang.'/'.$site.'/sale') }}">{{ trans('vars.HeaderFooter.outlet') }}</a>
            <a href="{{ url('/'.$lang->lang.'/'.$site.'/contacts') }}">{{ trans('vars.Contacts.contactsTitle') }}</a>
            <a href="{{ url('/'.$lang->lang.'/'.$site.'/livrare-achitare-retur') }}">{{ trans('vars.DetailsProductSet.shippingPaymentReturns') }}</a> -->
            <div class="navCollection">
                <span id="categoryButton">
                  <!-- Help & Information -->
                  {{ trans('vars.General.helpInformation') }}
                  <svg width="6px" height="14px" viewBox="0 0 6 14" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                      <!-- Generator: Sketch 58 (84663) - https://sketch.com -->
                      <title>Shape</title>
                      <desc>Created with Sketch.</desc>
                      <g id="AnaPopova-Site" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                          <g id="Burger_375---new" transform="translate(-310.000000, -173.000000)" fill="#42261D" fill-rule="nonzero">
                              <path d="M316,179.999496 C315.994424,179.76069 315.929214,179.61673 315.845738,179.513407 L311.263173,173.291465 C311.046736,172.9548 310.556192,172.901525 310.249549,173.174905 C309.942871,173.448285 309.926585,173.959445 310.172633,174.263643 L313.818842,179.221754 C314.053173,179.555878 314.170339,179.815125 314.170339,179.999496 C314.170339,180.183867 314.053173,180.443114 313.818842,180.777238 L310.172633,185.735345 C309.893521,186.042592 309.972834,186.58149 310.267456,186.870577 C310.494222,187.093127 311.098625,187.022197 311.263173,186.707523 L315.845738,180.485585 C315.965078,180.324437 316.001411,180.204626 316,179.999496 Z" id="Shape"></path>
                          </g>
                      </g>
                  </svg>
                </span>
                <ul class="navOpen navOneCollection" id="categoryOpen">
                            <li>
                                <span><a href="{{ url('/'.$lang->lang.'/'.$site.'/contacts') }}">{{ trans('vars.Contacts.contactsTitle') }}</a></span>
                            </li>
                            <li>
                              <span><a href="{{ url('/'.$lang->lang.'/'.$site.'/livrare-achitare-retur') }}">{{ trans('vars.DetailsProductSet.shippingPaymentReturns') }}</a></span>
                            </li>
                            {{-- <li>
                              <span><a href="{{ url('/'.$lang->lang.'/'.$site.'/size-guide') }}">{{ trans('vars.PagesNames.pageSizeGuide') }}</a></span>
                            </li> --}}
                </ul>
            </div>
            <div class="networks">
                <p>{{ trans('vars.HeaderFooter.followUs') }}:</p>
                <ul class="dspflex">
                    <!-- <li>
                        <a href="/">
                            <svg
                                width="19px"
                                height="23px"
                                viewBox="0 0 19 23"
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                >
                                <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g
                                        id="Footer"
                                        transform="translate(-1410.000000, -427.000000)"
                                        fill="#B22D00"
                                        fill-rule="nonzero"
                                        >
                                        <g id="social">
                                            <g transform="translate(1410.000000, 427.000000)">
                                                <g id="pinterest-social-logo">
                                                    <path
                                                        d="M9.90954753,0.878619946 C3.56405595,0.878619946 0.364648438,5.18305599 0.364648438,8.77278897 C0.364648438,10.94618 1.23424931,12.8794597 3.09914205,13.6002444 C3.40527583,13.7184981 3.67951076,13.6045241 3.7685419,13.2842254 C3.82995909,13.0623588 3.97588445,12.503075 4.04087242,12.2699462 C4.13037965,11.9530262 4.09538613,11.8419803 3.84876514,11.5658296 C3.31100758,10.9655511 2.96726171,10.1884551 2.96726171,9.08768178 C2.96726171,5.89415513 5.49250836,3.03534287 9.5431868,3.03534287 C13.1291894,3.03534287 15.0997769,5.10895032 15.0997769,7.87811498 C15.0997769,11.5216816 13.3955686,14.5967292 10.865799,14.5967292 C9.46843874,14.5967292 8.42291807,13.503389 8.7580941,12.1625042 C9.15944834,10.5616865 9.93668536,8.83383042 9.93668536,7.67854772 C9.93668536,6.6442217 9.35036537,5.78130728 8.13582838,5.78130728 C6.70752145,5.78130728 5.56035294,7.17940433 5.56035294,9.05209304 C5.56035294,10.2449917 5.98622645,11.05182 5.98622645,11.05182 C5.98622645,11.05182 4.52435431,16.9115743 4.26868737,17.9377915 C3.75806765,19.9812161 4.1920349,22.4866186 4.22869478,22.739569 C4.24988133,22.8895823 4.45341507,22.925171 4.54577892,22.8120979 C4.67789731,22.6492456 6.37877281,20.6623576 6.95699906,18.676821 C7.1205402,18.114609 7.89587281,15.2035398 7.89587281,15.2035398 C8.36007257,16.0407763 9.71624999,16.7784544 11.1586019,16.7784544 C15.4516165,16.7784544 18.3646484,13.0751978 18.3646484,8.11777589 C18.3648865,4.36947028 15.0090794,0.878619946 9.90954753,0.878619946 Z"
                                                        id="Shape"
                                                        ></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="/">
                            <svg
                                width="19px"
                                height="16px"
                                viewBox="0 0 19 16"
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                >
                                <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g
                                        id="Footer"
                                        transform="translate(-1474.000000, -431.000000)"
                                        fill="#B22D00"
                                        fill-rule="nonzero"
                                        >
                                        <g id="social">
                                            <g transform="translate(1410.000000, 427.000000)">
                                                <g
                                                    id="instagram-social-network-logo-of-photo-camera"
                                                    transform="translate(64.000000, 4.000000)"
                                                    >
                                                    <path
                                                        d="M18.6716793,2.57379137 C18.4390703,1.35298625 17.6125825,0.452310726 16.61762,0.317955814 C14.261522,0.000195783787 11.8767892,-0.00146291883 9.50303963,0.000195783787 C7.12889779,-0.00146291883 4.74377272,0.000195783787 2.38767475,0.317955814 C1.39330059,0.452310726 0.567401268,1.35298625 0.334792245,2.57379137 C0.00372645147,4.31234867 0,6.21014143 0,8.00011851 C0,9.79009559 0,11.6876514 0.331065794,13.4259717 C0.563282559,14.6465399 1.38937801,15.5472154 2.38434055,15.6818073 C4.7402424,15.9998043 7.12517134,16.001463 9.49931318,15.9998043 C11.8738473,16.001463 14.2581878,15.9998043 16.6138936,15.6818073 C17.6082677,15.5474524 18.4349515,14.6467769 18.6675606,13.4259717 C18.9988225,11.6874144 19,9.78985863 19,8.00011851 C19,6.21014143 19.0027451,4.31234867 18.6716793,2.57379137 Z M7,12 C7,9.32409402 7,6.67615569 7,4 C9.00019482,5.33770328 10.987142,6.66641696 13,8.01236071 C10.9933762,9.34631832 9.0054549,10.6670412 7,12 Z"
                                                        id="Shape"
                                                        ></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </li> -->
                    <li>
                        <a href="{{ trans('vars.Contacts.instagram') }}">
                            <svg
                                width="18px"
                                height="18px"
                                viewBox="0 0 18 18"
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                >
                                <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g
                                        id="Footer"
                                        transform="translate(-1548.000000, -431.000000)"
                                        fill="#B22D00"
                                        fill-rule="nonzero"
                                        >
                                        <g id="social">
                                            <g transform="translate(1410.000000, 427.000000)">
                                                <g
                                                    id="instagram-social-network-logo-of-photo-camera"
                                                    transform="translate(64.000000, 4.000000)"
                                                    >
                                                    <path
                                                        d="M76.3103887,0 L89.6897961,0 C90.9604082,0 92,0.940705375 92,2.31018019 L92,15.6898198 C92,17.0592946 90.9604082,18 89.6897961,18 L76.3103887,18 C75.0394069,18 74,17.0592946 74,15.6898198 L74,2.31018019 C74,0.940705375 75.0394069,0 76.3103887,0 L76.3103887,0 Z M87.8865291,2 C87.398665,2 87,2.40944718 87,2.91045938 L87,5.08954062 C87,5.59034519 87.398665,6 87.8865291,6 L90.1130663,6 C90.6009304,6 91,5.59034519 91,5.08954062 L91,2.91045938 C91,2.40944718 90.6009304,2 90.1130663,2 L87.8865291,2 L87.8865291,2 Z M89.9996295,8 L88.4115078,8 C88.5617606,8.4706196 88.6430935,8.96914225 88.6430935,9.48490436 C88.6430935,12.3640726 86.1315936,14.6979761 83.0340894,14.6979761 C79.9367705,14.6979761 77.4256412,12.3640726 77.4256412,9.48490436 C77.4256412,8.96878679 77.5067888,8.47044187 77.6572268,8 L76,8 L76,15.3118432 C76,15.6902228 76.3227377,16 76.7173597,16 L89.2826403,16 C89.6772623,16 90,15.6904006 90,15.3118432 L90,8 L89.9996295,8 Z M83.4998211,6 C81.5670467,6 80,7.56690234 80,9.5 C80,11.4330977 81.5670467,13 83.4998211,13 C85.4327744,13 87,11.4330977 87,9.5 C87,7.56690234 85.4329533,6 83.4998211,6 Z"
                                                        id="Shape"
                                                        ></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </li>
                    <!-- <li>
                        <a href="/">
                            <svg
                                width="21px"
                                height="21px"
                                viewBox="0 0 21 21"
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                >
                                <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g
                                        id="Footer"
                                        transform="translate(-1609.000000, -428.000000)"
                                        fill="#B22D00"
                                        fill-rule="nonzero"
                                        >
                                        <g id="social">
                                            <g transform="translate(1410.000000, 427.000000)">
                                                <g id="twitter-logo-button" transform="translate(199.000000, 1.000000)">
                                                    <path
                                                        d="M10.5,0 C4.71032385,0 0,4.71032385 0,10.5 C0,16.2892532 4.71032385,21 10.5,21 C16.2892532,21 21,16.2892532 21,10.5 C21,4.71032385 16.2900991,0 10.5,0 Z M14.7728105,7.48950555 C14.7775504,7.598206 14.7801357,7.7077902 14.7801357,7.8173744 C14.7801357,11.1539481 12.304212,15 7.77421092,15 C6.38371216,15 5.08930303,14.5833149 4,13.8665989 C4.19261015,13.8900181 4.38866746,13.9019487 4.58731014,13.9019487 C5.74124744,13.9019487 6.80254228,13.4980779 7.64537326,12.8211303 C6.5681353,12.8008042 5.65851557,12.070832 5.34525477,11.0673413 C5.49520629,11.0965048 5.64989766,11.112854 5.8080362,11.112854 C6.03253259,11.112854 6.25056555,11.0828068 6.45696434,11.0249216 C5.33060433,10.7933808 4.48217171,9.77309885 4.48217171,8.54911405 C4.48217171,8.53850912 4.48217171,8.52746233 4.48260261,8.51729928 C4.8143919,8.70597853 5.19401056,8.81998144 5.59732845,8.83279572 C4.93719703,8.3807609 4.50242379,7.60748531 4.50242379,6.73169546 C4.50242379,6.26861385 4.62350533,5.83469577 4.83550576,5.461756 C6.0493375,6.98974857 7.86426802,7.99456498 9.91015835,8.1006142 C9.86793063,7.91546993 9.84681676,7.72325571 9.84681676,7.52485529 C9.84681676,6.13074986 10.9490466,5 12.308521,5 C13.0169126,5 13.6559302,5.30665901 14.1057848,5.79713667 C14.6672412,5.6840175 15.1929333,5.47457028 15.6699343,5.18426053 C15.4846494,5.77415934 15.095551,6.26861385 14.585802,6.58145906 C15.0843477,6.52048076 15.560056,6.385268 16,6.18421634 C15.6716579,6.68971764 15.2541204,7.13468252 14.7728105,7.48950555 Z"
                                                        id="Shape"
                                                        ></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="/">
                            <svg
                                width="20px"
                                height="19px"
                                viewBox="0 0 20 19"
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                >
                                <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g
                                        id="Footer"
                                        transform="translate(-1667.000000, -429.000000)"
                                        fill="#B22D00"
                                        fill-rule="nonzero"
                                        >
                                        <g id="social">
                                            <g transform="translate(1410.000000, 427.000000)">
                                                <g id="linkedin-logotype-button" transform="translate(257.000000, 2.000000)">
                                                    <path
                                                        d="M16.2500123,0 L3.74998771,0 C1.67875733,0 0,1.59481947 0,3.56248832 L0,15.4375117 C0,17.4052179 1.67875733,19 3.74998771,19 L16.2500123,19 C18.321282,19 20,17.4051805 20,15.4375117 L20,3.56248832 C20,1.59481947 18.321282,0 16.2500123,0 Z M7.50000983,14.1015832 L5.00000983,14.1015832 L5.00000983,5.78908556 L7.50000983,5.78908556 L7.50000983,14.1015832 Z M6.32751185,5.27963557 C5.68002376,5.27963557 5.15626751,4.78087151 5.15626751,4.16575782 C5.15626751,3.55064413 5.68128231,3.05188007 6.32751185,3.05188007 C6.97499995,3.0530757 7.50001475,3.55180239 7.50001475,4.16575782 C7.50001475,4.78087151 6.97499995,5.27963557 6.32751185,5.27963557 Z M16.2500123,14.1015832 L13.7499975,14.1015832 L13.7499975,8.96206041 C13.7499975,8.35998655 13.5687752,7.93845264 12.7900039,7.93845264 C11.4987641,7.93845264 11.2499975,8.96206041 11.2499975,8.96206041 L11.2499975,14.1015832 L8.74999754,14.1015832 L8.74999754,5.78908556 L11.2499975,5.78908556 L11.2499975,6.58350622 C11.6075112,6.32345694 12.4999853,5.79024383 13.7499975,5.79024383 C14.5600066,5.79024383 16.2500123,6.2510093 16.2500123,9.03450805 L16.2500123,14.1015832 Z"
                                                        id="Shape"
                                                        ></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </li> -->
                    <li>
                        <a href="/">
                            <svg
                                width="10px"
                                height="19px"
                                viewBox="0 0 10 19"
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                >
                                <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g
                                        id="Footer"
                                        transform="translate(-1732.000000, -428.000000)"
                                        fill="#B22D00"
                                        fill-rule="nonzero"
                                        >
                                        <g id="social">
                                            <g transform="translate(1410.000000, 427.000000)">
                                                <path
                                                    d="M330.187221,4.15465514 C328.766054,4.15465514 328.490905,4.82104815 328.490905,5.79901691 L328.490905,7.95536596 L331.880335,7.95536596 L331.879135,11.3329419 L328.490905,11.3329419 L328.490905,20 L324.955596,20 L324.955596,11.3329419 L322,11.3329419 L322,7.95536596 L324.955596,7.95536596 L324.955596,5.46473443 C324.955596,2.57406965 326.745162,1 329.358574,1 L332,1.00414645 L331.9998,4.15386534 L330.187221,4.15465514 Z"
                                                    id="Shape-Copy"
                                                    transform="translate(327.000000, 10.500000) rotate(-360.000000) translate(-327.000000, -10.500000) "
                                                    ></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>


        </div>
    </header>
@endif
