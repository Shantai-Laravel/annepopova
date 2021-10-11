<template>

    <form :class="['row', 'shippingInformation', editableAddress ? 'show' : 'show']">
        <div class="titleCeck">
            <p>{{ trans.vars.Shipping.shipping }} </p>
            <span>{{ trans.vars.Orders.step }} 1 of 3</span>
        </div>
        <div class="col-12 shippingBloc">
            <p class="subTitle">{{ trans.vars.Shipping.shippingContact }}</p>
            <div class="row">
                <div class="col-md-6">
                    <label for="name">{{ trans.vars.FormFields.fieldFullName }} <b>*</b></label>
                    <input type="text" id="name" v-model="name=user.name"/>
                </div>
                <div class="col-md-6">
                    <label for="email">{{ trans.vars.FormFields.fieldEmail }} <b>*</b></label>
                    <input type="text" id="email" v-model="email=user.email"/>
                </div>
                <div class="col-md-6">
                    <div class="phoneContainer">
                        <label>{{ trans.vars.FormFields.fieldphone }} <b>*</b></label>
                        <input type="text" id="tel" v-model="phone=user.phone"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 shippingBloc" v-if="userAddress">
            <p class="subTitle">{{ trans.vars.FormFields.contactPopupHeading }}</p>
            <div class="row">
                <div class="col-md-6">
                    <label for="street">{{ trans.vars.FormFields.fieldStreet }} <b>*</b></label>
                    <input type="text" id="street" v-model="address=userAddress.address"/>
                </div>
                <div class="col-md-6">
                    <label for="flat">{{ trans.vars.FormFields.fieldFullApartment }}</label>
                    <input type="text" id="flat" v-model="apartment=userAddress.homenumber"/>
                </div>
                <div class="col-md-4">
                    <div class="countryContainer">
                        <label for="country">{{ trans.vars.FormFields.fieldCountry }} <b>*</b></label>
                        <select v-model="country" @change="changeCountry">
                            <option :value="country.id" v-for="country in countries">{{ country.translation.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="region">{{ trans.vars.FormFields.fieldFullRegion }}</label>
                    <input type="text" id="region" v-model="region=userAddress.region"/>
                </div>
                <div class="col-md-4">
                    <label for="city">{{ trans.vars.FormFields.fieldCity }} <b>*</b></label>
                    <input type="text" id="city" v-model="city=userAddress.location"/>
                </div>
                <div class="col-md-6">
                    <label for="zipCode">{{ trans.vars.FormFields.fieldPostcode }}</label>
                    <input type="text" id="zipCode" v-model="zip=userAddress.code"/>
                </div>
                <div class="col-12 text-center" v-if="validateError">
                    <p class="text-danger">{{ validateError }}</p>
                </div>
                <div class="col-12"></div>
            </div>
        </div>
        <div class="col-12 shippingBloc" v-else>
            <p class="subTitle">{{ trans.vars.FormFields.contactPopupHeading }}</p>
            <div class="row">
                <div class="col-md-6">
                    <label for="street">{{ trans.vars.FormFields.fieldStreet }} <b>*</b></label>
                    <input type="text" id="street" v-model="address"/>
                </div>
                <div class="col-md-6">
                    <label for="flat">{{ trans.vars.FormFields.fieldFullApartment }}</label>
                    <input type="text" id="flat" v-model="apartment"/>
                </div>
                <div class="col-md-4">
                    <div class="countryContainer">
                        <label for="country">{{ trans.vars.FormFields.fieldCountry }} <b>*</b></label>
                        <select v-model="country" @change="changeCountry">
                            <option :value="country.id" v-for="country in countries">{{ country.translation.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="region">{{ trans.vars.FormFields.fieldFullRegion }}</label>
                    <input type="text" id="region" v-model="region"/>
                </div>
                <div class="col-md-4">
                    <label for="city">{{ trans.vars.FormFields.fieldCity }} <b>*</b></label>
                    <input type="text" id="city" v-model="city"/>
                </div>
                <div class="col-md-6">
                    <label for="zipCode">{{ trans.vars.FormFields.fieldPostcode }}</label>
                    <input type="text" id="zipCode" v-model="zip"/>
                </div>
                <div class="col-12 text-center" v-if="validateError">
                    <p class="text-danger">{{ validateError }}</p>
                </div>
                <div class="col-12"></div>
            </div>
        </div>
    </form>

</template>

<script>
import {bus} from '../../app.js'

export default {
  props: ['items', 'mode', 'prods', 'subprods', 'sets', 'site'],
  data() {
    return {
        ready: true,
        serverError: false,
        user: [],
        userAddress: false,

        countries: [],
        currentCountry: '',

        name: '',
        code: '',
        phone: '',
        email: '',
        address: '',
        apartment: '',
        country: '',
        region: '',
        city: '',
        zip: '',
        setDefault: false,

        validateError: false,
        cartData: [],
        editableAddress: true,
        lookOrderBtn: false,
        step: 'order-shipping'
    }
  },
  mounted() {
      this.editableAddress = this.mode == 'guest' ? true : false;

      this.getUser();

      bus.$on('getCartData', data => {
          this.cartData = data;
      });

      bus.$on('updateCartBox', data => {
          this.checkCart(data);
      });

      bus.$on('continueToPayment', data => {
          this.order();
      });
  },
  methods: {
      dismisAllert(){
          this.validateError = false;
      },
      getUser(){
          axios.post('/' + this.$lang + '/' + this.site + '/order-get-user')
              .then(response => {
                  if (response.data.mode == 'auth') {
                      this.user = response.data.frontUser;
                      this.userAddress = response.data.frontUser.address;
                      if (this.userAddress) {
                          this.editableAddress = false;
                      }else{
                          this.editableAddress = true;
                      }
                  }else{
                      this.user = response.data.frontUser;
                      this.editableAddress = true;
                  }
                  this.country = response.data.country;
                  this.code = response.data.phone_code;
                  this.phone = response.data.phone;
                  // this.choosePayment = response.data.payment_id;
                  this.loadCountries();
              })
              .catch(e => {
                  this.serverError = 'A avut loc o problema tehnica!';
                  console.log('error load user')
              })
      },
      loadCountries(){
          axios.post('/' + this.$lang + '/' + this.site + '/get-countries')
              .then(response => {
                  this.countries = response.data.countries;
                  this.currentCountry = response.data.currentCountry;
                  // this.setPayments();
              })
              .catch(e => {
                  this.serverError = 'A avut loc o problema tehnica!';
                  console.log('error load countries')
              })
      },
      setPayments(){ // to Delete
          if (this.userAddress) {
              this.payments = this.userAddress.get_country_by_id.payments;
              this.mainPayment = 0;
          }else{
              this.payments = this.currentCountry.payments;
              this.mainPayment = 0;
          }
      },
      changeCountry(){
          axios.post('/' + this.$lang + '/' + this.site + '/order-change-country', {countryId: this.country})
              .then(response => {
                  this.currentCountry = response.data;
                  this.payments = this.currentCountry.payments;
                  bus.$emit('changeCountry', this.currentCountry);
                  bus.$emit('changeWarehouse', this.currentCountry.id);
              })
              .catch(e => {
                  this.serverError = 'A avut loc o problema tehnica!';
                  console.log('error')
              })
      },
      order(){
          bus.$emit('cartData');
          this.validate();
          if (!this.validateError) {

              if (this.lookOrderBtn === false) {
                  // this.lookOrderBtn = true;
                  axios.post('/' + this.$lang + '/' + this.site + '/order-shipping', {
                      name  : this.name,
                      email : this.email,
                      phone_code : this.code,
                      phone : this.phone,
                      country : this.country,
                      region    : this.region,
                      city  : this.city,
                      address : this.address,
                      apartment : this.apartment,
                      zip   : this.zip,
                      saveAddress: this.setDefault,
                      payment : this.choosePayment,
                      defaultPayment: this.defaultPayment,
                      cartData: this.cartData,
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
          let vm = this;
          setTimeout(function(){
              vm.dismisAllert();
          }, 5000);
      },
      confirmAddress(){
          this.validate('shipping');
          if (!this.validateError) {
              this.editableAddress = false;
          }
      },
      cancelConfirmAddress(){
          this.editableAddress = true;
      },
      checkCart(data){
          // if (data.products.length == 0 || data.sets.length == 0) {
          //     this.ready = false;
          // }else{
          //     this.ready = true;
          // }
      },
      validate(mode = 'all'){
          let error = false;

          if (mode == 'all') {
              // if (this.cartData.agreeCond == false) {
              //     error = trans.vars.Notifications.agreeRequired
              // }
              if (this.cartData.delivery.length < 1) {
                  error = 'delivery missing!'
              }
          }

         if (this.city === null) { error = "city missing!"; }
         else{ if (this.city.length < 1) { error = "city missing!"; } }

         if (this.country === null) { error = "country missing!"; }
         else{ if (this.country.length < 1) { error = "country missing!"; } }

         if (this.address === null) { error = "street missing!"; }
         else{ if (this.address.length < 1) { error = "street missing!"; } }

         if (this.phone === null) { error = trans.vars.Notifications.phoneRequired }
         else{ if (this.phone.length < 1) { error = trans.vars.Notifications.phoneRequired }}

         if (this.email === null) { error = trans.vars.Notifications.emailRequired }
         else{ if (this.email.length < 1) { error = trans.vars.Notifications.emailRequired } }

         if (this.name === null) { error = trans.vars.Notifications.nameRequired }
         else{ if (this.name.length < 1) { error = trans.vars.Notifications.nameRequired } }

         this.validateError = error;
      }
  }
}
</script>

<style media="screen">
    .cart .butt.buttView{
        margin-right: auto;
    }
    .hide{
        display: none;
    }
    .shippingInformation select{
        height: 44px;
        line-height: 44px;
        margin-bottom: 20px;
        width: 100%;
        background-color: transparent;
        border: 1px solid #42261d;
        border-radius: 0;
    }

</style>
