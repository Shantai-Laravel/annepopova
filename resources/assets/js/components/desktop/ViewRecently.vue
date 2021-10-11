<template>

    <div class="row" v-if="products.length > 0">
        <div class="col-12">
            <div class="title">
                {{ trans.vars.DetailsProductSet.recentlyViewed }}
            </div>
        </div>
        <div class="col-12" style="padding:0">
            <div style="display:block">
                <div>
                    <slick ref="slick" :options="slickOptions">
                        <div class="oneProduct" v-for="product in products" :data-productid="product.code">
                            <div class="addToWish"></div>
                            <a class="imgBlock" :href="'/' + $lang + '/' + product.type + '/catalog/' +  product.category.alias + '/' + product.alias" @click="viewProductGA(product)">
                            <img :src="'/images/products/og/' + product.main_image.src" :alt="product.translation.name" v-if="product.main_image"/>
                            <img src="/images/no-image-ap.jpg" :alt="product.translation.name" v-else/>
                            </a>
                            <a :href="'/' + $lang + '/' + product.type + '/catalog/' +  product.category.alias + '/' + product.alias" @click="viewProductGA(product)">{{ product.translation.name }}</a>
                            <div class="price">
                                <span>
                                    {{ product.personal_price.price }}
                                    <span v-if="product.personal_price.old_price == product.personal_price.price">{{ $currency }}</span>
                                </span>
                                <span v-if="product.personal_price.old_price !== product.personal_price.price">{{ product.personal_price.old_price }} {{ $currency }}</span>
                                <span v-else></span>
                            </div>
                        </div>
                    </slick>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import { bus } from '../../app';
    import Slick from 'vue-slick';

    export default {
        components: { Slick },
        props : ['wish', 'site'],
        data() {
            return {
                slickOptions: {
                    dots: false,
                    infinite: true,
                    speed: 800,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    variableWidth: false,
                    arrows: false,
                },
                products : [],
                page: 1,
                last_page: '',
                loading: false,
                ready: false,
                className: 'hide',
            }
        },
        mounted() {
            this.load();
        },
        methods: {
            viewProductGA(product){
                // bus.$emit('ga-event-clickImpresions', {product: product, list: "View Recently"});
                bus.$emit('ga-event-viewProduct', {product: product, actionField: "View Recently"});
            },
            // load products method
            load() {
                this.loading = true;
                axios.post('/' + this.$lang + '/' + this.site + '/get-recently-products')
                    .then(response => {
                        this.products = response.data;
                        this.loading = false;
                        this.ready = true;
                        let vm = this;
                        setTimeout(function(){
                            vm.className = '';
                        }, 4000)
                    })
                    .catch(e => {
                        this.loading = false;
                        console.log('loading products error.');
                    })
            },
        }
    }
</script>

<style>
    .hide{
        visibility: hidden;
    }
</style>
