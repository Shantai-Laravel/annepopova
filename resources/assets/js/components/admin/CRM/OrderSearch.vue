<template lang="html">

    <div class="card-block">

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Search User</label>
                    <input type="text" class="form-control" v-model="userSearch" @keyup="searchUser" @click="showAllUsers">
                    <span class="close-search" @click="removeSearchUser"><i class="fa fa-close"></i></span>
                    <div class="search-absolute">
                        <div class="row userRow" v-for="user in users" @click="chooseUser(user)">
                            <div class="col-md-7">
                                {{ user.name }} {{ user.surname }}
                            </div>
                            <div class="col-md-5">
                                <small>{{ user.phone ? user.phone : user.email}}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-input">
                    <label for="">Search Product/Set</label>
                    <input type="text" class="form-control" v-model="productSearch" @keyup="searchProduct" :disabled="!user">
                    <span class="close-search" @click="removeSearchProduct"><i class="fa fa-close"></i></span>

                    <div class="search-absolute">

                        <div v-for="set in sets">
                            <div :class="['row', set.stock == 0 ? 'pasive' : '']">
                                <div class="col-md-1 text-center">
                                    <small><span class="label label-success">set</span></small>
                                </div>
                                <div class="col-md-6"> {{ set.translation.name }} </div>
                                <div class="col-md-2"> {{ set.code }} </div>
                                <div class="col-md-2"> {{ set.price.price }} EUR </div>
                                <div class="col-md-1">
                                    <a href="#" v-if="set.stock > 0" @click="setCurrenctSet(set)"><i class="fa fa-shopping-cart"></i></a>
                                </div>
                            </div><hr>
                        </div>

                        <div v-for="product in products">
                            <div :class="['row', subproduct.stoc == 0 ? 'pasive' : '']" v-if="product.subproducts.length > 0" v-for="subproduct in product.subproducts">
                                <div class="col-md-1 text-center">
                                    <small><span class="label label-warning">sub</span></small>
                                </div>
                                <div class="col-md-5">
                                    <i>{{ product.translation.name }}</i>
                                </div>
                                <div class="col-md-1">
                                    <span class="badge badge-default">{{ subproduct.parameter_value.translation.name  }}</span>
                                </div>
                                <div class="col-md-2">
                                    {{ subproduct.code }}
                                </div>
                                <div class="col-md-2">
                                    {{ subproduct.price.price }} EUR
                                </div>
                                <div class="col-md-1">
                                    <a href="#" v-if="subproduct.stoc > 0"  @click="addSubproductToCart(subproduct.id)"><i class="fa fa-shopping-cart"></i></a>
                                </div>
                            </div><hr>
                            <div :class="['row', product.stock == 0 ? 'pasive' : '']" v-if="product.subproducts.length == 0">
                                <div class="col-md-1 text-center">
                                    <small><span class="label label-primary">prod</span></small>
                                </div>
                                <div class="col-md-6">
                                    {{ product.translation.name }}
                                </div>
                                <div class="col-md-2">
                                    {{ product.code }}
                                </div>
                                <div class="col-md-2">
                                    {{ product.price.price }} EUR
                                </div>
                                <div class="col-md-1">
                                    <a href="#" v-if="product.stock > 0" @click="addProductToCart(product.id)"><i class="fa fa-shopping-cart"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 cart-heading" v-if="user">
                <h5>{{ user.name }} {{ user.surname }} shopping cart:</h5>
            </div>
        </div>

        <div class="modal-costum">
            <div class="modal-content">
                <div class="row" v-if="currentSet">
                    <div class="col-md-12">
                        <h4>{{ currentSet.translation.name }}</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-12 admin-cart" v-for="product in currentSet.products">
                            <div class="col-md-1">
                                <img src="/admin/img/noimage.jpg">
                            </div>
                            <div class="col-md-6">
                                {{ product.translation.name }}
                            </div>
                            <div class="col-md-2" v-if="product.subproducts.length > 0">
                                <select class="select-costum" v-model="subproductSets[product.id]=product.first_subproduct.id">
                                    <option v-for="subproduct in product.subproducts"
                                            v-if="subproduct.stoc !== 0"
                                            :value="subproduct.id">
                                            {{ subproduct.parameter_value.translation.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2" v-else>
                                <span class="label label-danger" v-if="product.stock == 0">stockout</span>
                                <span class="label label-primary" v-else>Product</span>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <button type="button" class="btn btn-primary" @click="addSetCart(currentSet.id)"><i class="fa fa-buy"></i> Add to cart</button>
                        <button type="button" class="btn btn-primary cloose-modal" @click="closeModal"> Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <crm-cart></crm-cart>

    </div>

</template>

<script>

import { bus } from '../../../app';

export default {
    data(){
        return {
            userSearch: '',
            productSearch: '',
            users: [],
            user: false,
            products: [],
            sets: [],
            currentSet: false,
            subproductSets: [],
        }
    },
    mounted(){

    },
    methods: {
        searchUser(){
            this.users = [];
            if (this.userSearch.length > 1) {
                axios.post('/back/crm-orders-search-users', {user_search: this.userSearch})
                    .then(response => {
                        this.users = response.data;
                    })
                    .catch(e => { console.log('error load users') });
            }
        },
        showAllUsers(){
            if (this.userSearch.length == 0) {
                axios.post('/back/crm-orders-search-show-all-users')
                    .then(response => {
                        this.users = response.data;
                    })
                    .catch(e => { console.log('error load users') });
            }
        },
        chooseUser(user){
            this.user = user;
            this.removeSearchUser();
            bus.$emit('chooseUser', this.user);
        },
        searchProduct(){
            this.products = [];
            this.sets = [];
            if (this.productSearch.length > 1) {
                axios.post('/back/crm-orders-search-products', {product_search: this.productSearch})
                    .then(response => {
                        this.products = response.data.products;
                        this.sets = response.data.sets;
                    })
                    .catch(e => { console.log('error load products') });
            }
        },
        removeSearchProduct(){
            this.products = [];
            this.sets = [];
            this.productSearch = '';
        },
        removeSearchUser(){
            this.users = [];
            this.userSearch = '';
        },
        addProductToCart(productId){
            bus.$emit('addProduct', productId);
        },
        addSubproductToCart(subproductId){
            bus.$emit('addSubproduct', subproductId);
        },
        addSetToCart(setId){
            bus.$emit('addSet', setId);
        },
        setCurrenctSet(set){
            this.currentSet = set;
            $('.modal-costum').show();
        },
        closeModal(){
            $('.modal-costum').hide();
        },
        addSetCart(setId){
            bus.$emit('addSet', {setId:  setId, subproductSets: this.subproductSets});
            this.closeModal();
        }
    },
}
</script>
