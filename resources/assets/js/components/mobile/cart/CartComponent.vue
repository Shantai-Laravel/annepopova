<template>
            <div class="row productsList">
                <div class="col-12" v-if="ready">
                    <div class="col-12 text-center text-danger" v-if="serverError">
                        <p class="text-center">A avut loc o problema tehnica.</p>
                        <p class="text-center">Va sugeram sa reincarcati pagina</p>
                        <a href="#" @click="refreshPage">Reincarca</a>
                    </div>
                    <div class="col-12 text-center" v-if="!cartProds.length &&  !cartSubprods.length">
                        {{ trans.front.cart.empty }}
                    </div>
                    <div class="cartItem" v-for="(cartSubprod, key) in cartSubprods">
                        <a class="img" :href="'/' + $lang + '/' + site + '/catalog/' + cartSubprod.subproduct.product.category.alias + '/' + cartSubprod.subproduct.product.alias">
                        <img :src="'/images/products/sm/' + cartSubprod.subproduct.product.main_image.src" alt="" v-if="cartSubprod.subproduct.product.main_image"/>
                        <img src="/images/no-image-ap.jpg" alt="" v-else/>
                        </a>
                        <div class="description">
                            <a :href="'/' + $lang + '/' + site + '/catalog/' + cartSubprod.subproduct.product.category.alias + '/' + cartSubprod.subproduct.product.alias">
                            {{ cartSubprod.subproduct.product.translation.name }}
                            </a>
                            <div class="price">
                                {{ cartSubprod.subproduct.product.main_price.price * cartSubprod.qty }} {{ $mainCurrency }}
                            </div>
                            <div class="params">
                                <span>{{ trans.vars.DetailsProductSet.size }}: {{ cartSubprod.subproduct.parameter_value.translation.name }}</span>
                                <span>{{ trans.vars.DetailsProductSet.qty }}:  <span class="qtyBox">{{ cartSubprod.qty }}</span></span>
                            </div>
                            <div class="methods">
                                <div class="edit" id="edit">
                                    {{ trans.vars.Cart.edit }}
                                    <select class="qty" @change="changeProductQty" :name="cartSubprod.id">
                                        <option disabled>Choose quantity</option>
                                        <option :value="key + 1" :selected="key + 1 == cartSubprod.qty" v-for="(stoc, key) in cartSubprod.stock_qty">{{ key + 1}}</option>
                                    </select>
                                </div>
                                <!-- <div class="addToWish" @click="moveProductToWish(cartSubprod.id)">
                                    {{ trans.vars.Cart.moveTo }}
                                    <span>
                                        <svg
                                            width="23px"
                                            height="20px"
                                            viewBox="0 0 31 26"
                                            version="1.1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            >
                                            <g id="Symbols" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                                                <g
                                                    id="project_20200129_150142"
                                                    transform="translate(-1443.000000, -66.000000)"
                                                    fillRule="nonzero"
                                                    >
                                                    <g transform="translate(-359.000000, -1055.000000)" id="Group-8">
                                                        <g id="Group-16" transform="translate(356.500000, 1055.000000)">
                                                            <g id="Shape-3" transform="translate(1445.500000, 66.000000)">
                                                                <path
                                                                    d="M31,7.921875 C31,10.4157986 29.6795015,12.9548611 27.0385045,15.5390625 L16.2611607,25.6953125 C16.0535714,25.8984375 15.7998512,26 15.5,26 C15.2001488,26 14.9464286,25.8984375 14.7388393,25.6953125 L3.94419643,15.5052083 C3.82886905,15.4149306 3.6702939,15.2682292 3.46847098,15.0651042 C3.26664807,14.8619792 2.94661458,14.4924045 2.50837054,13.9563802 C2.07012649,13.4203559 1.67801339,12.8702257 1.33203125,12.3059896 C0.986049107,11.7417535 0.677548363,11.0590278 0.406529018,10.2578125 C0.135509673,9.45659722 0,8.67795139 0,7.921875 C0,5.43923611 0.732328869,3.49826389 2.19698661,2.09895833 C3.66164435,0.699652778 5.68563988,0 8.26897321,0 C8.98400298,0 9.71344866,0.121310764 10.4573103,0.363932292 C11.2011719,0.606553819 11.8931362,0.933810764 12.5332031,1.34570313 C13.1732701,1.75759549 13.7239583,2.14409722 14.1852679,2.50520833 C14.6465774,2.86631944 15.0848214,3.25 15.5,3.65625 C15.9151786,3.25 16.3534226,2.86631944 16.8147321,2.50520833 C17.2760417,2.14409722 17.8267299,1.75759549 18.4667969,1.34570313 C19.1068638,0.933810764 19.7988281,0.606553819 20.5426897,0.363932292 C21.2865513,0.121310764 22.015997,0 22.7310268,0 C25.3143601,0 27.3383557,0.699652778 28.8030134,2.09895833 C30.2676711,3.49826389 31,5.43923611 31,7.921875 Z"
                                                                    id="Shape"
                                                                    fill="none"
                                                                    opacity="0.916666667"
                                                                    ></path>
                                                                <path
                                                                    d="M29.2285714,7.62639287 C29.2285714,6.65683111 29.1004619,5.80098339 28.8442429,5.05884971 C28.5880239,4.31671602 28.260302,3.72719853 27.861077,3.29029725 C27.4618521,2.85339596 26.9762277,2.49729149 26.4042039,2.22198384 C25.8321801,1.94667618 25.2720734,1.76114276 24.7238839,1.66538357 C24.1756944,1.56962438 23.5917535,1.52174479 22.972061,1.52174479 C22.3523686,1.52174479 21.6850074,1.67436099 20.9699777,1.9795934 C20.2549479,2.2848258 19.5965247,2.66786254 18.994708,3.12870362 C18.3928912,3.5895447 17.877474,4.02046104 17.4484561,4.42145263 C17.0194382,4.82244422 16.6619234,5.19051859 16.3759115,5.52567573 C16.1614025,5.78901349 15.869432,5.92068237 15.5,5.92068237 C15.130568,5.92068237 14.8385975,5.78901349 14.6240885,5.52567573 C14.3380766,5.19051859 13.9805618,4.82244422 13.5515439,4.42145263 C13.122526,4.02046104 12.6071088,3.5895447 12.005292,3.12870362 C11.4034753,2.66786254 10.7450521,2.2848258 10.0300223,1.9795934 C9.31499256,1.67436099 8.64763145,1.52174479 8.02793899,1.52174479 C7.40824653,1.52174479 6.82430556,1.56962438 6.27611607,1.66538357 C5.72792659,1.76114276 5.16781994,1.94667618 4.59579613,2.22198384 C4.02377232,2.49729149 3.53814794,2.85339596 3.13892299,3.29029725 C2.73969804,3.72719853 2.41197607,4.31671602 2.15575707,5.05884971 C1.89953807,5.80098339 1.77142857,6.65683111 1.77142857,7.62639287 C1.77142857,9.63733576 2.88568328,11.7619927 5.11419271,14.0003636 L15.5,24.0550781 L25.8679315,14.0183185 C28.1083581,11.7679776 29.2285714,9.63733576 29.2285714,7.62639287 Z M31,7.921875 C31,10.4157986 29.6795015,12.9548611 27.0385045,15.5390625 L16.2611607,25.6953125 C16.0535714,25.8984375 15.7998512,26 15.5,26 C15.2001488,26 14.9464286,25.8984375 14.7388393,25.6953125 L3.94419643,15.5052083 C3.82886905,15.4149306 3.6702939,15.2682292 3.46847098,15.0651042 C3.26664807,14.8619792 2.94661458,14.4924045 2.50837054,13.9563802 C2.07012649,13.4203559 1.67801339,12.8702257 1.33203125,12.3059896 C0.986049107,11.7417535 0.677548363,11.0590278 0.406529018,10.2578125 C0.135509673,9.45659722 5.32907052e-15,8.67795139 5.32907052e-15,7.921875 C5.32907052e-15,5.43923611 0.732328869,3.49826389 2.19698661,2.09895833 C3.66164435,0.699652778 5.68563988,-1.0658141e-14 8.26897321,-1.0658141e-14 C8.98400298,-1.0658141e-14 9.71344866,0.121310764 10.4573103,0.363932292 C11.2011719,0.606553819 11.8931362,0.933810764 12.5332031,1.34570312 C13.1732701,1.75759549 13.7239583,2.14409722 14.1852679,2.50520833 C14.6465774,2.86631944 15.0848214,3.25 15.5,3.65625 C15.9151786,3.25 16.3534226,2.86631944 16.8147321,2.50520833 C17.2760417,2.14409722 17.8267299,1.75759549 18.4667969,1.34570312 C19.1068638,0.933810764 19.7988281,0.606553819 20.5426897,0.363932292 C21.2865513,0.121310764 22.015997,-1.0658141e-14 22.7310268,-1.0658141e-14 C25.3143601,-1.0658141e-14 27.3383557,0.699652778 28.8030134,2.09895833 C30.2676711,3.49826389 31,5.43923611 31,7.921875 Z"
                                                                    id="Shape"
                                                                    fill="#42261D"
                                                                    ></path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                </div> -->
                            </div>
                        </div>
                        <div class="remove" @click="removeProduct(cartSubprod, key)"></div>
                    </div>
                    <div class="cartItem" v-for="(cartProd, key) in cartProds">
                        <a class="img" :href="'/' + $lang + '/' + site + '/catalog/' + cartProd.product.category.alias + '/' + cartProd.product.alias">
                        <img :src="'/images/products/sm/' + cartProd.product.main_image.src" alt="" v-if="cartProd.product.main_image"/>
                        <img src="/images/no-image-ap.jpg" alt="" v-else/>
                        </a>
                        <div class="description">
                            <a :href="'/' + $lang + '/' + site + '/catalog/' + cartProd.product.category.alias + '/' + cartProd.product.alias">
                            {{ cartProd.product.translation.name }}
                            </a>
                            <div class="price">
                                {{ cartProd.product.main_price.price * cartProd.qty }} {{ $mainCurrency }}
                            </div>
                            <div class="params">
                                <!-- <span>{{ trans.vars.DetailsProductSet.size }}: {{ cartProd.parameter_value.translation.name }}</span> -->
                                <span>{{ trans.vars.DetailsProductSet.qty }}:  <span class="qtyBox">{{ cartProd.qty }}</span></span>
                            </div>
                            <div class="methods">
                                <div class="edit" id="edit">
                                    {{ trans.vars.Cart.edit }}
                                    <select class="qty" @change="changeProductQty" :name="cartProd.id">
                                        <option disabled>Choose quantity</option>
                                        <option :value="key + 1" :selected="key + 1 == cartProd.qty" v-for="(stoc, key) in cartProd.stock_qty">{{ key + 1}}</option>
                                    </select>
                                </div>
                                <div class="addToWish" @click="moveProductToWish(cartProd.id)">
                                    {{ trans.vars.Cart.moveTo }}
                                    <span>
                                        <svg
                                            width="23px"
                                            height="20px"
                                            viewBox="0 0 31 26"
                                            version="1.1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            >
                                            <g id="Symbols" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                                                <g
                                                    id="project_20200129_150142"
                                                    transform="translate(-1443.000000, -66.000000)"
                                                    fillRule="nonzero"
                                                    >
                                                    <g transform="translate(-359.000000, -1055.000000)" id="Group-8">
                                                        <g id="Group-16" transform="translate(356.500000, 1055.000000)">
                                                            <g id="Shape-3" transform="translate(1445.500000, 66.000000)">
                                                                <path
                                                                    d="M31,7.921875 C31,10.4157986 29.6795015,12.9548611 27.0385045,15.5390625 L16.2611607,25.6953125 C16.0535714,25.8984375 15.7998512,26 15.5,26 C15.2001488,26 14.9464286,25.8984375 14.7388393,25.6953125 L3.94419643,15.5052083 C3.82886905,15.4149306 3.6702939,15.2682292 3.46847098,15.0651042 C3.26664807,14.8619792 2.94661458,14.4924045 2.50837054,13.9563802 C2.07012649,13.4203559 1.67801339,12.8702257 1.33203125,12.3059896 C0.986049107,11.7417535 0.677548363,11.0590278 0.406529018,10.2578125 C0.135509673,9.45659722 0,8.67795139 0,7.921875 C0,5.43923611 0.732328869,3.49826389 2.19698661,2.09895833 C3.66164435,0.699652778 5.68563988,0 8.26897321,0 C8.98400298,0 9.71344866,0.121310764 10.4573103,0.363932292 C11.2011719,0.606553819 11.8931362,0.933810764 12.5332031,1.34570313 C13.1732701,1.75759549 13.7239583,2.14409722 14.1852679,2.50520833 C14.6465774,2.86631944 15.0848214,3.25 15.5,3.65625 C15.9151786,3.25 16.3534226,2.86631944 16.8147321,2.50520833 C17.2760417,2.14409722 17.8267299,1.75759549 18.4667969,1.34570313 C19.1068638,0.933810764 19.7988281,0.606553819 20.5426897,0.363932292 C21.2865513,0.121310764 22.015997,0 22.7310268,0 C25.3143601,0 27.3383557,0.699652778 28.8030134,2.09895833 C30.2676711,3.49826389 31,5.43923611 31,7.921875 Z"
                                                                    id="Shape"
                                                                    fill="none"
                                                                    opacity="0.916666667"
                                                                    ></path>
                                                                <path
                                                                    d="M29.2285714,7.62639287 C29.2285714,6.65683111 29.1004619,5.80098339 28.8442429,5.05884971 C28.5880239,4.31671602 28.260302,3.72719853 27.861077,3.29029725 C27.4618521,2.85339596 26.9762277,2.49729149 26.4042039,2.22198384 C25.8321801,1.94667618 25.2720734,1.76114276 24.7238839,1.66538357 C24.1756944,1.56962438 23.5917535,1.52174479 22.972061,1.52174479 C22.3523686,1.52174479 21.6850074,1.67436099 20.9699777,1.9795934 C20.2549479,2.2848258 19.5965247,2.66786254 18.994708,3.12870362 C18.3928912,3.5895447 17.877474,4.02046104 17.4484561,4.42145263 C17.0194382,4.82244422 16.6619234,5.19051859 16.3759115,5.52567573 C16.1614025,5.78901349 15.869432,5.92068237 15.5,5.92068237 C15.130568,5.92068237 14.8385975,5.78901349 14.6240885,5.52567573 C14.3380766,5.19051859 13.9805618,4.82244422 13.5515439,4.42145263 C13.122526,4.02046104 12.6071088,3.5895447 12.005292,3.12870362 C11.4034753,2.66786254 10.7450521,2.2848258 10.0300223,1.9795934 C9.31499256,1.67436099 8.64763145,1.52174479 8.02793899,1.52174479 C7.40824653,1.52174479 6.82430556,1.56962438 6.27611607,1.66538357 C5.72792659,1.76114276 5.16781994,1.94667618 4.59579613,2.22198384 C4.02377232,2.49729149 3.53814794,2.85339596 3.13892299,3.29029725 C2.73969804,3.72719853 2.41197607,4.31671602 2.15575707,5.05884971 C1.89953807,5.80098339 1.77142857,6.65683111 1.77142857,7.62639287 C1.77142857,9.63733576 2.88568328,11.7619927 5.11419271,14.0003636 L15.5,24.0550781 L25.8679315,14.0183185 C28.1083581,11.7679776 29.2285714,9.63733576 29.2285714,7.62639287 Z M31,7.921875 C31,10.4157986 29.6795015,12.9548611 27.0385045,15.5390625 L16.2611607,25.6953125 C16.0535714,25.8984375 15.7998512,26 15.5,26 C15.2001488,26 14.9464286,25.8984375 14.7388393,25.6953125 L3.94419643,15.5052083 C3.82886905,15.4149306 3.6702939,15.2682292 3.46847098,15.0651042 C3.26664807,14.8619792 2.94661458,14.4924045 2.50837054,13.9563802 C2.07012649,13.4203559 1.67801339,12.8702257 1.33203125,12.3059896 C0.986049107,11.7417535 0.677548363,11.0590278 0.406529018,10.2578125 C0.135509673,9.45659722 5.32907052e-15,8.67795139 5.32907052e-15,7.921875 C5.32907052e-15,5.43923611 0.732328869,3.49826389 2.19698661,2.09895833 C3.66164435,0.699652778 5.68563988,-1.0658141e-14 8.26897321,-1.0658141e-14 C8.98400298,-1.0658141e-14 9.71344866,0.121310764 10.4573103,0.363932292 C11.2011719,0.606553819 11.8931362,0.933810764 12.5332031,1.34570312 C13.1732701,1.75759549 13.7239583,2.14409722 14.1852679,2.50520833 C14.6465774,2.86631944 15.0848214,3.25 15.5,3.65625 C15.9151786,3.25 16.3534226,2.86631944 16.8147321,2.50520833 C17.2760417,2.14409722 17.8267299,1.75759549 18.4667969,1.34570312 C19.1068638,0.933810764 19.7988281,0.606553819 20.5426897,0.363932292 C21.2865513,0.121310764 22.015997,-1.0658141e-14 22.7310268,-1.0658141e-14 C25.3143601,-1.0658141e-14 27.3383557,0.699652778 28.8030134,2.09895833 C30.2676711,3.49826389 31,5.43923611 31,7.921875 Z"
                                                                    id="Shape"
                                                                    fill="#42261D"
                                                                    ></path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="remove" @click="removeProduct(cartProd, key)"></div>
                    </div>

                </div>

                <div class="modals">
                    <div class="modal" id="checkStocksQty">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modalContent">
                                    <div class="closeModal" data-dismiss="modal">
                                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                                    </div>
                                    <div class="closeModal" data-dismiss="modal"></div>
                                    <div class="col-md-12">
                                        <p class="text-center text-danger" >
                                            {{ trans.front.cart.notInStock }}
                                        </p>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-center">
                                        <input type="submit" class="butt  onSubmit" value="Move to Favorites" data-dismiss="modal" @click="moveAllProductToWish"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal" id="checkStocks">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modalContent">
                                    <div class="closeModal" data-dismiss="modal">
                                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                                    </div>
                                    <div class="closeModal" data-dismiss="modal"></div>
                                    <div class="col-md-12">
                                        <p class="text-center text-danger" >
                                            Unui sau mai multor dintre articolele din coșul dvs. de cumpărături nu ii este atribuita cantitatea. Va rugam sa o alegeti.
                                        </p>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-center">
                                        <input type="submit" class="butt  onSubmit" value="close" data-dismiss="modal" @click="moveAllProductToWish"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Summary -->
                <div class="col-12">
                  <cart-summary
                      v-if="mode != 'order-shipping' && mode != 'order-payment'"
                      :prods="cartProds"
                      :subprods="cartSubprods"
                      :mode="mode"
                      site="homewear"
                      ></cart-summary>
                </div>
                <!-- <div class="col-12">
                    <div class="info">
                        <div class="infoBloc">
                            <p class="title">{{ trans.vars.Cart.weAccept }}:</p>
                            <ul class="paymentMethods">
                                <li><img src="/fronts/img/payment-methods/visa.svg" alt="payment" /></li>
                                <li><img src="/fronts/img/payment-methods/mastercart.svg" alt="payment" /></li>
                                <li><img src="/fronts/img/payment-methods/paypal.svg" alt="payment" /></li>
                                <li><img src="/fronts/img/payment-methods/ideal.svg" alt="payment" /></li>
                                <li><img src="/fronts/img/payment-methods/bancontact.svg" alt="payment" /></li>
                            </ul>
                        </div>
                        <div class="infoBloc">
                            <p class="title">{{ trans.vars.weAccept }}</p>
                            <p>
                                {{ trans.vars.Contacts.contactUsBy }}
                            </p>
                        </div>
                        <div class="infoBloc">
                            <p class="title">{{ trans.vars.Orders.billingDescriptor }}</p>
                            <p>
                                {{ trans.vars.Orders.billingDescriptorText1 }}
                            </p>
                        </div>
                        <div class="infoBloc">
                            <p class="title">{{ trans.vars.Contacts.queriesPaymentShippingReturnsCompany }}</p>
                            <p>{{ trans.vars.Contacts.queriesPaymentShippingReturnsAddress }}</p>
                        </div>
                    </div>
                </div> -->
            </div>
