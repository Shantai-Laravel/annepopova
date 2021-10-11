<template>
    <aside>
        <div class="title">{{ trans.vars.DetailsProductSet.productsFrom }}</div>
        <div class="products">
            <div class="item" v-for="product in set.products" :data-productid="product.code">
                <a :href="'/' + $lang + '/' + product.type + '/catalog/' + product.category.alias + '/' + product.alias" @click="viewProductGA(product)">
                    <img :src="'/images/products/sm/' + product.set_image.src" v-if="product.set_image" />
                    <img src="/images/no-image-ap.jpg" v-else/>
                </a>
                <add-to-cart-set-product :product="product" :site="site"></add-to-cart-set-product>
            </div>
            <a href="#" data-toggle="modal" data-target="#modalShipping">{{ trans.vars.DetailsProductSet.shippingPaymentReturns }}</a>

        </div>
    </aside>


</template>

<script>
import { bus } from '../../app';

export default {
    props: ['set', 'site'],
    data() {
        return {
            subproducts: [],
            chooseProducts: false,
            ammount: 0,
            notInStock: false,
            product: [],
            subproduct: [],
        };
    },
    mounted() {

    },
    methods: {
        viewProductGA(product){
            // bus.$emit('ga-event-clickImpresions', {product: product, list: "One col"});
            bus.$emit('ga-event-viewProduct', {product: product, actionField: set.collection.translation.name+" "+set.translation.name});
        },
        getSetImage(product, setId){
            return true;
            let ret = false;
            product.set_images.forEach(function(entry){
                if(setId == entry.set_id){
                    ret = entry.image;
                    return ret;
                }
            })
            return ret;
        },
    },
}
</script>
