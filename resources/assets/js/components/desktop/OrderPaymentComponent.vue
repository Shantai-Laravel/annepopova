<template>

    <form class="row shippingInformation">
        <div class="col-12 titleCeck">
            <p>{{ trans.vars.Orders.paymentMethod }}</p>
            <span>{{ trans.vars.Orders.step }} 2 of 3</span>
        </div>
        <div class="col-12 shippingBloc" v-if="countrydelivery !== 140">
          <div class="row paymentMethodContainer">
              <div class="col-4">
                  <div class="paymentMethod" @click="cashOnDelivery">
                      <div class="checkMethod"></div>
                      <div>
                        <p>Cash on Delivery</p>
                      </div>
                  </div>
              </div>
              <div class="col-4">
                  <div class="paymentMethod" @click="setPayment('paypal')">
                      <div class="checkMethod"></div>
                      <div>
                        <p>PayPal</p>
                        <span>
                          <img class="paypalnew" src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png" border="0" alt="PayPal Logo" />
                        </span>
                      </div>
                  </div>
              </div>
              <div class="col-4">
                  <div class="paymentMethod" @click="selectPaymentPaydo()">
                  <div class="checkMethod"></div>
                  <div>
                    <p>Credit Card</p>
                    <span>
                      <svg width="256px" height="83px" viewBox="0 0 256 83" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid">
                          <defs>
                              <linearGradient x1="45.9741966%" y1="-2.00617467%" x2="54.8768726%" y2="100%" id="linearGradient-1">
                                  <stop stop-color="#222357" offset="0%"></stop>
                                  <stop stop-color="#254AA5" offset="100%"></stop>
                              </linearGradient>
                          </defs>
                          <g>
                              <path d="M132.397094,56.2397455 C132.251036,44.7242808 142.65954,38.2977599 150.500511,34.4772086 C158.556724,30.5567233 161.262627,28.0430004 161.231878,24.5376253 C161.17038,19.1719416 154.805357,16.804276 148.847757,16.7120293 C138.454628,16.5505975 132.412467,19.5178668 127.607952,21.7625368 L123.864273,4.24334875 C128.684163,2.02174043 137.609033,0.084559486 146.864453,-7.10542736e-15 C168.588553,-7.10542736e-15 182.802234,10.7236802 182.879107,27.3511501 C182.963666,48.4525854 153.69071,49.6210438 153.890577,59.05327 C153.959762,61.912918 156.688728,64.964747 162.669389,65.7411565 C165.628971,66.133205 173.800493,66.433007 183.0636,62.1665965 L186.699658,79.11693 C181.718335,80.931115 175.314876,82.6684285 167.343223,82.6684285 C146.895202,82.6684285 132.512402,71.798691 132.397094,56.2397455 M221.6381,81.2078555 C217.671491,81.2078555 214.327548,78.8940005 212.836226,75.342502 L181.802894,1.24533061 L203.511621,1.24533061 L207.831842,13.1835926 L234.360459,13.1835926 L236.866494,1.24533061 L256,1.24533061 L239.303345,81.2078555 L221.6381,81.2078555 M224.674554,59.6067505 L230.939643,29.5804456 L213.781755,29.5804456 L224.674554,59.6067505 M106.076031,81.2078555 L88.9642665,1.24533061 L109.650591,1.24533061 L126.754669,81.2078555 L106.076031,81.2078555 M75.473185,81.2078555 L53.941265,26.7822953 L45.2316377,73.059396 C44.2092367,78.2252115 40.1734431,81.2078555 35.6917903,81.2078555 L0.491982464,81.2078555 L0,78.886313 C7.22599245,77.318119 15.4359498,74.7890215 20.409585,72.083118 C23.4537265,70.4303645 24.322383,68.985166 25.3217224,65.0569935 L41.8185094,1.24533061 L63.68098,1.24533061 L97.1972855,81.2078555 L75.473185,81.2078555" fill="url(#linearGradient-1)" transform="translate(128.000000, 41.334214) scale(1, -1) translate(-128.000000, -41.334214) "></path>
                          </g>
                      </svg>

                      <img class="master" src="/images/mc_symbol.svg" alt="">
                    </span>
                  </div>
                  </div>
              </div>
          </div>
        </div>
        <div class="col-12 shippingBloc" v-else>
            <div class="row paymentMethodContainer">
                <div class="col-lg-3 col-md-6">
                    <div class="paymentMethod" @click="cashOnDelivery">
                        <p>Cash on Delivery</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="paymentMethod" @click="maibCardsPayment">
                        <p>VisaMastercard</p>
                        <span
                            ><img src="/fronts/img/payment-methods/mastercart.svg" alt="payment"/><img
                            src="/fronts/img/payment-methods/visa.svg"
                            alt="payment"
                            /></span>
                    </div>
                </div>
                <div class="col-auto">
                    <label class="checkContainer" v-if="mode == 'auth'">
                        <input type="checkbox" name="color" v-model="defaultPayment"/>{{ trans.vars.Payment.saveIt }}
                        <span class="check"></span>
                    </label>
                </div>
                <div class="alertUser  text-center" v-if="validateError">
                    <div class="closeAlert" @click="dismisAllert"></div>
                    <p class="text-danger">{{ validateError }}</p>
                </div>
            </div>
        </div>
    </form>