</template>

<script>
    import { bus } from '../../../app';

    export default {
        props: ['items', 'mode', 'site'],
        data() {
            return {
                loading: false,
                ready: false,
                serverError: false,
                notInStoc: false,
                cartProds: [],
                cartSubprods: [],
                qty: 1,
                siteType: 'homewear',
            }
        },
        mounted() {
            if (this.site == "") {
                this.siteType = 'homewear';
            }
            bus.$on('updateCart', data => {
                this.load();
            })

            bus.$on('changeWarehouse', data => {
                this.changeWarehouse(data);
            });
            this.load();
        },
        methods: {
            sendDataToSummary(){
                bus.$emit('refreshSummary', {
                    products: this.cartProds,
                    subproducts: this.cartSubprods,
                });
            },
            // update products list and emit event in CartBoxComponent
            updateItemsList(data) {
                this.cartProds = data.products;
                this.cartSubprods = data.subproducts;

                bus.$emit('updateCartBox', {
                    data: data
                });

                bus.$emit('refreshSummary', {
                    products: this.cartProds,
                    subproducts: this.cartSubprods,
                });
            },
            // load cart products method
            load() {
                axios.post('/' + this.$lang + '/' + this.siteType + '/get-cart-items')
                    .then(response => {
                        this.cartProds = response.data.products;
                        this.cartSubprods = response.data.subproducts;
                        this.checkStock();

                        bus.$emit('refreshSummary', {
                            products: this.cartProds,
                            subproducts: this.cartSubprods,
                        });

                        if (this.mode != 'order-shipping' && this.mode != 'order-payment') {
                            bus.$emit('ga-event-viewCart', {products: this.cartProds, subproducts: this.cartSubprods});
                        }
                        this.loading = false;
                        this.ready = true;
                    })
                    .catch(e => {
                        this.serverError = 'A avut loc o problema tehnica!';
                        console.log('error load products');
                    })
                },
                // remove all cart products method
                removeAllCart(e) {
                    this.loading = true;
                    axios.post('/' + this.$lang + '/' + this.siteType + '/remove-all-cart')
                        .then(response => {
                            this.updateItemsList(response.data);
                            this.loading = false;
                        })
                        .catch(e => {
                            this.serverError = 'A avut loc o problema tehnica!';
                            console.log('error remove all carts');
                        })
                },
                // remove one cart product method
                removeProduct(cart, key) {
                    this.loading = true;
                    axios.post('/' + this.$lang + '/' + this.siteType + '/remove-cart-item', {
                            cartId: cart.id
                        })
                        .then(response => {
                            this.updateItemsList(response.data);
                            bus.$emit('ga-event-removeFromCart', cart);
                            this.loading = false;
                        })
                        .catch(e => {
                            this.serverError = 'A avut loc o problema tehnica!';
                            console.log('error remove product');
                        })
                },
                // change product quantity method
                changeProductQty(e) {
                    this.loading = true;
                    axios.post('/' + this.$lang + '/' + this.siteType + '/change-product-qty', {
                            cartId: e.target.name,
                            qty: e.target.value,
                        })
                        .then(response => {
                            this.updateItemsList(response.data);
                            this.checkStock()
                        })
                        .catch(e => {
                            this.serverError = 'A avut loc o problema tehnica!';
                            console.log('error change qty');
                        });
                },
                // move product to wish list
                moveProductToWish(cartId) {
                    this.loading = true;
                    axios.post('/' + this.$lang + '/' + this.siteType + '/move-product-to-wish', {
                            cartId: cartId
                        })
                        .then(response => {
                            this.addAnimation();
                            bus.$emit('updateWishCounter', response.data.wishProducts.products.length);
                            this.updateItemsList(response.data.cartProducts);
                            this.loading = false;
                        })
                        .catch(e => {
                            // this.serverError = 'A avut loc o problema tehnica!';
                            console.log('error move to wishlist')
                        })
                },
                moveAllProductToWish(){
                    axios.post('/' + this.$lang + '/' + this.siteType + '/move-all-product-to-wish')
                        .then(response => {
                            bus.$emit('updateWishBox', response.data.wishProducts.products);
                            this.updateItemsList(response.data.cartProducts);
                            this.loading = false;
                            $('#checkStocks').modal('hide');
                            $('#checkStocksQty').modal('hide');
                            this.checkStock();
                        })
                        .catch(e => {
                            this.serverError = 'A avut loc o problema tehnica!';
                            console.log('error error move all to wish list');
                        });
                },
                checkStock(){
                    let notInStock = false;
                    let notInQty = false;

                    this.cartSubprods.forEach(function(entry){
                        if (entry.stock_qty == 0) {
                            $('#checkStocksQty').modal('show');
                            notInStock = true;
                            return notInStock
                        }

                        if (entry.qty == 0) {
                            $('#checkStocks').modal('show');
                            notInQty = true;
                            return notInStock;
                        }
                    });

                    if (notInStock == true) {
                        bus.$emit('notInStoc', true);
                    }else{
                        bus.$emit('notInStoc', false);
                    }

                    if (notInQty == true) {
                        bus.$emit('notInQty', true);
                    }else{
                        bus.$emit('notInQty', false);
                    }

                    return this.notInStoc = notInStock;
                },
                refreshPage(){
                    return location.reload();
                },
                addAnimation() {
                  document.getElementById("wish").classList.add("heartBeat");
                  setInterval(() => {
                    document.getElementById("wish").classList.remove("heartBeat");
                  }, 2000)
              },
              changeWarehouse(countryId){
                  axios.post('/' + this.$lang + '/' + this.siteType + '/change-country', {countryId: countryId})
                      .then(response => {
                          this.updateItemsList(response.data);
                          this.checkStock();
                      })
                      .catch(e => {
                          this.serverError = 'A avut loc o problema tehnica!';
                          console.log('error error change warehouse');
                      });
              },
        }
    }
</script>

<style media="screen">
    .productsInCart .bagFromFirefox{
        width: 100% !important;
    }
    .modal .modalContent{
        padding: 30px 20px !important;

    }
    .modal .closeModal{
        right: -21px !important;
        top: -35px !important;
    }
</style>
