<template>

    <div>
        <vue-nestable v-model="sets"
                        :maxDepth="1"
                        @change="change"
                        >
            <vue-nestable-handle slot-scope="{ item }" :item="item">
                <div class="row">
                    <div class="col-md-4">
                        <i class="fa fa-ellipsis-v"></i>
                        <span>{{ item.translation.name }}</span>
                    </div>
                    <!-- <div class="col-md-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" :id="'.com' + item.id">
                            <label class="form-check-label" :for="'.com' + item.id">.com</label>
                        </div>
                    </div> -->
                    <div class="col-md-5 text-right">

                            <a href="#" data-toggle="modal" @click="setCurrentSet(item)" :data-target="'#productToSet'"><i class="fa fa-plus"></i></a>

                            <a :href="'/back/sets/' + item.id + '/edit'"><i class="fa fa-edit"></i></a>

                            <a href="#" v-if="item.products.length < 1" @click="remove(item)"><i class="fa fa-trash"></i></a>

                            <a href="#" v-if="item.products.length > 0" @click="openSets(item.id)"><i class="fa fa-chevron-down"></i></a>
                    </div>
                </div>

                <div class="row products" :id="'product' + item.id" v-if="item.products.length > 0">
                    <products-depth :products_prop="item.products" :set="item"></products-depth>
                </div>
            </vue-nestable-handle>
        </vue-nestable>

    </div>

</template>

<script>

import { bus } from '../../../app';
import { VueNestable, VueNestableHandle } from 'vue-nestable';

export default {
    props: ['sets_prop', 'collection'],
    data(){
        return {
            maxDepth: 1,
            sets: this.sets_prop,
            currentSet: [],
        }
    },
    mounted(){
        bus.$on('updateSets' + this.collection.id, response => {
            this.updateSet();
        });
    },
    methods: {
        setCurrentSet(set){
            // this.currentSet = set;
            bus.$emit('currentSet', set);
        },
        change(){
            axios.post('/back/product-collections/changeSets', {sets: this.sets, collection_id: this.collection.id})
                .then(response => {
                    this.sets = response.data;
                })
        },
        openSets(id){
            $('.products').not('#product' + id).hide();

            if ($('#product' + id).css('display') == 'none') {
                $('#product' + id).show();
            }else{
                $('#product' + id).hide();
            }

        },
        updateSet(){
            axios.post('/back/product-collections/getSets', {collection_id: this.collection.id})
                .then(response => {
                    // $('.products').hide();
                    this.sets = response.data;
                })
        },
        remove(set){
            if (confirm("Do you really want to remove this set?")) {
                axios.post('/back/product-collections/removeSet', {set_id: set.id, collection_id: this.collection.id})
                    .then(response => {
                        this.sets = response.data;
                        bus.$emit('updateCollections');
                    })
                    .catch(e => { console.log('error remove set') });
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
        display: none;
    }

</style>
