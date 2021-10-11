<template>

    <div class="categories-cover">

        <div class="row">
            <div class="col-md-12">
                <a href="#" data-toggle="modal" data-target="#addNew" class="btn btn-primary"><i class="fa fa-plus"></i> Add new collection</a> <hr>

                <!-- Add New Modal -->
                <div class="modal fade settings-modal" id="addNew" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h6 class="modal-title">Add new collection</h6>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" v-for="lang in langs">
                                            <div class="row">
                                                <div class="col-md-2 text-right">
                                                    <label for="">Title [{{ lang.lang }}]</label>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" v-model="titles[lang.id]">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center" v-if="validateTitles()">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close" @click="addSave">Save</button>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close" @click="addEdit">Save & Edit</button>
                                </div>
                                <div class="col-md-12 text-center" v-if="!validateTitles()">
                                    <button type="button" class="btn btn-primary" disabled>Save</button>
                                    <button type="button" class="btn btn-primary" disabled>Save & Edit</button>
                                    <p class="text-danger"><small>* all fields are required</small></p>
                                </div>
                            </div><hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <vue-nestable v-model="collections"
                        :maxDepth="1"
                        @change="change"
                        >
            <vue-nestable-handle slot-scope="{ item }" :item="item">
                <div class="row">
                    <div class="col-md-7">
                        <i class="fa fa-ellipsis-v"></i>
                        <span>{{ item.translation.name }}</span>
                    </div>
                    <div class="col-md-5 text-right pull-right">
                        <div class="col-md-4"></div>
                        <div class="col-md-2">
                            <a href="#" data-toggle="modal" data-target="#addSet" @click="setCurrent(item)"><i class="fa fa-plus"></i></a>
                        </div>
                        <div class="col-md-2">
                            <a :href="'/back/product-collections/' + item.id + '/edit'"><i class="fa fa-edit"></i></a>
                        </div>
                        <div class="col-md-2">
                            <a href="#" @click="remove(item)" v-if="item.sets.length < 1"><i class="fa fa-trash"></i></a>
                        </div>
                        <div class="col-md-2">

                            <a href="#" @click="openSets(item.id)" v-if="item.sets.length > 0"><i class="fa fa-chevron-down"></i></a>
                        </div>
                    </div>

                </div>

                <div class="row sets" :id="'set' + item.id" v-if="item.sets.length > 0">
                    <sets :sets_prop="item.sets" :collection="item"></sets>
                </div>

            </vue-nestable-handle>
        </vue-nestable>

        <!-- create new set -->
        <div class="modal fade settings-modal" id="addSet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h6 class="modal-title">Create set in <b>{{  currentCollection.translation ?  currentCollection.translation.name : ''}}</b></h6>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" v-for="lang in langs">
                                    <div class="row">
                                        <div class="col-md-2 text-right">
                                            <label for="">Title [{{ lang.lang }}]</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" v-model="titles[lang.id]">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">

                                    <div class="col-md-2 text-right">
                                        <label for="">Code</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" v-model="code">
                                    </div>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-2 text-right">
                                            <label for="">Price</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="number" class="form-control" v-model="price">
                                        </div>
                                    </div>
                                </div> -->
                                <!-- <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-2 text-right">
                                            <label for="">Discount</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="number" class="form-control" v-model="discount">
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center" v-if="validateTitles()">
                            <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close" @click="addSaveSet">Save</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close" @click="addEditSet">Save & Edit</button>
                        </div>
                        <div class="col-md-12 text-center" v-if="!validateTitles()">
                            <button type="button" class="btn btn-primary" disabled>Save</button>
                            <button type="button" class="btn btn-primary" disabled>Save & Edit</button>
                            <p class="text-danger"><small>* title fields are required</small></p>
                        </div>
                    </div><hr>
                </div>
            </div>
        </div>

        <!-- add product image to set -->
        <div class="modal fade settings-modal" :id="'productSetImage'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h6 class="modal-title">
                            Set - <b>{{  currentSet.translation ?  currentSet.translation.name : ''}}</b> <hr>
                            Product - <b>{{  currentProduct.translation ?  currentProduct.translation.name : ''}}</b>
                        </h6>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <label for="">Choose image</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="file" id="attachments" @change="uploadFieldChange" :name="'set' + currentSet.id" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-primary svbtn" :id="'set' + currentSet.id" @click="uploadSetImage(currentSet.id)">save</button>
                                </div>
                            </div>
                        </div><hr>
                        <div class="row" v-for="image in currentProduct.set_images">
                            <div class="col-md-12" v-if="image.set_id == currentSet.id">
                                <span class="badge" @click="removeSetProductImage(image.id)"><i class="fa fa-trash"></i></span>
                                <img :src="'/images/products/set/' + image.image" width="100px">
                            </div>
                        </div>
                        <!-- {{ currentProduct.set_images }} -->
                    </div>
                </div>
            </div>
        </div>

        <!-- add product to set -->
        <div class="modal fade settings-modal" :id="'productToSet'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h6 class="modal-title">Create set in <b>{{  currentSet.translation ?  currentSet.translation.name : ''}}</b></h6>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-primary" v-if="message">
                            <p class="text-center" v-html="message"></p>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Search, enter product name or code</label>
                                    <input type="text" v-model="search" @keyup="searchProduct" class="form-control" placeholder="product name or code">
                                </div>
                            </div>
                            <div class="col-md-12 text-right">
                                <small>* will only show products that are not yet assigned to this set</small> <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 product-search" v-for="product in searchProducts">
                                <div class="col-md-2">
                                    <img :src="'/images/products/sm/' + product.main_image.src" height="50px;" v-if="product.main_image">
                                    <img src="/admin/img/noimage.jpg" height="50px;" v-else>
                                </div>
                                <div class="col-md-6">
                                    {{ product.translation.name }}
                                </div>
                                <div class="col-md-3">
                                    <small>code - </small> {{ product.code }}
                                </div>
                                <div class="col-md-1">
                                    <a href="#" class="btn btn-sm btn-primary" @click="addProductToSet(product)"><i class="fa fa-plus"></i></a>
                                </div> <br> <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>

