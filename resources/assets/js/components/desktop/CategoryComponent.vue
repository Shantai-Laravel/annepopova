<template>

    <div class="row">

      <div class="col-md-4" v-for="product in products" :data-productid="product.code">
        <div class="oneCateProd">
          <div class="oneProduct">
            <div class="addToWish"></div>
            <a  class="imgBlock" :href="'/' + $lang + '/' + site + '/catalog/' +  product.category.alias + '/' + product.alias" @click="viewProductGA(product)">
                <img :src="'/images/products/og/' + product.main_image.src" :alt="product.translation.name" v-if="product.main_image"/>
                <img src="/images/no-image-ap.jpg" :alt="product.translation.name" v-else/>
            </a>
            <a :href="'/' + $lang + '/' + site + '/catalog/' +  product.category.alias + '/' + product.alias" @click="viewProductGA(product)">{{ product.translation.name }}</a>
            <div class="price">
                <span>
                    {{ product.personal_price.price }}
                    <span v-if="product.personal_price.old_price == product.personal_price.price">{{ $currency }}</span>
                </span>
                <span v-if="product.personal_price.old_price !== product.personal_price.price">{{ product.personal_price.old_price }} {{ $currency }}</span>
                <span v-else></span>
            </div>
          </div>
        </div>
      </div>

    </div>

</template>

<script>
import { bus } from '../../app';

export default {
    props: ['category', 'product', 'site'],
    data() {
        return {
            products: [],
            productsTemplate: [],
            defaultFilter: [],
            page: 0,
            last_page: 0,
            loading: false,
            categoryFiltredId: this.category.id
        };
    },
    mounted() {
        bus.$on('sendInitProducts', data => {
            this.products = this.products.concat(data.data);
            this.parseProducts();
        })

        bus.$on('categoryFilter', data => {
            this.categoryFiltredId = data.id;
            this.page = 0;
            this.filterCategory();
        })

        bus.$on('filterProducts', data => {
            this.products = data;
            this.parseProducts();
        })

        bus.$on('loadMoreFilterProducts', data => {
            this.products = this.products.concat(data.data);
            this.parseProducts();
        })

        window.addEventListener('scroll', this.handleScroll);
    },
    methods: {
        viewProductGA(product){
            // bus.$emit('ga-event-clickImpresions', {product: product, list: "One cat - " + category.translation.name});
            bus.$emit('ga-event-viewProduct', {product: product, actionField: category.translation.name});
        },
        parseProducts(){
            let productArr = [];
            let products = [];
            let i = 0;
            let s = 0;
            this.products.forEach(function(entry, key){
                if ((key + 1) % 3 == 1) {
                    products = [];
                    i = 0;
                    s++;
                }
                products[i] = entry;
                productArr[s] = products;
                i++;
            });
            let filtered = productArr.filter(function (el) {
              return el != null;
            });
            this.productsTemplate = filtered;
        },
        handleScroll(e) {
            let scrollY = window.scrollY
            let visible = document.documentElement.clientHeight
            let pageHeight = document.documentElement.scrollHeight - 1500

            bus.$emit('handleScroll', {
                scrollY: scrollY,
                visible: visible,
                pageHeight: pageHeight,
                last_page: this.last_page
            });
        },
        // load cart products method
        load() {
            this.loading = true;
            axios.post('/'+ this.$lang + '/' + this.site + '/categories?page=' + this.page, {
                    mainProductId: this.product,
                    category_id: this.categoryFiltredId
                })
                .then(response => {
                    this.last_page = response.data.products.last_page;
                    this.page = response.data.products.current_page + 1;
                    this.products = this.products.concat(response.data.products.data);
                    this.defaultFilter = response.data.filter;
                    this.loading = false;

                    bus.$emit('setFilterDefault', response.data.filter);
                })
                .catch(e => {
                    console.log('error load products');
                })
        },
        filterCategory() {
            this.loading = true;
            axios.post('/'+ this.$lang + '/' + this.site + '/categories?page=' + this.page, {
                    mainProductId: this.product,
                    category_id: this.categoryFiltredId
                })
                .then(response => {
                    this.last_page = response.data.last_page;
                    this.page = response.data.current_page + 1;
                    this.loading = false;
                    this.products = response.data.data;
                })
                .catch(e => {
                    console.log('error load products');
                })
        },

    },
}
</script>
