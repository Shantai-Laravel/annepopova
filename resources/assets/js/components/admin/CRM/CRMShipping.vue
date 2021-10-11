<template lang="html">

    <div class="" v-if="user">
        <div class="col-md-12 cart-heading">
            <h5>Shipping information:</h5>
        </div>

        <div class="col-md-9 accordion-area">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="">Contact name</label>
                    <input type="text" v-model="name=user.name+' '+user.surname" class="form-control">
                </div>
                <div class="col-md-6 form-group">
                    <label for="">Email</label>
                    <input type="text" v-model="email=user.email" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="">Code</label>
                    <v-select :options="countries" :reduce="country => country.phone_code"  label="phone_code" v-model="code">
                        <template slot="country" slot-scope="country">
                            <span class="fa" :class="country.flag"></span>
                            {{ country.iso }}
                        </template>
                    </v-select>
                </div>
                <div class="col-md-3">
                    <label for="">Mobile</label>
                    <div class="input-group">
                        <input type="number" v-model="phone=user.phone" class="form-control">
                    </div>
                </div>

                <div class="col-md-3 form-group">
                    <label for="">Country</label>
                    <select class="select-costum" v-model="userCountry" @change="changeCountry">
                        <option :value="country.id" v-for="country in countries">{{ country.translation.name }}</option>
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="">Delivery Method</label>
                    <select class="select-costum" v-model="userDelivery" @change="changeDelivery">
                        <option :value="delivery.delivery_id" v-for="delivery in deliveries">{{ delivery.delivery.translation.name }}</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="">Region</label>
                    <input type="text" v-model="region" class="form-control">
                </div>
                <div class="col-md-6 form-group">
                    <label for="">City</label>
                    <input type="text" v-model="city" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="">Street Address</label>
                    <input type="text" v-model="streetAddress" class="form-control">
                </div>
                <div class="col-md-3 form-group">
                    <label for="">Apartament,suite<small>(optional)</small></label>
                    <input type="number" v-model="apartament" class="form-control">
                </div>
                <div class="col-md-3 form-group">
                    <label for="">Zip/Postal Code</label>
                    <input type="number" v-model="zip" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="">Comment</label>
                    <textarea v-model="comment" class="form-control"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="default">
                        <input type="checkbox" v-model="setDefault" id="default">
                        <span>Set as defult</span>
                    </label>
                </div>
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
            name : '',
            email : '',
            phone: '',
            code: '',
            countries: [],
            userCountry: 0,
            currentCountry: [],
            deliveries: [],
            userDelivery: 0,
            currentDelivery: '',
            region: '',
            city: '',
            streetAddress: '',
            apartament: '',
            zip: '',
            comment: '',
            setDefault: false,
        }
    },
    mounted(){
        bus.$on('chooseUser', data => {
            this.getCountriesList();
            this.user = data;
            this.deliveries = this.user.country.deliveries;
            this.code = this.user.country.phone_code;
            this.userCountry = this.user.country_id;
            this.userDelivery = this.user.country.main_delivery ? this.user.country.main_delivery.delivery_id : 0;
            if (this.user.address) {
                this.region = this.user.address.region;
                this.city = this.user.address.location;
                this.streetAddress = this.user.address.address;
                this.apartament = this.user.address.homenumber;
                this.zip = this.user.address.code;
            }
        });
        bus.$on('getOrderData', data => {
            bus.$emit('sendShippingData', {
                name: this.name,
                email: this.email,
                code: this.code,
                phone: this.phone,
                countryId: this.userCountry,
                deliveryId: this.userDelivery,
                region: this.region,
                city: this.city,
                streetAddress: this.streetAddress,
                apartament: this.apartament,
                zip: this.zip,
                comment: this.comment,
                setDefault: this.setDefault,
            });
        });
    },
    methods: {
        getCountriesList(){
            axios.post('/back/crm-get-countries-list')
                .then(response => {
                    this.countries = response.data;
                })
                .catch(e => { console.log('error load countries') });
        },
        changeCountry(){
            axios.post('/back/crm-get-country', {countryId: this.userCountry})
                .then(response => {
                    this.currentCountry = response.data;
                    bus.$emit('changeCountry', this.currentCountry);
                    this.deliveries = this.currentCountry.deliveries;
                    this.userDelivery = this.currentCountry.main_delivery ? this.currentCountry.main_delivery.delivery_id : 0;
                })
                .catch(e => { console.log('error load currenct country') });
        },
        changeDelivery(){
            axios.post('/back/crm-get-delivery', {deliveryId: this.userDelivery})
                .then(response => {
                    this.currentDelivery = response.data;
                    bus.$emit('changeDelivery', this.currentDelivery);
                })
                .catch(e => { console.log('error load currenct country') });
        }
    },

}
</script>
