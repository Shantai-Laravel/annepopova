<template>

    <div class="wish wishButton" @click="addToFavorites"></div>

</template>

<script>
import { bus } from '../../../app.js'

export default {
  props: ['product', 'favorites'],
  data() {
      return {
          added : false,
      }
  },
  mounted(){},
  methods: {
      // add product to wishlist
      addToFavorites(e){
          e.target.classList.toggle('wishAdded');
          axios.post('/'+ this.$lang +'/add-to-favorites', { product_id : this.product.id })
              .then(response => {
                  bus.$emit('updateWishBox', response.data.products);
                  bus.$emit('ga-event-addToFavorites', this.product);
                  $('.buttWish').addClass('flash');
                  setTimeout(function(){
                      $('.buttWish').removeClass('flash');
                  }, 500);
              })
              .catch(e => {  console.log('add favorites error') });
      },
      inArray() {
        var ret = false;
        var length = this.favorites.length;
        for(var i = 0; i < length; i++) {
            if(this.favorites[i] == this.product.id) {
                ret = 'wishAdded'
            };
        }
        this.added = ret;
    }
  }
}
</script>
