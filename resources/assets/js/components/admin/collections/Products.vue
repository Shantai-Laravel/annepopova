<template>

    <div>
        <vue-nestable v-model="products"
                        :maxDepth="1"
                        @change="change"
                        >
            <vue-nestable-handle slot-scope="{ item }" :item="item">
                <div class="row">
                    <div class="col-md-1">
                        <img :src="'/images/products/sm/' + item.main_image.src" height="50px;" v-if="item.main_image">
                        <img src="/admin/img/noimage.jpg" height="45px;" v-else>
                    </div>
                    <div class="col-md-7">
                        <span>{{ item.translation.name }}</span>
                    </div>
                    <div class="col-md-2 text-right">
                        <i>{{ item.code }}</i>
                    </div>
                    <div class="col-md-2 text-right">
                        <a href="#" @click="setCurrentSetProduct(item)" data-toggle="modal" :data-target="'#productSetImage'"><i class="fa fa-image"></i></a>
                        <a :href="'/back/products/' + item.id + '/edit'"><i class="fa fa-edit"></i></a>
                        <a href="#" @click="remove(item)"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
            </vue-nestable-handle>
        </vue-nestable>
    </div>

</template>

<script>

import { bus } from '../../../app';
import { VueNestable, VueNestableHandle } from 'vue-nestable';

export default {
    props: ['products_prop', 'set'],
    data(){
        return {
            maxDepth: 1,
            products: this.products_prop,
        }
    },
    mounted(){
        bus.$on('updateProducts' + this.set.id, data => {
            this.products = data
        });
    },
    methods: {
        setCurrentSetProduct(product){
            console.log(product);
            bus.$emit('currentSet', this.set);
            bus.$emit('currentProduct', product);
        },
        change(){
            axios.post('/back/product-collections/changeProducts', {products: this.products, set_id: this.set.id})
                .then(response => {
                    this.products = response.data.products;
                })
        },
        remove(product){
            if (confirm("Do you really want to remove this product from set?")) {
                axios.post('/back/product-collections/removeProduct', {product_id: product.id, set_id: this.set.id})
                    .then(response => {
                        this.products = response.data.products;
                        bus.$emit('updateSets' + this.set.collection_id);
                    })
                    .catch(e => { console.log('error remove product') });
            }
        }
    },
    components: {
        VueNestable,
        VueNestableHandle
    },

}
</script>

<style lang="css" scoped>
    .products{
        margin-left: 40px;
    }
</style>
