<template>

    <div class="filterInner" id="filterInner">
        <div class="filterCat" v-for="parameter in category.params" v-if="parameter.property.in_filter == 1">
            <div class="filterButton" v-if="parameter.property.multilingual == 1">{{ parameter.property.translation.name }}</div>
            <ul class="options show">
                <label class="checkContainer" v-for="value in parameter.property.parameter_values" v-if="parameter.property.multilingual == 1 && filter.includes(value.id)">
                {{ value.translation.name }}
                <input type="checkbox" class="filter-checkbox-category" @change="setProperty" :name="parameter.property.id" :value="value.id">
                <span class="check"></span>
                </label>
            </ul>
        </div>
        <div class="filterCat" v-for="parameter in category.params" v-if="parameter.property.in_filter == 1">
            <div class="filterButton" v-if="parameter.property.multilingual == 0">{{ parameter.property.translation.name }}</div>
            <ul class="options show">
                <label class="checkContainer" v-for="value in parameter.property.parameter_values" v-if="parameter.property.multilingual == 0 && filter.includes(value.id)">
                {{ value.trans_data.name }}
                <input type="checkbox" class="filter-checkbox-category" @change="setProperty" :name="parameter.property.id" :value="value.id">
                <span class="check"></span>
                </label>
            </ul>
        </div>
        <div class="filterFooter">
            <button @click="removeFilter">{{ trans.vars.TehButtons.clearFilter }}</button>
            <button id="closeFilter">{{ trans.vars.TehButtons.btnClose }}</button>
        </div>
    </div>

</template>

<script>
    import { bus } from '../../app';

    export default {
        props: ['category', 'site'],
        data() {
            return {
                filter: [],
                priceMin:   0,
                priceMax:   1000000,
                categories: [],
                properties: [],
                page: 1,
                last_page: false,
                loading: false,
                products: false,
                repeted: false,
            };
        },
        mounted(){
            axios.interceptors.request.use(config => {
                bus.$emit('showPreloader');
                return config;
            }, error => {
                return Promise.reject(error);
            });

            axios.interceptors.response.use(response => {
                bus.$emit('hidePreloader');
                return response;
            }, error => {
                this.loading = false
                return Promise.reject(error);
            });

            this.setDefaultCategories();
            this.filterProducts();
            this.setDefaultFilter();

            bus.$on('handleScroll', data => {
                this.handleScroll(data)
            })
        },
        methods: {
            setDefaultCategories(){
                let ret = [];
                if (this.category.children.length > 0) {
                    this.category.children.forEach(function(entry, key){
                        ret[key] = entry.id;
                    })
                    this.categories = ret;
                }else {
                    this.categories = [this.category.id];
                }
            },
            filterCategory(id){
                this.categories = [id];
                this.page = 0;
                this.filterProducts();
            },
            filterPrice(){
                this.page = 0;
                this.filterProducts();
            },
            filterProducts(loadMore = 0){
                this.loading = true;
                axios.post('/'+ this.$lang + '/' + this.site +'/filter?page=' + this.page, {
                        priceMin :  this.priceMin,
                        priceMax :  this.priceMax,
                        categories : this.categories,
                        category:   this.category,
                        properties : this.properties,
                    })
                    .then(response => {
                        this.last_page = response.data.last_page;
                        this.page = response.data.current_page + 1;
                        if (this.products === false) {
                            this.products = true;
                            bus.$emit('sendInitProducts', response.data);
                        }else{
                            if (loadMore == 1) {
                                bus.$emit('loadMoreFilterProducts', response.data);
                            }else{
                                bus.$emit('filterProducts', response.data.data);
                            }
                        }
                        this.loading = false;
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            setProperty(e){
                if (e.target.checked == true) {
                    this.properties.push({'name': e.target.name, 'value': e.target.value,});
                }else{
                    this.properties = this.removeFromObject(this.properties, e.target.name, e.target.value);
                }
                this.page = 0;
                this.filterProducts();
            },
            setDefaultFilter(){
                axios.post('/'+ this.$lang + '/' + this.site +'/setDefaultFilter', {
                        category: this.category,
                    })
                    .then(response => {
                        this.filter = response.data.parameters;
                        this.priceMin = response.data.prices.min;
                        this.priceMax = response.data.prices.max;
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            removeFromObject(object, name, value){
                object.filter(function(element, key){
                    if ((element.name == name) && (element.value == value)){
                        object.splice(key, 1);
                    }
                });
                return object;
            },
            handleScroll(data){
                let lastPage;

                if (this.last_page === false) {
                    lastPage = data.last_page;
                }else{
                    lastPage = this.last_page;
                }

                if (this.page <= lastPage) {

                    let bottomOfPage = data.visible + data.scrollY >= data.pageHeight
                    let diff =  bottomOfPage || data.pageHeight < data.visible

                    if (diff && !this.loading ) {
                        if (this.page == 0) {
                            this.filterProducts();
                        }else{
                            this.filterProducts(1);
                        }
                    }
                }
            },
            removeFilter(){
                location.reload();
            }
        },
    }
</script>