</template>

<script>
import {bus} from '../../app.js'

export default {
  props: ['items', 'mode', 'order_id', 'site', 'countrydelivery'],
  data() {
    return {
        credentials: {
            sandbox: '<Aav5tFuYbs3HnBKmYSvYVyfixocvWtXqqH9aI1gbbBWgofAsA5P1uEGTmVnJDqvovp_eRo0h_wCywzzY>',
            production: 'AcsdVkFUJELrIYwAa8X_wUnWrgIFNfNRrrXHasyJWGD0i-me9iuUpU1ctMQZDwQGTTgQwWgOOud3_RWd'
        },
        ready: true,
        validateError: false,
        serverError: false,

        payments: [],
        mainPayment: [],
        choosePayment: false,
        defaultPayment: false,

        cartData: [],
        amount: '0.00',
        lookOrderBtn: false,
        paymentDriver: 'paydo',
        step: 0,
    }
  },
  mounted() {
      this.getPayments();

      bus.$on('getCartData', data => {
          this.cartData = data;
          this.amount = this.cartData.amount;
      });

      bus.$on('pay', data => {
          this.pay();
      });
  },
  methods: {
      dismisAllert(){
          this.validateError = false;
      },
      getPayments(){
          axios.post('/' + this.$lang + '/' + this.site + '/order-get-payments')
              .then(response => {
                  this.payments = response.data;
              })
              .catch(e => {
                  this.serverError = 'A avut loc o problema tehnica!';
                  console.log('error load payments')
              })
      },
      pay(){
          let vm = this;
          bus.$emit('cartData');
          this.validate();

          if (!this.validateError) {
              if (this.lookOrderBtn === false) {
                  this.lookOrderBtn = true;
                  bus.$emit('ga-event-addPAymentInfo', this.choosePayment);
                  window.location.href = window.location.origin + '/' + this.$lang + '/' + this.site + '/order/payment/methods/' + this.choosePayment + '/' + this.amount + '/' + this.order_id + '/' + this.paymentDriver;
              }
          }
          setTimeout(function(){
              vm.dismisAllert();
          }, 5000);
      },
      order(){
          let vm = this;
          bus.$emit('cartData');
          this.validate();

          if (!this.validateError) {

              if (this.lookOrderBtn === false) {
                  this.lookOrderBtn = true;
                  axios.post('/' + this.$lang + '/order-shipping', {
                      payment : this.choosePayment,
                  })
                  .then(response => {
                      window.location.href = window.location.origin + '/' + this.$lang + '/order/payment/' + response.data;
                  })
                  .catch(e => {
                      this.serverError = 'A avut loc o problema tehnica!';
                      console.log('error')
                  })
              }
          }
          setTimeout(function(){
              vm.dismisAllert();
          }, 5000);
      },
      validate(){
          let error = false;
          if (this.choosePayment === false) {
              error = "Choose payment method!";
          }
          this.validateError = error;
      },
      selectPayment(){
          this.paymentDriver = "payop";
          this.step = this.step + 1;
      },
      selectPaymentPaydo(){
          this.choosePayment = 1;
          this.paymentDriver = "paydo";
          this.step = this.step + 1;
      },
      setPayment(payment){
          this.choosePayment = 0;
          this.paymentDriver = payment;
          this.step = this.step + 1;
      },
      maibCardsPayment(){
          this.choosePayment = 0;
          this.paymentDriver = "maib";
          this.step = this.step + 1;
      },
      cashOnDelivery(){
          this.choosePayment = 0;
          this.paymentDriver = "cash";
          this.step = this.step + 1;
      }
  },

}
</script>

<style media="screen">
    .cart .butt.buttView{
        margin-right: auto;
    }
    .hide{
        display: none;
    }
    .tabBox .tabOption {
        position: relative;
        display: block;
        width: 25%;
        margin-left: 10px;
        cursor: pointer;
    }
    .tabBox .tabHeader {
        position: relative;
        display: flex;
    }
    .tabBox {
        margin-bottom: 30px;
    }
</style>
