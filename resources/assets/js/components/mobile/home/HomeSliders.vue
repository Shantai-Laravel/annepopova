<template>

    <div>
        <div v-for="(item, key) in items">
            <home-slider-collection-mob v-if="cols[key]" :colection="cols[key]" :site="site"></home-slider-collection-mob>
            <home-slider-category-mob  v-if="cats[key]" :category="cats[key]" :site="site"></home-slider-category-mob>
        </div>

        <!-- <div class="sniper-load" v-if="!ready">
            <img src="/images/loading-logo.gif">
        </div> -->
    </div>

</template>

<script>
export default {
    props: ['site'],
    data(){
        return {
            items : 0,
            currentItem: 'cat',
            cats: [],
            cols: [],
            pageCats: 0,
            pageCols: 0,
            last_page_cats: 1,
            last_page_cols: 1,
            loading: false,
            ready: false,
        }
    },
    mounted(){
        window.addEventListener('scroll', this.handleScroll);
        setTimeout(() => this.loadCategory(), 1200);
    },
    methods: {
        loadCategory(){
            this.loading = true;
            axios.post('/'+ this.$lang +'/'+ this.site +'/get-category-home?page=' + this.pageCats)
                .then(response => {
                      this.cats = this.cats.concat(response.data.cats.data);
                      this.cols = this.cols.concat(response.data.cols.data);
                      this.last_page_cats = response.data.cols.last_page;
                      this.pageCats = response.data.cols.current_page + 1;
                      this.items = this.items + 1;
                      this.loading = false;
                      this.ready = true;
                })
                .catch(e => {
                      this.loading = false;
                      this.ready = true;
                      console.log('loading category error.');
                })
        },
        loadCollection(){
            this.loading = true;
            this.currentItem = 'col';
            console.log('collection');
            this.loading = false;
        },
        // handle scroll, add more
        handleScroll(event){
            var scrollY = window.scrollY
            var visible = document.documentElement.clientHeight
            var pageHeight = document.documentElement.scrollHeight - 2000
            var bottomOfPage = visible + scrollY >= pageHeight
            var diff =  bottomOfPage || pageHeight < visible

            if (this.pageCats <= this.last_page_cats) {
                if (diff && !this.loading ) {
                    this.loadCategory();
                }
            }
        }
    }
}
</script>
