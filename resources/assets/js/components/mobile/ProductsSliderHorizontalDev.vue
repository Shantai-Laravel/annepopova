<template>

    <div class="productInner oneProduct ">
    <hooper :infiniteScroll="true">
         <slide>
            <products-mobile-slider-one-item-dev :product="product" :site="site"></products-mobile-slider-one-item-dev>
        </slide>

        <slide  v-if="other_products"
                v-for="otherProduct in other_products">
            <products-mobile-slider-one-item-dev :product="otherProduct" :site="site"></products-mobile-slider-one-item-dev>
        </slide>
      </hooper>
    </div>

</template>

<script>
import { VueHammer } from 'vue2-hammer'
import { Hooper, Slide } from 'hooper';
import 'hooper/dist/hooper.css';
import Slick from 'vue-slick'

Vue.use(VueHammer)

export default {
    components: { Slick, Hooper, Slide },
    props: ['product', 'category_id', 'other_products', 'site'],
    data(){
        return {
            otherProducts: [],
            loading: false,
            last_page: 0,
            page: 0,
            swipeLeft: 0,
        }
    },
    mounted(){
        // this.loadOtherProducts();
    },
    methods: {
        reInit() {},
        handleSwipe(event, slick, direction) {
            if (this.otherProducts.length >= this.swipeLeft) {
                if (direction == 'left') {
                    this.swipeLeft += 1;
                }else{
                    if (this.swipeLeft > 0) {
                        this.swipeLeft -= 1;
                    }
                }
            }else{
                if (this.swipeLeft > 0) {
                    this.swipeLeft -= 1;
                }
            }
        },
        loadOtherProducts(){
            this.loading = true;
            axios.post('/'+ this.$lang + '/' + this.site + '/get-other-products?page=' + this.page, {
                    product_id: this.product.id,
                    category_id: this.category_id
                })
                .then(response => {
                    this.last_page = response.data.last_page;
                    this.page = response.data.current_page + 1;
                    this.otherProducts = this.otherProducts.concat(response.data.data);
                    this.loading = false;
                    this.reInit();
                })
                .catch(e => {
                    console.log('error load products');
                })
        },
    },
}
</script>

<style lang="css" scoped>
    a{
        display: block;
        width: 100%;
        height: 100vh;
        border: 1px solid red;
    }
    .hooper{
        height: auto;
    }
</style>
