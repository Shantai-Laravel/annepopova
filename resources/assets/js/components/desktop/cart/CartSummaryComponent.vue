<template>

    <div style="position:relative" v-if="cartProds.length || cartSubprods.length">
        <div class="orderSummary">
            <div>
                <div class="title">{{ trans.vars.Cart.orderSummary }}</div>
                <div class="bloc" v-if="mode != 'order-shipping' && mode != 'order-payment'">
                    <span>{{ trans.vars.Cart.shipTo }}:</span>
                    <div class="selectContainer">
                        <select v-model="currentCountry" @change="changeCountry">
                            <option :value="country" v-for="country in countries">{{ country.translation.name }}</option>
                        </select>
                        <div class="bloc" v-if="mode == 'order-shipping' && 'order-payment'">
                            <div>{{ trans.vars.Shipping.shipping }}:</div>
                            <span v-if="currentCountry.translation">{{ currentCountry.translation.name }}</span>
                        </div>
                        <span>
                            <svg width="10px" height="6px" viewBox="0 0 10 6" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                <g id="AnaPopova-Site" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                                    <g id="Cos._APL---" transform="translate(-1592.000000, -545.000000)" fill="#42261D" fillRule="nonzero">
                                        <g id="Order-summery" transform="translate(1233.000000, 423.000000)">
                                            <g id="Ship" transform="translate(15.000000, 108.000000)">
                                                <polygon id="Shape" transform="translate(349.000000, 17.000000) scale(1, -1) translate(-349.000000, -17.000000) " points="349 14 344 20 348.763602 20 354 20"></polygon>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="bloc">
                    <span>{{ trans.vars.Cart.ship }}:</span>
                    <div class="selectContainer">
                        <select v-model="mainDelivery" @change="changeShippingMethod">
                            <option :value="delivery.delivery_id" :data-price="delivery.delivery.price" :data-time="delivery.delivery.delivery_time" v-for="delivery in currentCountry.deliveries">{{ delivery.delivery.translation.name }}</option>
                        </select>
                        <span>
                            <svg width="10px" height="6px" viewBox="0 0 10 6" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                <g id="AnaPopova-Site" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                                    <g id="Cos._APL---" transform="translate(-1592.000000, -545.000000)" fill="#42261D" fillRule="nonzero">
                                        <g id="Order-summery" transform="translate(1233.000000, 423.000000)">
                                            <g id="Ship" transform="translate(15.000000, 108.000000)">
                                                <polygon id="Shape" transform="translate(349.000000, 17.000000) scale(1, -1) translate(-349.000000, -17.000000) " points="349 14 344 20 348.763602 20 354 20"></polygon>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="bloc" v-if="currentCountry.main_delivery">
                    <span>
                        {{ trans.vars.Shipping.shippingTime }}
                    </span>
                    <span>
                        {{ shippingTime }} {{ trans.vars.Shipping.days }}
                    </span>
                </div>

                <div class="bloc" v-if="!currentCountry.main_delivery">
                    <span>{{ trans.vars.Shipping.shipping }}:</span>
                    <span>
                        no shipping for this country
                    </span>
                </div>
                <div class="promoCode">
                    <label htmlFor="pomoCode">{{ trans.vars.Promocode.promocodeAdd }}:</label>
                    <div class="bloc">
                        <input type="text" v-model="promocode"/>



                        <button @click="applyPromocode">{{ trans.vars.Promocode.apply }}</button>
                        <div class="error">{{ trans.vars.Promocode.promoCodeNotValid }}</div>
                    </div>
                </div>
                <div class="bloc total">
                    <span>{{ trans.vars.Orders.orderSubtotal }}:</span>
                    <span>{{ parseFloat(amount).toFixed(2) }} {{ $mainCurrency }}</span>
                </div>
                <div class="bloc total">
                    <span>{{ trans.vars.Shipping.shipping }}: </span>
                    <span v-if="shippingPrice">
                        {{ shippingPrice }} {{ $mainCurrency }}
                    </span>
                    <span v-else>
                        no shipping for this country
                    </span>
                </div>
                <div class="bloc last">
                    <span class="title">{{ trans.vars.Orders.orderTotal }}</span>
                    <span>{{ parseFloat(parseFloat(amount) + parseFloat(shippingPrice)).toFixed(2) }} {{ $mainCurrency }}</span>
                </div>
                <div class="bloc total" v-if="$currency !== $mainCurrency && $mainCurrency !== 'MDL'">
                    <span class="title">{{ trans.vars.Orders.ordersApproximately }}</span>
                    <span>{{ parseFloat(parseFloat(amountPersonalCurrecy) + parseFloat(aproximativShippingPrice)).toFixed(2) }} {{ $currency }}</span>
                </div>
                <span v-if="mode != 'order-shipping' && mode != 'order-payment'">
                    <span v-if="notInStoc">
                        <a data-toggle="modal" data-target="#checkStocksQty" href="#"><button class="onSubmit">{{ trans.vars.Orders.order }}</button></a>
                    </span>
                    <span v-if="notInQty">
                        <a data-toggle="modal" data-target="#checkStocks" href="#"><button class="onSubmit">{{ trans.vars.Orders.order }}</button></a>
                    </span>
                    <span v-if="!notInQty && !notInStoc">
                        <a :href="'/' + $lang + '/order'" v-if="mode == 'cart'" @click="setGuest"><button class="onSubmit">{{trans.vars.Orders.order}}</button></a>
                        <a href="#" v-else data-toggle="modal" data-target="#auth" @click="setGuest"><button class="onSubmit">{{trans.vars.Orders.order}}</button></a>
                    </span>
                </span>
                <span v-if="mode == 'order-shipping'">
                    <span v-if="notInStoc">
                        <a data-toggle="modal" data-target="#checkStocksQty" href="#"><button class="onSubmit">{{ trans.vars.Orders.order }}</button></a>
                    </span>
                    <span v-if="notInQty">
                        <a data-toggle="modal" data-target="#checkStocks" href="#"><button class="onSubmit">{{ trans.vars.Orders.order }}</button></a>
                    </span>
                    <span v-if="!notInQty && !notInStoc">
                        <a href="#" @click.prevent="continueToPayment"><button class="onSubmit">{{ trans.vars.Orders.continueBtn }}</button></a>
                    </span>
                </span>
                <span v-if="mode == 'order-payment'">
                    <span v-if="notInStoc">
                        <a data-toggle="modal" data-target="#checkStocksQty" href="#"><button class="onSubmit">{{ trans.vars.Orders.order }}</button></a>
                    </span>
                    <span v-if="notInQty">
                        <a data-toggle="modal" data-target="#checkStocks" href="#"><button class="onSubmit">{{ trans.vars.Orders.order }}</button></a>
                    </span>
                    <span v-if="!notInQty && !notInStoc">
                        <a href="#" @click.prevent="pay"><button class="onSubmit">{{ trans.vars.Orders.payBtn }}</button></a>
                    </span>
                </span>
            </div>
            <div>
                <p><a :href="'/' + $lang + '/' + site + '/livrare-achitare-retur'">{{ trans.vars.Shipping.shipping }}</a> {{ trans.vars.Cart.shippingTerm }}</p>
                <p><a :href="'/' + $lang + '/' + site + '/refund'">{{ trans.vars.Cart.return }}</a> {{ trans.vars.Cart.inDays }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    import { bus } from '../../../app';

    export default {
        props: ['prods', 'subprods', 'mode', 'site'],
        data() {
            return {
                cartProds: this.prods,
                cartSubprods: this.subprods,
                amount: 0,
                amountWithoutDiscount: 0,
                amountPersonalCurrecy: 0,
                delivery: 50,
                promocode: '',
                promocodeDetails: {},
                countries: [],
                currentCountry: [],
                mainDelivery: 0,
                shippingPrice: 0,
                originalShippingPrice: 0,
                shippingTime: 0,
                aproximativShippingPrice: 0,
                agreeCond: false,
                notInStoc: false,
                notInQty: false,
                ready: false,
                initiateCheckout: false,
            }
        },
        mounted() {
            this.checkPromocode();
            this.checkStock();
            this.loadCountries();
            // this.countAmount();
            // this.countPersonalAmount();

            bus.$on('notInStoc', data => {
                this.notInStoc = data;
            })
            bus.$on('notInQty', data => {
                this.notInQty = data;
            })

            bus.$on('changeCountry', data => {
                this.currentCountry = data;
                this.mainDelivery =  data.main_delivery ? data.main_delivery.delivery_id : 0;
            })

            bus.$on('refreshSummary', data => {
                this.cartProds = data.products;
                this.cartSubprods = data.subproducts;

                this.countAmount();
                this.countPersonalAmount();
                this.checkPromocode();
            })

            // Request from order
            bus.$on('cartData', data => {
                bus.$emit('getCartData', {
                    amount      : parseFloat(this.amount) + parseFloat(this.shippingPrice), // this.amount,
                    delivery    : this.mainDelivery,
                    currency    : this.$currency,
                    promocode   : this.promocode,
                    tax         : parseFloat(this.amount) * parseFloat(this.currentCountry.vat),
                    agreeCond   : this.agreeCond,
                });
            })
        },
        methods: {
            pay(){
                bus.$emit('pay', {products: this.cartProds, subproducts: this.cartSubprods});
            },
            continueToPayment(){
                bus.$emit('continueToPayment');
                if (!this.addShippingInfo) {
                    this.addShippingInfo = true;
                    // bus.$emit('ga-event-addShippingInfo', this.currentCountry);
                    bus.$emit('ga-event-addShippingInfo', {country: this.currentCountry, products: this.cartProds, subproducts: this.cartSubprods});

                }
            },
            loadCountries(){
                axios.post('/' + this.$lang + '/' + this.site +'/get-countries')
                    .then(response => {
                        this.countries = response.data.countries;
                        this.currentCountry = response.data.currentCountry;
                        this.mainDelivery = response.data.currentCountry.main_delivery ? response.data.currentCountry.main_delivery.delivery_id : 0;
                        this.shippingPrice = this.currentCountry.main_delivery ? this.currentCountry.main_delivery.price : false;
                        this.originalShippingPrice = this.currentCountry.main_delivery ? this.currentCountry.main_delivery.price : false;
                        this.shippingTime = this.currentCountry.main_delivery ? this.currentCountry.main_delivery.delivery_time : 0;
                        this.ready = true;
                        this.exchangeShippingPrice(this.originalShippingPrice);
                    })
                    .catch(e => {
                        this.serverError = 'A avut loc o problema tehnica!';
                        console.log('error load countries');
                    })
            },
            // count amount method
            countAmount() {
                let ammount = 0;
                if (typeof this.cartProds !== "undefined") {
                    this.cartProds.forEach(function(entry){
                        if (entry.stock_qty > 0) {
                            ammount += entry.product.main_price.price * entry.qty;
                        }
                    });
                }

                if (typeof this.cartSubprods !== "undefined") {
                    this.cartSubprods.forEach(function(entry){
                        if (entry.stock_qty > 0) {
                            ammount += entry.subproduct.product.main_price.price * entry.qty;
                        }
                    });
                }

                if (this.promocodeDetails.discount) {
                    this.amount = ammount - (ammount * this.promocodeDetails.discount / 100);
                    this.amountWithoutDiscount = ammount;
                    return true;
                }

                this.amount = ammount;
                this.amountWithoutDiscount = ammount;
            },
            // count personal currency amount method
            countPersonalAmount() {
                var amount = 0;
                if (typeof this.cartProds !== "undefined") {
                    this.cartProds.forEach(function(entry){
                        if (entry.stock_qty > 0) {
                            amount += entry.product.personal_price.price * entry.qty;
                            console.log(entry.product.personal_price.price);
                        }
                    });
                }
                if (typeof this.cartSubprods !== "undefined") {
                    this.cartSubprods.forEach(function(entry){
                        if (entry.stock_qty > 0) {
                            amount += entry.subproduct.product.personal_price.price * entry.qty;
                            console.log(entry.subproduct.product.personal_price.price);
                        }
                    });
                }

                if (this.promocodeDetails.discount) {
                    this.amountPersonalCurrecy = amount - (amount * this.promocodeDetails.discount / 100);
                    return true;
                }
                this.amountPersonalCurrecy = amount;
                this.exchangeShippingPrice(this.originalShippingPrice);
            },

            // check if promocode is valid on load component
            checkPromocode() {
                axios.post('/' + this.$lang + '/' + this.site +'/check-promocode', {amount: this.amount})
                    .then(response => {
                        if (response.data != 'false') {
                            this.promocodeDetails = {
                                'body': response.data.body,
                                'discount': response.data.discount,
                                'status': response.data.status,
                            };
                            this.promocode = response.data.name;
                            if (response.data.status == 'true') {
                                this.load();
                            }
                            this.countAmount();
                            this.countPersonalAmount();
                        }
                    })
                    .catch(e => {
                        this.serverError = 'A avut loc o problema tehnica!';
                        console.log('error check promocode')
                    })
            },
            // apply and check a new promocode from input
            applyPromocode() {
                this.promocodeCheck = true;
                axios.post('/' + this.$lang + '/' + this.site +'/apply-promocode', {
                        promocode: this.promocode,
                        amount: this.amountWithoutDiscount,
                    })
                    .then(response => {
                        this.promocodeDetails = {
                            'body': response.data.body,
                            'discount': response.data.discount,
                            'status': response.data.status,
                        };
                        this.countAmount();
                        this.countPersonalAmount();
                    })
                    .catch(e => {
                        this.serverError = 'A avut loc o problema tehnica!';
                        console.log('error aply promocode')
                    })
            },
            removePromocode(){
                this.promocode = '';
                this.applyPromocode();
            },
            setDeliveryCountry(){ //delete
                axios.post('/' + this.$lang + '/' + this.site +'/change-product-qty', {
                        cartId: e.target.name,
                        qty: e.target.value,
                    })
                    .then(response => {
                        this.loading = false;
                    })
                    .catch(e => {
                        this.serverError = 'A avut loc o problema tehnica!';
                        console.log('error set delivery country');
                    });
            },
            convertFloat(amount){
                return amount.toFixed(2);
            },
            setGuest(){
                bus.$emit('changeWarehouse', this.currentCountry.id);
                bus.$emit('setGuest');
                axios.post('/' + this.$lang + '/' + this.site +'/set-country-delivery', {
                        country: this.currentCountry.id,
                        delivery: this.mainDelivery,
                    })
                    .then(response => {
                        if (!this.initiateCheckout) {
                            this.initiateCheckout = true;
                            bus.$emit('ga-event-initiateCheckout', {
                                promo: false,
                                products: {prods: this.cartProds, subprods: this.cartSubprods},
                                amount: this.amount});
                        }
                    })
                    .catch(e => {
                        this.serverError = 'A avut loc o problema tehnica!';
                        console.log('set guest user');
                    });
            },
            changeShippingMethod(e){
                if (e.target.options.selectedIndex > -1) {
                    const theTarget = e.target.options[e.target.options.selectedIndex].dataset;
                    this.shippingPrice = theTarget.price;
                    this.originalShippingPrice = theTarget.price;
                }
                if (e.target.options.selectedIndex > -1) {
                    const theTarget = e.target.options[e.target.options.selectedIndex].dataset;
                    this.shippingTime = theTarget.time
                }
                this.exchangeShippingPrice(this.originalShippingPrice);
            },
            changeCountry(){
                this.shippingPrice = this.currentCountry.main_delivery ? this.currentCountry.main_delivery.price : false;
                this.originalShippingPrice = this.currentCountry.main_delivery ? this.currentCountry.main_delivery.price : false;

                this.shippingTime = this.currentCountry.main_delivery ? this.currentCountry.main_delivery.delivery_time : 0;
                this.mainDelivery = this.currentCountry.main_delivery ? this.currentCountry.main_delivery.delivery_id : 0;

                this.exchangeShippingPrice(this.originalShippingPrice);

                bus.$emit('changeWarehouse', this.currentCountry.id);
            },
            checkStock(){
                let notInStock = false;
                if (typeof this.cartProds !== "undefined") {
                    this.cartProds.forEach(function(entry){
                        if (entry.stock_qty == 0) {
                            bus.$emit('addAlertMessage', trans.front.cart.notInStock)
                            notInStock = true;
                        }
                        return notInStock;
                    });
                }

                if (typeof this.cartSubprods !== "undefined") {
                    this.cartSubprods.forEach(function(entry){
                        if (entry.stock_qty == 0) {
                            bus.$emit('addAlertMessage', trans.front.cart.notInStock)
                            notInStock = true;
                        }
                        return notInStock;
                    });
                }

                return this.notInStoc = notInStock;
            },
            exchangeShippingPrice(price){
                axios.post('/' + this.$lang + '/' + this.site +'/exchange-shipping-price', {
                        currency: this.$currency,
                        price : price,
                    })
                    .then(response => {
                        this.aproximativShippingPrice = response.data;

                        if (this.$currency == 'MDL') {
                            this.shippingPrice = response.data;
                        }
                    })
                    .catch(e => {
                        this.serverError = 'A avut loc o problema tehnica!';
                        console.log('error exchange shpping price')
                    })
            },
        }
    }
</script>
