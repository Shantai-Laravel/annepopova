<template>

    <div class="productInner oneProduct ">
        <hooper :infiniteScroll="true">

             <slide v-if="main_set !== 'empty'">
                <collections-mobile-slider-one-item-dev :set="main_set"
                                                    :site="site"
                                                    >
                </collections-mobile-slider-one-item-dev>
            </slide>

            <slide  v-for="otherSet in other_sets"
                    v-if="other_sets && otherSet.id !== main_set.id"
                    >
                <collections-mobile-slider-one-item-dev :set="otherSet"
                                                    :site="site"
                                                    >
                </collections-mobile-slider-one-item-dev>
            </slide>

        </hooper>
    </div>

</template>

<script>
import { VueHammer } from 'vue2-hammer'
import { Hooper, Slide } from 'hooper';
import 'hooper/dist/hooper.css';
Vue.use(VueHammer)

export default {
    components: { Hooper, Slide },
    props: ['main_set', 'other_sets', 'site'],
    data(){
        return {
            otherProducts   : [],
            loading         : false,
            last_page       : 0,
            page            : 0,
            swipeLeft       : 0,
        }
    },
    mounted(){
        // this.loadOtherProducts();
    },
    methods: {
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
    .hooper{
        height: auto;
    }
</style>
