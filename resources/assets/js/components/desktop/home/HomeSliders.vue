<template>

    <div>
        <div v-for="(item, key) in items">
            <home-slider-collection v-if="cols[key]" :colection="cols[key]" :site="site"></home-slider-collection>
            <home-slider-category  v-if="cats[key]" :category="cats[key]" :site="site"></home-slider-category>
        </div>
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
        }
    },
    mounted(){
        window.addEventListener('scroll', this.handleScroll);
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
                })
                .catch(e => {
                      this.loading = false;
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
            var pageHeight = document.documentElement.scrollHeight - 1500
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
