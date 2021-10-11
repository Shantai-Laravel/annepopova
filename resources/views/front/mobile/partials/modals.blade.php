<div class="modals">
    @if (!Auth::guard('persons')->user())
    <div class="modal" id="auth">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modalContent">
                    <div class="closeModal" data-dismiss="modal">
                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                    </div>
                    @if (!is_null($unloggedUser))
                    <auth-mob guest="{{ json_encode($unloggedUser) }}" site="{{ $site }}"></auth-mob>
                    @else
                    <auth-mob guest="{{ null }}" site="{{ $site }}"></auth-mob>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="changeData">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="modalContent">
                    <div class="closeModal" data-dismiss="modal">
                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                    </div>
                    <div class="modalTitle">
                        Change Data
                    </div>
                    <div class="modalBody">
                        <div class="inputGroup">
                            <label for="name">Name</label>
                            <input type="text" />
                        </div>
                        <div class="inputGroup">
                            <label for="name">E-mail</label>
                            <input type="text" />
                        </div>
                        <div class="inputGroup">
                            <label for="name">Phone</label>
                            <div class="phoneContainer">
                                <div id="codSelected">
                                    <img src="/fronts/img/prod/flag.svg" alt="" />
                                    <span>+373</span>
                                    <svg
                                        width="10px"
                                        height="6px"
                                        viewBox="0 0 10 6"
                                        version="1.1"
                                        xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                        >
                                        <g id="AnaPopova-Site" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g
                                                id="Checout_375--Step1"
                                                transform="translate(-97.000000, -502.000000)"
                                                fill="#B22D00"
                                                fill-rule="nonzero"
                                                >
                                                <g id="Shipping-information" transform="translate(9.000000, 268.000000)">
                                                    <g id="Contact" transform="translate(3.000000, 45.000000)">
                                                        <g id="TELEFON" transform="translate(0.000000, 147.000000)">
                                                            <polygon
                                                                id="Shape"
                                                                transform="translate(90.000000, 45.000000) scale(1, -1) translate(-90.000000, -45.000000) "
                                                                points="90 42 85 48 89.7636023 48 95 48"
                                                                ></polygon>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                                <input type="number" />
                            </div>
                        </div>
                        <input type="submit" value="save" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Forget Password modal --}}
    <div class="modal fade" id="forgetPassword">
        <div class="modal-dialog">
            <div class="modal-content">
                <reset-password></reset-password>
            </div>
        </div>
    </div>
    @endif
    <div class="modal" id="userSettings">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="modalContent" action="{{ url('/'.$lang->lang.'/set-user-settings') }}" method="post">
                    {{ csrf_field() }}
                    <div class="closeModal" data-dismiss="modal">
                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                    </div>
                    <div class="modalTitle">
                        {{ trans('vars.FormFields.settings') }}
                    </div>
                    <div class="modalBody">
                        <div class="inputGroup">
                            <label for="country">{{ trans('vars.FormFields.shipTo') }}</label>
                            <div class="selectContainer">
                                <select name="country_id">
                                @foreach ($countries as $key => $oneCountry)
                                <option value="{{ $oneCountry->id }}" {{ $oneCountry->id == $country->id ? 'selected' : ''}}>{{ $oneCountry->translation->name }}</option>
                                @endforeach
                                </select>
                                <span>
                                    <svg
                                        width="10px"
                                        height="6px"
                                        viewBox="0 0 10 6"
                                        version="1.1"
                                        xmlns="http://www.w3.org/2000/svg"
                                        >
                                        <g id="AnaPopova-Site" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                                            <g
                                                id="Cos._APL---"
                                                transform="translate(-1592.000000, -545.000000)"
                                                fill="#42261D"
                                                fillRule="nonzero"
                                                >
                                                <g id="Order-summery" transform="translate(1233.000000, 423.000000)">
                                                    <g id="Ship" transform="translate(15.000000, 108.000000)">
                                                        <polygon
                                                            id="Shape"
                                                            transform="translate(349.000000, 17.000000) scale(1, -1) translate(-349.000000, -17.000000) "
                                                            points="349 14 344 20 348.763602 20 354 20"
                                                            ></polygon>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="inputGroup">
                            <label for="languege">{{ trans('vars.FormFields.language') }}</label>
                            <div class="selectContainer">
                                <select name="lang_id">
                                @foreach ($langs as $key => $language)
                                <option value="{{ $language->id }}" {{ $lang->id == $language->id ? 'selected' : '' }}>{{ $language->description }}</option>
                                @endforeach
                                </select>
                                <span>
                                    <svg
                                        width="10px"
                                        height="6px"
                                        viewBox="0 0 10 6"
                                        version="1.1"
                                        xmlns="http://www.w3.org/2000/svg"
                                        >
                                        <g id="AnaPopova-Site" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                                            <g
                                                id="Cos._APL---"
                                                transform="translate(-1592.000000, -545.000000)"
                                                fill="#42261D"
                                                fillRule="nonzero"
                                                >
                                                <g id="Order-summery" transform="translate(1233.000000, 423.000000)">
                                                    <g id="Ship" transform="translate(15.000000, 108.000000)">
                                                        <polygon
                                                            id="Shape"
                                                            transform="translate(349.000000, 17.000000) scale(1, -1) translate(-349.000000, -17.000000) "
                                                            points="349 14 344 20 348.763602 20 354 20"
                                                            ></polygon>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="inputGroup">
                            <label for="currency">{{ trans('vars.FormFields.currency') }}</label>
                            <div class="selectContainer">
                                <select name="currency_id">
                                @foreach ($currencies as $key => $oneCurrency)
                                <option value="{{ $oneCurrency->id }}"  {{ $oneCurrency->id == $currency->id ? 'selected' : ''}}>{{ $oneCurrency->abbr }}</option>
                                @endforeach
                                </select>
                                <span>
                                    <svg
                                        width="10px"
                                        height="6px"
                                        viewBox="0 0 10 6"
                                        version="1.1"
                                        xmlns="http://www.w3.org/2000/svg"
                                        >
                                        <g id="AnaPopova-Site" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                                            <g
                                                id="Cos._APL---"
                                                transform="translate(-1592.000000, -545.000000)"
                                                fill="#42261D"
                                                fillRule="nonzero"
                                                >
                                                <g id="Order-summery" transform="translate(1233.000000, 423.000000)">
                                                    <g id="Ship" transform="translate(15.000000, 108.000000)">
                                                        <polygon
                                                            id="Shape"
                                                            transform="translate(349.000000, 17.000000) scale(1, -1) translate(-349.000000, -17.000000) "
                                                            points="349 14 344 20 348.763602 20 354 20"
                                                            ></polygon>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <input type="submit" value="{{ trans('vars.FormFields.save') }}"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalSize">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modalContent">
                    <div class="closeModal" data-dismiss="modal">
                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                    </div>
                    <div class="closeModal" data-dismiss="modal"></div>
                    <div class="modalTitle">
                        <span>Size Guide</span>
                    </div>
                    <div class="modalBody">
                        <div class="col-12 editorPage">
                            @php $page = getPage('size-guide', $lang->id); @endphp
                            @if (!is_null($page))
                                {!! $page->body !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="shipping">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modalContent">
                    <div class="closeModal" data-dismiss="modal">
                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                    </div>
                    <div class="closeModal" data-dismiss="modal"></div>
                    <div class="modalTitle"></div>
                    <div class="modalBody">
                        <div class="col-12 editorPage">
                            @php $page = getPage('livrare-achitare-retur', $lang->id); @endphp
                            @if (!is_null($page))
                                {!! $page->body !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal fade" id="returns">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modalContent">
                    <div class="closeModal" data-dismiss="modal">
                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                    </div>
                    <div class="closeModal" data-dismiss="modal"></div>
                    <div class="modalTitle"></div>
                    <div class="modalBody">
                        <div class="col-12 editorPage">
                            @php $page = getPage('refund', $lang->id); @endphp
                            @if (!is_null($page))
                                {!! $page->body !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</div>