import { bus } from '../../../app';
import { VueNestable, VueNestableHandle } from 'vue-nestable';

export default {
    props: ['collections_prop', 'langs'],
    data(){
        return {
            titles: [],
            maxDepth: 1,
            collections: this.collections_prop,
            currentCollection: [],
            currentSet: [],
            currentProduct: [],
            searchProducts: [],
            search: '',
            message: false,
            code: '',
            price: '',
            discount: '',
            attachments : [],
            data : new FormData(),
        }
    },
    mounted(){
        this.setTitles();
        bus.$on('currentSet', response => {
            this.searchProducts = [];
            this.search = '';
            this.message = false;
            this.currentSet = response;
        });
        bus.$on('currentProduct', response => {
            this.currentProduct = response;
        });
        bus.$on('updateCollections', response => {
            this.change();
        });
    },
    methods: {
        setCurrent(collection){
            this.currentCollection = collection;
        },
        setTitles(){
            let ret = [];
            this.langs.forEach(function(entry){
                ret[entry.id] = '';
            })
            this.titles = ret;
        },
        change(){
            axios.post('/back/product-collections/changeCollections', {collections: this.collections})
                .then(response => {
                    this.collections = response.data;
                })
        },
        openSets(id){
            $('.sets').not('#set' + id).hide();
            if ($('#set' + id).css('display') == 'none') {
                $('#set' + id).show();
            }else{
                $('#set' + id).hide();
            }

            bus.$emit('updateSets' + id);
        },
        remove(item){
            if (confirm("Do you really want to remove this collection?")) {
                axios.post('/back/product-collections/removeCollection', {collection_id: item.id})
                    .then(response => {
                        this.collections = response.data;
                    })
                    .catch(e => { console.log('error remove collections') });
            }
        },
        validateTitles(){
            let ret = true;
            this.titles.forEach(function(entry){
                if (entry.length == 0) {
                    return ret = false;
                }
            })
            return ret;
        },
        addSave(){
            if (this.validateTitles()) {
                axios.post('/back/product-collections/add-new-collection', {titles: this.titles})
                    .then(response => {
                        this.collections = response.data.collections;
                        this.setTitles();
                    })
                    .catch(e => {
                        console.log('error add new collections');
                    });
            }
        },
        addEdit(){
            if (this.validateTitles()) {
                axios.post('/back/product-collections/add-new-collection', {titles: this.titles})
                    .then(response => {
                        window.location.href = window.location.origin + '/back/product-collections/'+ response.data.collection.id +'/edit';
                    })
                    .catch(e => {
                        console.log('error add and edit new collections');
                    });
            }
        },
        addSaveSet(){
            if (this.validateTitles()) {
                axios.post('/back/product-collections/add-new-set', {titles: this.titles, code: this.code, price: this.price, discount: this.discount, collection_id: this.currentCollection.id})
                    .then(response => {
                        bus.$emit('updateSets' + this.currentCollection.id);
                        this.collections = response.data.collections;
                        this.setTitles();
                    })
                    .catch(e => {
                        console.log('error add new collections');
                    });
            }

        },
        addEditSet(){
            if (this.validateTitles()) {
                axios.post('/back/product-collections/add-new-set', {titles: this.titles, code: this.code, price: this.price, discount: this.discount, collection_id: this.currentCollection.id})
                    .then(response => {
                        window.location.href = window.location.origin + '/back/sets/'+ response.data.set.id +'/edit';
                    })
                    .catch(e => {
                        console.log('error add and edit new collections');
                    });
            }
        },
        searchProduct(){
            this.message = false;
            if (this.search.length > 2) {
                axios.post('/back/product-collections/search-product', {search: this.search, set_id: this.currentSet.id})
                    .then(response => {
                        this.searchProducts = response.data;
                    })
                    .catch(e => {
                        console.log('error search product');
                    });
            }else{
                this.searchProducts = [];
            }
        },
        addProductToSet(product){
            axios.post('/back/product-collections/add-product-to-set', { product_id: product.id, set_id: this.currentSet.id })
                .then(response => {
                    bus.$emit('updateProducts' + this.currentSet.id, response.data.products);
                    bus.$emit('updateSets' + this.currentSet.collection_id);
                    this.searchProducts = [];
                    this.search = '';
                    this.message = 'Success, <b>' + product.translation.name + '</b> was added to <b>' + this.currentSet.translation.name + '</b>';
                })
                .catch(e => {
                    console.log('error search product');
                });
        },
        uploadFieldChange(e) {
            var name = e.target.name;
            var files = e.target.files || e.dataTransfer.files;

            if (name.length > 0) {
                $('.svbtn').removeClass('btn-warning');
                $('.svbtn').addClass('btn-primary');
                $('#' + name).removeClass('btn-primary');
                $('#' + name).addClass('btn-warning');
            }

            if (!files.length)
                return;
            for (var i = files.length - 1; i >= 0; i--) {
                this.attachments.push(files[i]);
            }
            document.getElementById("attachments").value = [];
        },
        uploadSetImage(setId){
            $('.svbtn').removeClass('btn-warning');
            $('.svbtn').addClass('btn-primary');

            this.loading = true;
            this.prepareFields();
            this.data.append('set_id', setId);

            var config = {
                headers: { 'Content-Type': 'multipart/form-data' } ,
                onUploadProgress: function(progressEvent) {
                    this.percentCompleted = Math.round( (progressEvent.loaded * 100) / progressEvent.total );
                    this.$forceUpdate();
                }.bind(this)
            };
            axios.post('/back/auto-upload-set-image-upload', this.data, config)
                .then(response => {
                    this.currentProduct = response.data.product;
                    this.resetData();
                })
                .catch(e => {
                    console.log('error load products');
                })
        },
        prepareFields() { //image prepare field
            if (this.attachments.length > 0) {
                for (var i = 0; i < this.attachments.length; i++) {
                    let attachment = this.attachments[i];
                    this.data.append('attachments[]', attachment);
                }
                this.data.append('product_id', this.currentProduct.id);
            }
        },
        resetData() {
            this.data = new FormData();
            this.attachments = [];
        },
        removeSetProductImage(imageId){
            axios.post('/back/product-collections/remove-set-product-image', { id: imageId })
                .then(response => {
                    this.currentProduct = response.data;
                })
                .catch(e => {
                    console.log('error remove set product image');
                });
        },
    },
    components: {
        VueNestable,
        VueNestableHandle
    },

}
</script>

<style lang="css" scoped>
    .sets{
        margin-left: 40px;
        display: none;
        background-color: #AAA;
    }
</style>
