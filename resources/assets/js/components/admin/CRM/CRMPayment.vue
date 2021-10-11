<template lang="html">

    <div class="" v-if="user">
        <div class="col-md-12 cart-heading">
            <h5>Payment methods:</h5>
        </div>
        <div class="col-md-8 accordion-area">
            <div class="col-md-6" v-for="payment in payments">
                <div class="payment-method" @click="selectPayment" :id="payment.id" v-bind:class="{ active: paymentMethodActive == this.payment }">
                    <h6>{{ payment.translation.name }}</h6>
                    <img src="/admin/img/com6.png" alt="">
                </div>
            </div>
            <div class="col-md-12 text-center">
                <p class="text-danger" v-if="validateError">
                    {{ validateError }}
                </p>

                <div class="alert-message text-danger" v-if="stockError">
                    This Your final Order, according to our available stocks at this moment.
                    The items out of stock You can find in Your Favorites.
                    If You not agree, please press button "Decline" before the order is accepted. <br>
                    If You not Decline it, the order will be automatically accepted in following <b> {{ stockError }} </b> seconds.
                    <hr>
                    <div>
                        <button type="button" class="btn btn-primary" @click="declineOrder">Decline</button>
                        <button type="button" class="btn btn-primary" @click="getOrderData">Accept</button>
                    </div>
                </div>

                <button type="button" class="btn btn-primary" @click="getOrderData" v-if="!stockError">Order</button>
            </div>

        </div>

    </div>

</template>

<script>

import { bus } from '../../../app';

export default {
    data(){
        return {
            user : false,
            payments: [],
            payment: 0,
            userCountry: 0,
            validateError: false,
            cartData: [],
            shippingData: [],
            stockError: false,
            paymentMethodActive: 0,
        }
    },
    mounted(){
        bus.$on('chooseUser', data => {
            this.user = data;
            this.userCountry = this.user.country_id;
            this.getPaymentList();
        });

        bus.$on('changeCountry', data => {
            this.userCountry = data.id;
            this.getPaymentList();
        });

        bus.$on('sendShippingData', data => {
            this.shippingData = data;
            this.shippingValidate(data);
        });

        bus.$on('sendCartData', data => {
            this.cartData = data;
            this.cartValidate(data);
        });
    },
    methods: {
        getPaymentList(){
            axios.post('/back/crm-get-payments-list', {countryId: this.userCountry})
                .then(response => {
                    this.payments = response.data;
                })
                .catch(e => { console.log('error load payments') });
        },
        selectPayment(e){
            let id = e.target.id;
            // $('.payment-method').removeClass('payment-method-active');
            // $(e.target).addClass('payment-method-active');
            this.payment = id;
        },
        getOrderData(){
            this.validateError = false;
            bus.$emit('getOrderData');
            this.paymentValidate();
            if (this.stockError == false) {
                this.preorder();
            }else{
                this.order();
            }
        },
        preorder(){
            if (this.validateError == false) {
                axios.post('/back/crm-user-preorder', {cartData: this.cartData, shippingData: this.shippingData, payment: this.payment})
                    .then(response => {
                        bus.$emit('updateCartItems', response.data.carts);
                        bus.$emit('getOrderData');
                        this.paymentValidate();
                        if (response.data.status == 'false') {
                            this.stockError = 10;
                            let vm = this;
                            let countDown = setInterval(function() {
                                vm.stockError = vm.stockError - 1;
                                if (vm.stockError === 0) {
                                    clearInterval(countDown)
                                    vm.stockError = false;
                                }
                            }, 1000)
                        }else{
                            this.order();
                        }
                    })
                    .catch(e => { console.log('error load user order') });
            }
        },
        order(){
            console.log(this.shippingData);
            if (this.validateError == false) {
                axios.post('/back/crm-user-order', {cartData: this.cartData, shippingData: this.shippingData, payment: this.payment})
                    .then(response => {
                        window.location.href = window.location.origin + '/back/crm-orders-detail/' + response.data;
                    })
                    .catch(e => { console.log('error load user order') });
            }
        },
        declineOrder(){
            this.stockError = 1;
        },
        cartValidate(data){
            let error = false;
            if (data.taxPrice == 0) {
                 error = 'Shopping cart: tax missing!'
            }
            if (data.deliveryPrice == 0) {
                 error = 'Shopping cart: delivery missing!'
            }
            if (data.amount == 0) {
                 error = 'Shopping cart: add product to cart!'
            }
            if (data.userId == 0) {
                error = 'Shopping cart: choose user please!';
            }
            this.validateError = error;
        },
        shippingValidate(data){
            let error = false;
            if (data.zip.length < 1) {
                 error = 'Shipping information: Zip/Postal Code missing!'
            }
            if (data.streetAddress.length < 1) {
                 error = 'Shipping information: Street Address missing!'
            }
            if (data.city.length < 1) {
                 error = 'Shipping information: City missing!'
            }
            if (data.region.length < 1) {
                 error = 'Shipping information: Region missing!'
            }
            if (data.deliveryId == 0) {
                 error = 'Shipping information: Delivery method missing!'
            }
            if (data.countryId == 0) {
                 error = 'Shipping information: Country missing!'
            }
            if (data.phone.length < 1) {
                 error = 'Shipping information: Phone missing!'
            }
            if (data.code.length < 1) {
                 error = 'Shipping information: Code missing!'
            }
            if (data.name.length < 1) {
                error = 'Shipping information: Contact Name missing!'
            }
            this.validateError = error;
        },
        paymentValidate(){
            if (this.payment == 0) {
                this.validateError = 'Payment methods: Choose Payment Method!';
            }
        }

    },
}
</script>

<style media="screen">
    .alert-message{
        border: 1px solid #d9534f;
        border-radius: 5px;
        padding: 20px;
    }
</style>
