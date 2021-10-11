@extends('front.mobile.app')
@section('content')
@include('front.mobile.partials.header')
<main class="clientArea">
    <div class="container">
        <h3>{{ trans('vars.Cabinet.yourPersonalData') }}</h3>
        <div class="row">
            <div class="col-12">
                <a href="{{ url()->previous() }}"><div class="undoStatic"></div></a>
            </div>
            <div class="col-12">
                <div class="user">
                    <p>{{ trans('vars.General.hello') }}, {{ $userdata->name }}</p>
                    <p>{{ trans('vars.Cabinet.welcomeTo') }} {{ trans('vars.Cabinet.yourPersonalData') }}</p>
                </div>
            </div>
            <div class="col-12">
                <div class="navArea" id="navArea">
                    <div id="pageSelected">
                        {{ trans('vars.Cabinet.yourPersonalData') }}
                        <svg
                            width="12px"
                            height="6px"
                            viewBox="0 0 12 6"
                            version="1.1"
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            >
                            <g id="AnaPopova-Site" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g
                                    id="Cabinet_Mob._375-cos"
                                    transform="translate(-325.000000, -156.000000)"
                                    fill="#B22D00"
                                    fill-rule="nonzero"
                                    >
                                    <polygon
                                        id="Shape"
                                        transform="translate(331.000000, 159.000000) scale(1, -1) translate(-331.000000, -159.000000) "
                                        points="331 156 325 162 330.716323 162 337 162"
                                        ></polygon>
                                </g>
                            </g>
                        </svg>
                    </div>
                    @include('front.mobile.account.accountMenu')
                </div>
            </div>
            <div class="col-12">
                <div class="personalData">
                    <div class="row">
                        <div class="col-12">
                            <p class="name">{{ $userdata->name }} {{ $userdata->surname }}</p>
                            <p>{{ $userdata->email }}</p>
                            <p>{{ $userdata->phone }}</p>
                        </div>
                        <div class="col-12">
                            <button data-toggle="modal" data-target="#userData">{{ trans('vars.Cabinet.modify') }}</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p>{{ trans('vars.FormFields.pass') }}</p>
                            <p>********</p>
                        </div>
                        <div class="col-12">
                            <button data-toggle="modal" data-target="#changePasswords">{{ trans('vars.Cabinet.modify') }}</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p>{{ trans('vars.Cabinet.shipiingAddress') }}</p>
                            <p></p>
                        </div>
                        <div class="col-12">
                            @if ($userdata->address)
                            <button data-toggle="modal" data-target="#changeAddress">{{ trans('vars.General.modify') }}</button>
                            @else
                            <button data-toggle="modal" data-target="#addAddress">{{ trans('vars.Cabinet.FillYourAddress') }}</button>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button onclick="location.href='{{ url('/'.$lang->lang.'/account/remove-account') }}';">{{ trans('vars.Cabinet.removeAccount') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<div class="modals">
    {{-- Modals --}}
    <div class="modal" id="userData">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="modalContent" action="{{ url('/'.$lang->lang.'/account/savePersonalData') }}" method="post">
                    {{ csrf_field() }}
                    <div class="closeModal" data-dismiss="modal">
                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                    </div>
                    <div class="modalTitle">
                        Change Data
                    </div>
                    <div class="modalBody">
                        <div class="inputGroup">
                            <label for="name">Name</label>
                            <input type="text" placeholder="Name" name="name" required value="{{ $userdata->name }}" />
                        </div>
                        <div class="inputGroup">
                            <label for="name">E-mail</label>
                            <input type="email" placeholder="Email" name="email" required value="{{ $userdata->email }}" />
                        </div>
                        <div class="inputGroup">
                            <label for="name">Phone</label>
                            <div class="phoneContainer">
                                <div id="codSelected">
                                    <img src="./img/prod/flag.svg" alt="" />
                                    <span>+373</span>
                                    <svg width="10px" height="6px" viewBox="0 0 10 6" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g id="AnaPopova-Site" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Checout_375--Step1" transform="translate(-97.000000, -502.000000)" fill="#B22D00" fill-rule="nonzero">
                                                <g id="Shipping-information" transform="translate(9.000000, 268.000000)">
                                                    <g id="Contact" transform="translate(3.000000, 45.000000)">
                                                        <g id="TELEFON" transform="translate(0.000000, 147.000000)">
                                                            <polygon id="Shape" transform="translate(90.000000, 45.000000) scale(1, -1) translate(-90.000000, -45.000000) " points="90 42 85 48 89.7636023 48 95 48"></polygon>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                                <input type="number" placeholder="Telefon" name="phone" required value="{{ $userdata->phone }}" />
                            </div>
                        </div>
                        <input type="submit" value="{{ trans('vars.FormFields.save') }}" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="changePasswords">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="modalContent" action="{{ url('/'.$lang->lang.'/account/changePass') }}" method="post">
                    {{ csrf_field() }}
                    <div class="closeModal" data-dismiss="modal">
                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                    </div>
                    <div class="modalTitle">
                        Change Password
                    </div>
                    <div class="modalBody">
                        <div class="inputGroup">
                            <label for="name">New password</label>
                            <input type="password" placeholder="new password" name="newpass" required/>
                        </div>
                        <div class="inputGroup">
                            <label for="name">New password</label>
                            <input type="password" placeholder="new password again" name="repeatnewpass" required/>
                        </div>
                        <input type="submit" value="{{ trans('vars.FormFields.save') }}" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="changePasswords_">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="modalContent" action="{{ url('/'.$lang->lang.'/account/changePass') }}" method="post">
                    {{ csrf_field() }}
                    <a class="closeModal" data-dismiss="modal">
                        <div class="icon"></div>
                    </a>
                    <div class="modalTitle">
                        <span>Modifica parola</span>
                    </div>
                    <div class="col-md-12">
                        <label for="name">new password</label>
                        <input type="password" placeholder="new password" name="newpass" required/>
                        <label for="name">new password again</label>
                        <input type="password" placeholder="new password again" name="repeatnewpass" required/>
                    </div>
                    <div class="col-12">
                        <input type="submit" class="butt buttView" value="{{ trans('vars.FormFields.save') }}" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if (!is_null($userdata->address))
    <div class="modal" id="changeAddress">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="modalContent" action="{{ url('/'.$lang->lang.'/account/saveAddress/'.$userdata->address->id) }}" method="post">
                    {{ csrf_field() }}
                    <a class="closeModal" data-dismiss="modal">
                        <div class="icon"></div>
                    </a>
                    <div class="modalTitle">
                        <span>{{ trans('vars.General.modifyAddress') }}</span>
                    </div>
                    <div class="col-md-12">
                        <label for="country">Country</label>
                        <select name="country_id" class="js-example-basic-single">
                        @foreach ($countries as $key => $countryItem) @if ($userdata->address)
                        <option value="{{ $countryItem->id }}" data-image="/images/flags/16x16/{{ $countryItem->flag }}" {{ $countryItem->id == $userdata->address->country ? 'selected' : '' }}> {{ $countryItem->name }}
                        </option>
                        @else
                        <option value="{{ $countryItem->id }}" data-image="/images/flags/16x16/{{ $countryItem->flag }}" {{ $country->id == $countryItem->id ? 'selected' : ''}}> {{ $countryItem->name }}
                        </option>
                        @endif @endforeach
                        </select>
                        <label for="region">Region</label>
                        <input type="text" placeholder="Region" name="region" value="{{ $userdata->address->region }}" required/>
                        <label for="city">City</label>
                        <input type="text" placeholder="City" name="city" value="{{ $userdata->address->location }}" required/>
                        <label for="address">Address</label>
                        <input type="text" placeholder="Address" name="address" value="{{ $userdata->address->address }}" required/>
                        <label for="homenumber">Home number</label>
                        <input type="number" placeholder="Home number" name="homenumber" value="{{ $userdata->address->homenumber }}" />
                        <label for="code">Zip code</label>
                        <input type="number" placeholder="Zip code" name="code" value="{{ $userdata->address->code }}" required/>
                    </div>
                    <div class="col-12">
                        <input type="submit" class="butt buttView" value="{{ trans('vars.FormFields.save') }}" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    @else
    <div class="modal" id="addAddress">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="modalContent" action="{{ url('/'.$lang->lang.'/account/addAddress/') }}" method="post">
                    {{ csrf_field() }}
                    <a class="closeModal" data-dismiss="modal">
                        <div class="icon"></div>
                    </a>
                    <div class="modalTitle">
                        <span>{{ trans('vars.General.modifyAddress') }}</span>
                    </div>
                    <div class="col-md-12">
                        <label for="country">Country</label>
                        <select class="" name="country_id">
                        @foreach ($countries as $key => $country)
                        <option value="{{ $country->id }}" {{ $country->id == $_COOKIE['country_id'] ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                        </select>
                        <br>
                        <label for="region">Region</label>
                        <input type="text" placeholder="Region" name="region" value="" required/>
                        <label for="city">City</label>
                        <input type="text" placeholder="City" name="city" value="" required/>
                        <label for="address">Address</label>
                        <input type="text" placeholder="Address" name="address" value="" required/>
                        <label for="homenumber">Home number</label>
                        <input type="number" placeholder="Home number" name="homenumber" value="" />
                        <label for="code">Zip code</label>
                        <input type="number" placeholder="Zip code" name="code" value="" required/>
                    </div>
                    <div class="col-12">
                        <input type="submit" class="butt buttView" value="{{ trans('vars.FormFields.save') }}" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@include('front.mobile.partials.footer')
@stop
