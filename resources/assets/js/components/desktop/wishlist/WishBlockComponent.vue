<template>
<div class="row">
    <div class="col-12">
        <h3>{{ trans.vars.Wishlist.wishTitle }}</h3>
    </div>
    <div class="col-lg-4" v-for="wishListProduct in wishListProducts" :key="wishListProduct.id">
        <div class="oneProduct item">
            <div class="remove" @click="removeProductWish(wishListProduct.id)"></div>
            <a :href="'/' + $lang + '/' + wishListProduct.product.type + '/catalog/' + wishListProduct.product.category.alias + '/' + wishListProduct.product.alias">
                <img v-if="wishListProduct.product.main_image" :src="'/images/products/sm/' + wishListProduct.product.main_image.src">
                <img v-else src="/images/no-image-ap.jpg" alt="product" />
            </a>

            <add-to-cart-wish-product :product="wishListProduct.product" :site="site"></add-to-cart-wish-product>

            <a :href="'/' + $lang + '/' + wishListProduct.product.type + '/catalog/' + wishListProduct.product.category.alias + '/' + wishListProduct.product.alias">{{ wishListProduct.product.translation.name }}</a>
            <div class="price">
                <span>{{ wishListProduct.product.personal_price.price }} </span>
                <span>{{ wishListProduct.product.personal_price.old_price }} {{ $currency }}</span>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import { bus } from '../../../app.js'

export default {
    props: ['products', 'site'],
    data() {
        return {
            wishListProducts: this.products,
        }
    },
    mounted() {
        bus.$on('updateWishList', data => {
            this.wishListProducts = data.products;
        })
    },
    methods: {
        removeProductWish(id) {
            axios.post('/' + this.$lang + '/' + this.site + '/removeProductWish', {
                    id: id
                })
                .then(response => {
                    this.wishListProducts = response.data.products,
                        bus.$emit('updateWishBox', response.data.products);
                })
                .catch(e => {
                    console.log('error remove product')
                })
        },
    }
}
</script>
