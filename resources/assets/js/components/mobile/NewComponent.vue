<template>

    <div class="row" v-if="ready">
        <div class="col-xl-3 col-lg-4 col-md-4 col-12 oneProductBlock" v-for="(product, key) in products" :data-productid="product.code">
            <a :href="'/' + $lang + '/catalog/' +  product.category.alias + '/' + product.alias" @click="viewProductGA(product)">
                <img :src="'/images/products/sm/' + product.main_image.src" :alt="product.translation.name" v-if="product.main_image"/>
                <img src="/images/no-image-ap.jpg" :alt="product.translation.name" v-else/>
            </a>
            <product :product="product" :id="product.id"></product>
        </div>

    </div>

</template>

<script>
    import { bus } from '../../app';

    export default {
        props : ['wish'],
        data() {
            return {
                products : [],
                page: 1,
                last_page: '',
                loading: false,
                ready: false,
            }
        },
        mounted() {
            this.load();
            window.addEventListener('scroll', this.handleScroll);
        },
        methods: {
            viewProductGA(product){
                bus.$emit('ga-event-clickImpresions', {product: product, list: "New"});
            },
            // load products method
            load() {
                this.loading = true;
                axios.post('/'+ this.$lang +'/get-new-products?page=' + this.page)
                    .then(response => {
                      this.products = this.products.concat(response.data.data);
                      this.last_page = response.data.last_page;
                      this.page = response.data.current_page + 1;
                      this.loading = false;
                      this.ready = true;
                    })
                    .catch(e => {
                      this.loading = false;
                      console.log('loading products error.');
                    })
            },
            // handle scroll, add more
            handleScroll(event){
                if (this.page <= this.last_page) {
                    var scrollY = window.scrollY
                    var visible = document.documentElement.clientHeight
                    var pageHeight = document.documentElement.scrollHeight - 1500
                    var bottomOfPage = visible + scrollY >= pageHeight
                    var diff =  bottomOfPage || pageHeight < visible

                    if (diff && !this.loading ) {
                        this.page = this.page;
                        this.load();
                    }
                }
            }
        }
    }
</script>
