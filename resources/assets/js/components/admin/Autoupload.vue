<template>

    <div class="inside-area" v-if="category">

        <div class="table100 ver7 m-b-110 tableFixHead" @scroll="handleScroll">
			<table data-vertable="ver1" class="table table-bordered">
				<thead>
					<tr class="row100 head">
                        <th class="column-id">#</th>
						<th class="column-id">Lang</th>
						<th class="column-text">Name <small>*requred</small> </th>
                        <th class="column-number">Site</th>
                        <th class="column-select">Category</th>
                        <th class="column-number">Code</th>
                        <!-- <th class="column-number">EAN</th> -->
                        <th class="column-select" v-for="param in category.params" v-if="dependeble !== param.property.id">
                            {{ param.property.translation.name }}
                        </th>
                        <!-- <th class="column-number">Stoc</th> -->
                        <!-- <th class="column-number">Discount</th> -->
                        <th class="column-number">Promotion</th>
                        <th class="column-button">Prices</th>
                        <th class="column-button">Images</th>
                        <th class="column-button">Images FB</th>
                        <th class="column-button">Video</th>
                        <th class="column-button">Subproducts</th>
                        <th class="column-button">Focare</th>
                        <th class="column-button">Sets</th>
                        <th class="column-button">Collections/Brands</th>
                        <th class="column-button">Similar Products</th>
						<!-- <th class="column-number">Price</th> -->

                        <th class="column-text">Description</th>
                        <th class="column-text">Body</th>
						<th class="column-text">Atributes</th>
					</tr>
				</thead>
				<tbody >
                    <create-autoupload
                            :category="category"
                            :langs="langs"
                            :promotions="promotions">
                    </create-autoupload>
                    <edit-autoupload v-for="(product, index) in products"
                            :category="category"
                            :index="index"
                            :prod="product"
                            :langs="langs"
                            :promotions="promotions"
                            :sets="sets"
                            :collections="collections"
                            :categories="categories"
                            :brands="brands"
                            :dillergroups="dillergroups"
                            >
                    </edit-autoupload>
				</tbody>
			</table>
		</div>


    </div>

</template>

<script>
import { bus } from '../../app';

export default {
    props: ['langs', 'category', 'promotions', 'sets', 'collections', 'brands', 'categories', 'dillergroups'],
    data(){
        return {
            products : [],
            page : 0,
            last_page: 0,
            loading: false,
            dependeble: this.category.property ? this.category.property.parameter_id : 0,
        }
    },
    mounted(){
        this.load();

        window.addEventListener('scroll', this.handleScroll);

        bus.$on('removeProduct', data => {
            this.products.splice(data, 1);
        })

        bus.$on('search', data => {
            this.search(data);
        })

        bus.$on('cancelSearch', data => {
            bus.$emit('startLoading');
            this.page = 0;
            this.load('update');
        })

        bus.$on('updatePage', data => {
            this.page = 0;
            this.load('update');
        })
    },
    methods: {
        load(event){
            this.loading = true;
            if (event === 'update') {
                this.products = [];
            }
            axios.post('/back/auto-upload-get-products?page=' + this.page, {category: this.category})
                .then(response => {
                    this.last_page = response.data.last_page;
                    this.page = response.data.current_page + 1;
                    this.loading = false;
                    this.products = this.products.concat(response.data.data);
                    bus.$emit('refresh');
                    bus.$emit('endLoading');
                })
                .catch(e => {
                    console.log('error load products');
                })
        },
        search(search){
            bus.$emit('startLoading');
            this.products = [];
            axios.post('/back/auto-upload-search', {category: this.category, search : search})
                .then(response => {
                    this.last_page = 0;
                    this.products = response.data;
                    bus.$emit('endLoading');
                })
        },
        handleScroll(event){
            if (this.page <= this.last_page) {
                var scrollY = window.scrollY
                var visible = document.documentElement.clientHeight
                var pageHeight = document.documentElement.scrollHeight - 500
                var bottomOfPage = visible + scrollY >= pageHeight
                var diff =  bottomOfPage || pageHeight < visible


                if (diff && !this.loading ) {
                    this.page = this.page;
                    this.load();
                }
            }
        }
    },
}
</script>

<style media="screen">
    ::-webkit-scrollbar {
        width: 7px;
        height: 15px;
    }
</style>
