<template>

<div class="top-bar flex">
    <div class="loading" v-if="loading"><div class="lds-ripple"><div></div><div></div></div></div>
    <div class="col-md-3">
        <select class="select-top-bar" v-model="selected" @change="redirectPage">
            <option value="0">---</option>
            <option :value="category.id"  v-for="category in categories">{{ category.translation.name }}</option>
        </select>
     </div>
     <div class="col-md-3">
         <a href="#"  :class="['btn btn-sm btn-block', changed ? 'btn-warning' : 'btn-primary']" @click="save"><i class="fa fa-save"></i> Save changes</a>
     </div>
     <div class="col-md-3">
         <a href="/back" class="btn btn-primary btn-sm btn-block" @click="redirectBack"><i class="fa fa-backward"></i> Back to products</a>
     </div>
     <div class="col-md-3 form-inline">
        <input type="text" class="input-top-bar" v-model="search" placeholder="Search...">
        <button type="submit" class="btn btn-primary btn-sm" @click="searchProduct"><i class="fa fa-search"></i> </button>
        <button type="submit" class="btn btn-primary btn-sm" @click="cancelSearch"><i class="fa fa-close"></i> </button>
     </div>
</div>


</template>

<script>
    import { bus } from '../../app';

    export default {
        props: ['categories', 'current'],
        data(){
            return {
                selected : this.current ? this.current.id : 0,
                changed : false,
                items : [],
                loading : false,
                search : '',
            }
        },
        mounted(){
            bus.$on('documentChange', data => {
                this.changed = true;
                if(this.items.indexOf(data) === -1) {
                    this.items.push(data);
                }
            });
            bus.$on('clearSearch', data => {
                this.search = '';
            })
            bus.$on('startLoading', data => {
                this.loading = true;
            })
            bus.$on('endLoading', data => {
                let vm = this
                setTimeout(function() {
                    vm.loading = false;
                }, 0);
            })
        },
        methods: {
            redirectPage(e){
                window.location.href = window.location.origin + '/back/auto-upload?category=' + e.target.value;
            },
            redirectBack(e){
                window.location.href = window.location.origin + '/back/products/category/' + this.selected;
            },
            searchProduct(){
                bus.$emit('search', this.search);
            },
            cancelSearch(){
                this.search = "";
                bus.$emit('cancelSearch');
            },
            save(){
                if(this.items.indexOf('new') !== -1) {
                    bus.$emit('create');
                }
                this.items.forEach(function(entry){
                    bus.$emit('save' + entry);
                })
                this.items = [],
                this.changed = false;
            },
        }
    }

</script>
