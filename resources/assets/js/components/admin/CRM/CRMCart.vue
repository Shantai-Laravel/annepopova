<template lang="html">

    <div class="row" v-if="loading">
        <div class="col-md-9 admin-cart" v-for="cartSet in cartSets">
            <div class="col-md-1 text-center">
                <img :src="'/images/sets/og/' + cartSet.set.main_photo.src" v-if="cartSet.set.main_photo">
                <img src="/admin/img/noimage.jpg" v-else>
            </div>
            <div class="col-md-3">
                <p><b>{{ cartSet.set.translation.name }}</b></p>
                <p>code - {{ cartSet.set.code }}</p>
                <a href="#" data-toggle="modal" :data-target="'#products-modal' + cartSet.id"><small>view products</small></a>
            </div>
            <div class="col-md-2">
                <span class="label label-danger" v-if="cartSet.stock_qty == 0">out of stock</span>
                <span class="label label-primary" v-else>stock - {{ cartSet.stock_qty }}</span>
            </div>
            <div class="col-md-1">
                <a href="#" @click="toFavoritesCart(cartSet.id)"><i class="fa fa-heart"></i></a>
                <a href="#" @click="deformationSet(cartSet.id)"><i class="fa fa-cut"></i></a>
            </div>
            <div class="col-md-1">
                <select :name="cartSet.id" @change="changeQty">
                    <option :value="index + 1" v-for="(stock, index) in cartSet.stock_qty" :selected="cartSet.qty == (index + 1) ? true : false">
                        {{ index + 1 }}
                    </option>
                </select>
            </div>
            <div class="col-md-2">
                {{ cartSet.set.price.price * cartSet.qty }} EUR
            </div>
            <div class="col-md-1">
                <small>{{ cartSet.set.price.price }}</small>
            </div>
            <div class="col-md-1">
                <i class="fa fa-trash" @click="removeCart(cartSet.id)"></i>
            </div>

            <!-- Set Products Modal -->
            <div class="modal fade bd-example-modal-md settings-modal" :id="'products-modal' + cartSet.id" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="loading" v-if="loading"><div class="lds-ripple"><div></div><div></div></div></div>
                    <div class="modal-content">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="text-center">Set - {{ cartSet.set.translation.name }}</h5>
                                <hr>
                            </div>
                            <div class="row">
                                <div class="col-md-12 admin-cart" v-for="product in cartSet.set.products">
                                    <div class="col-md-1">
                                        <img src="/admin/img/noimage.jpg">
                                    </div>
                                    <div class="col-md-6">
                                        {{ product.translation.name }}
                                    </div>
                                    <div class="col-md-2" v-if="product.subproducts.length > 0">
                                        <select class="select-costum" :name="product.id" :data-id="cartSet.id" @change="changeSetSubproducts">
                                            <option :value="subproduct.id"
                                                    v-for="subproduct in product.subproducts"
                                                    v-if="subproduct.stoc !== 0"
                                                    :selected="getSelectedSubproduct(cartSet, product.id, subproduct.id)">
                                                    {{ subproduct.parameter_value.translation.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2" v-else>
                                        <span class="label label-primary">Product</span>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9 admin-cart" v-for="cartProduct in cartProducts">
            <div class="col-md-1 text-center">
                <img :src="'/images/products/og/' + cartProduct.product.main_image.src" v-if="cartProduct.product.main_image">
                <img src="/admin/img/noimage.jpg" v-else>
            </div>
            <div class="col-md-3">
                <p><b>{{ cartProduct.product.translation.name }}</b></p>
                <p>code - {{ cartProduct.product.code }}</p>
            </div>
            <div class="col-md-2">
                <span class="label label-danger" v-if="cartProduct.stock_qty == 0">out of stock</span>
                <span class="label label-primary" v-else>stock - {{ cartProduct.stock_qty }}</span>
            </div>
            <div class="col-md-1">
                <a href="#" @click="toFavoritesCart(cartProduct.id)"><i class="fa fa-heart"></i></a>
            </div>
            <div class="col-md-1">
                <select :name="cartProduct.id" @change="changeQty">
                    <option :value="index + 1" v-for="(stock, index) in cartProduct.stock_qty" :selected="cartProduct.qty == (index + 1) ? true : false">
                        {{ index + 1 }}
                    </option>
                </select>
            </div>
            <div class="col-md-2">
                 {{ cartProduct.product.main_price.price * cartProduct.qty }} EUR
            </div>
            <div class="col-md-1">
                <small>{{ cartProduct.product.main_price.price }}</small>
            </div>
            <div class="col-md-1">
                <i class="fa fa-trash" @click="removeCart(cartProduct.id)"></i>
            </div>
        </div>

        <div class="col-md-9 admin-cart" v-for="cartSubproduct in cartSubproducts">
            <div class="col-md-1 text-center">
                <img :src="'/images/products/og/' + cartSubproduct.subproduct.product.main_image.src" v-if="cartSubproduct.subproduct.product.main_image">
                <img src="/admin/img/noimage.jpg" v-else>
            </div>
            <div class="col-md-3">
                <p><b>{{ cartSubproduct.subproduct.product.translation.name }}</b></p>
                <p>code - {{ cartSubproduct.subproduct.code }}</p>
                <p><span class="badge badge-primary">{{ cartSubproduct.subproduct.parameter_value.translation.name }}</span></p>
            </div>
            <div class="col-md-2">
                <span class="label label-danger" v-if="cartSubproduct.stock_qty == 0">out of stock</span>
                <span class="label label-primary" v-else>stock - {{ cartSubproduct.stock_qty }}</span>
            </div>
            <div class="col-md-1">
                <a href="#" @click="toFavoritesCart(cartSubproduct.id)"><i class="fa fa-heart"></i></a>
            </div>
            <div class="col-md-1">
                <select :name="cartSubproduct.id" @change="changeQty">
                    <option :value="index + 1" v-for="(stock, index) in cartSubproduct.stock_qty" :selected="cartSubproduct.qty == (index + 1) ? true : false">
                        {{ index + 1 }}
                    </option>
                </select>
            </div>
            <div class="col-md-2">
                {{ cartSubproduct.subproduct.price.price * cartSubproduct.qty }} EUR
            </div>
            <div class="col-md-1">
                <small>{{ cartSubproduct.subproduct.price.price }}</small>
            </div>
            <div class="col-md-1">
                <i class="fa fa-trash" @click="removeCart(cartSubproduct.id)"></i>
            </div>
        </div>

        <div class="summary">
            <h6 class="text-center">Order summary</h6>

            <div class="promocode-area">
                <label for="">Promocode</label>
                <div class="col-md-6">
                    <select class="select-costum" v-model="promocode" @change="applyPromocode">
                        <option value=" " selected>NonUser promocode</option>
                        <option :value="promocode.name" v-for="promocode in user.promocodes">{{ promocode.name }}</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" class="select-costum"  placeholder="or enter promocode" v-model="promocode" @keyup="applyPromocode">
                </div>
                <div class="col-md-12 text-center">
                    <small class="text-danger" v-if="promocodeDetails.status == 'false'">{{ promocodeDetails.message }}</small>
                    <small class="text-success" v-if="promocodeDetails.status == 'true'">{{ promocodeDetails.message }}</small>
                </div>
            </div>

            <div class="col-md-6"> Subtotal </div>
            <div class="col-md-6 text-right"> {{ ammount }} EUR</div>

            <div class="col-md-6"> Shipping </div>
            <div class="col-md-6 text-right">{{ delivery }} EUR</div>

            <div class="col-md-6"> Tax </div>
            <div class="col-md-6 text-right">{{ parseFloat(ammount) * parseFloat(tax) }} EUR</div>

            <div class="col-md-12"><hr></div>
            <div class="col-md-6"> <b>Total</b> </div>
            <div class="col-md-6 text-right"><b>{{ parseFloat(delivery) + parseFloat(ammount) + (parseFloat(ammount) * parseFloat(tax))}} EUR</b></div>
        </div>

    </div>

</template>

<script>

import { bus } from '../../../app';

export default {
    data(){
        return {
            user: [],
            cartProducts: [],
            cartSubproducts: [],
            cartSets: [],
            qty: 0,
            ammount: 0,
            loading: false,
            promocode: '',
            promocodeDetails: {
                status: '',
                message: '',
                discount: 0,
                id: 0,
            },
            delivery: 0,
            tax: 0,
            currencyId: 5,
        }
    },
    mounted(){
        bus.$on('chooseUser', data => {
            this.user = data;
            this.getUserCart();
            this.loading = true;
        });
        bus.$on('addSet', data => {
            this.addSetToCart(data.setId, data.subproductSets);
        });
        bus.$on('addProduct', data => {
            this.addProductToCart(data);
        });
        bus.$on('addSubproduct', data => {
            this.addSubproductToCart(data);
        });
        bus.$on('changeCountry', data => {
            this.delivery = data.main_delivery ? data.main_delivery.delivery.price : 0;
            this.tax = data.vat ? data.vat : 0;
        });
        bus.$on('changeDelivery', data => {
            this.delivery = data.price;
        });
        bus.$on('updateCartItems', data => {
            this.cartProducts = data.products;
            this.cartSubproducts = data.subproducts;
            this.cartSets = data.sets;
        });
        bus.$on('getOrderData', data => {
            bus.$emit('sendCartData', {
                userId : this.user.id,
                currencyId : this.currencyId,
                amount: this.ammount,
                deliveryPrice: this.delivery,
                taxPrice: this.tax,
                promocodeId: this.promocodeDetails.id,
                promocodeDiscount: this.promocodeDetails.discount,
            });
        });
    },
    methods: {
        getUserCart(){
            axios.post('/back/crm-orders-get-user-cart', {user_id: this.user.id})
                .then(response => {
                    this.cartProducts = response.data.products;
                    this.cartSubproducts = response.data.subproducts;
                    this.cartSets = response.data.sets;
                    this.getAmmount();
                    this.delivery = this.user.country.main_delivery ? this.user.country.main_delivery.delivery.price : 0;
                    this.tax = this.user.country.vat ? this.user.country.vat : 0;
                })
                .catch(e => { console.log('error load user cart') });
        },
        addSetToCart(setId, subproductSets){
            axios.post('/back/crm-orders-add-set-to-cart', {user_id: this.user.id, set_id: setId, subproducts: subproductSets})
                .then(response => {
                    this.cartProducts = response.data.carts.products;
                    this.cartSubproducts = response.data.carts.subproducts;
                    this.cartSets = response.data.carts.sets;
                    this.getAmmount();
                    this.cartSets.find(set => set.set_id === setId).set.stock = response.data.stock;
                })
                .catch(e => { console.log('error load user cart') });
        },
        addProductToCart(productId){
            axios.post('/back/crm-orders-add-product-to-cart', {user_id: this.user.id, product_id: productId})
                .then(response => {
                    this.cartProducts = response.data.products;
                    this.cartSubproducts = response.data.subproducts;
                    this.cartSets = response.data.sets;
                    this.getAmmount();
                })
                .catch(e => { console.log('error load user cart') });
        },
        addSubproductToCart(subproductId){
            axios.post('/back/crm-orders-add-subproduct-to-cart', {user_id: this.user.id, subproduct_id: subproductId})
                .then(response => {
                    this.cartProducts = response.data.products;
                    this.cartSubproducts = response.data.subproducts;
                    this.cartSets = response.data.sets;
                    this.getAmmount();
                })
                .catch(e => { console.log('error load user cart') });
        },
        changeQty(e){
            let id = e.target.name;
            let qty = e.target.value;

            axios.post('/back/crm-orders-change-qty', {user_id: this.user.id, id: id, qty: qty})
                .then(response => {
                    this.cartProducts = response.data.products;
                    this.cartSubproducts = response.data.subproducts;
                    this.cartSets = response.data.sets;
                    this.getAmmount();
                })
                .catch(e => { console.log('error load user cart') });
        },
        removeCart(id){
            if (confirm("Do you really want to remove this item?")) {
                axios.post('/back/crm-orders-remove-cart', {user_id: this.user.id, id: id})
                    .then(response => {
                        this.cartProducts = response.data.products;
                        this.cartSubproducts = response.data.subproducts;
                        this.cartSets = response.data.sets;
                        this.getAmmount();
                    })
                    .catch(e => { console.log('error load user cart') });
            }
        },
        getAmmount(){
            let ammount = 0;
            this.cartProducts.forEach(function(entry){
                if (entry.stock_qty > 0) {
                    ammount += entry.product.main_price.price * entry.qty;
                }
            });

            this.cartSubproducts.forEach(function(entry){
                if (entry.stock_qty > 0) {
                    ammount += entry.subproduct.price.price * entry.qty;
                }
            });

            this.cartSets.forEach(function(entry){
                if (entry.stock_qty > 0) {
                    ammount += entry.set.price.price * entry.qty;
                }
            });

            this.ammount = ammount - (ammount * this.promocodeDetails.discount / 100);
        },
        applyPromocode(){
            this.promocodeDetails.status = '';
            this.promocodeDetails.message = '';
            this.promocodeDetails.discount = 0;
            this.promocodeDetails.id = 0;
            this.getAmmount();

            if (this.promocode.length > 2) {
                axios.post('/back/crm-apply-promocode', {user_id: this.user.id, promocode: this.promocode, ammount: this.ammount})
                    .then(response => {
                        this.promocodeDetails.status = response.data.status;
                        this.promocodeDetails.message = response.data.message;
                        this.promocodeDetails.discount = response.data.discount;
                        this.promocodeDetails.id = response.data.id;
                        this.getAmmount();
                    })
                    .catch(e => { console.log('error load user cart') });
            }
        },
        getSelectedSubproduct(set, productId, subproductId){
            let ret = false;
            set.children.forEach(function(enrty) {
                if (enrty.product_id == productId) {
                    if (enrty.subproduct_id == subproductId) {
                        return ret = true;
                    }
                }
            });

            return ret;
        },
        changeSetSubproducts(e){
            let set = e.target.getAttribute('data-id');
            let prod = e.target.name;
            let subprod = e.target.value;
            let setStock = this.cartSets.find(setCart => setCart.id === parseInt(set)).set.stock;

            axios.post('/back/crm-change-set-subproduct', {user_id: this.user.id, cart_set: set, product_id: prod, subproduct_id: subprod, set_stock: setStock})
                .then(response => {
                    this.cartProducts = response.data.products;
                    this.cartSubproducts = response.data.subproducts;
                    this.cartSets = response.data.sets;
                    this.getAmmount();
                })
                .catch(e => { console.log('error load user cart') });

        },
        toFavoritesCart(id){
            if (confirm("Do you really want to move to favorite this item?")) {
                axios.post('/back/crm-orders-favorite-cart', {user_id: this.user.id, id: id})
                    .then(response => {
                        this.cartProducts = response.data.products;
                        this.cartSubproducts = response.data.subproducts;
                        this.cartSets = response.data.sets;
                        this.getAmmount();
                    })
                    .catch(e => { console.log('error load user cart') });
            }
        },
        deformationSet(id){
            if (confirm("Do you really want to deformate this set?")) {
                axios.post('/back/crm-orders-deformate-set-cart', {user_id: this.user.id, id: id})
                    .then(response => {
                        this.cartProducts = response.data.products;
                        this.cartSubproducts = response.data.subproducts;
                        this.cartSets = response.data.sets;
                        this.getAmmount();
                    })
                    .catch(e => { console.log('error load user cart') });
            }
        }
    },

}
</script>

<style media="screen">
    .modal-content .row{
        margin: 15px;
    }
</style>
