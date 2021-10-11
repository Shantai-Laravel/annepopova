<template>

<section class="collection">
    <h4>{{ category.translation.name }}</h4>
    <aside :style="{ backgroundImage: 'url(\'' + setImage(category.banner_desktop) + '\')' }">
        <p>{{ category.translation.subtitle }}</p>
        <p>Here we go</p>

    </aside>
    <div>

      <div class="nextBanner slick-next">
          <div id="container">
              <div id="halfclip">
                  <div class="halfcircle" id="clipped"></div>
              </div>
              <div class="halfcircle" id="fixed"></div>
          </div>
      </div>
      <div class="prevBanner slick-prev">
          <div id="container">
              <div id="halfclip">
                  <div class="halfcircle" id="clipped"></div>
              </div>
              <div class="halfcircle" id="fixed"></div>
          </div>

      </div>
        <!-- <div class="categorySlider"> -->
        <div class="collectionSlider">
            <slick
                ref="slick"
                :options="slickOptions">
                <!-- <div class="oneProduct" v-for="product in category.products" :data-productid="product.code"> -->
                    <a
                        :href="'/' + $lang + '/' + site + '/catalog/' + category.alias + '/' + product.alias"
                        class="item"
                        v-for="product in category.products" :data-productid="product.code"
                        @click="viewProductGA(product)"
                    >
                        <img v-if="product.main_image" :src="'/images/products/og/' + product.main_image.src" alt="product" />
                        <img v-else src="/images/no-image-ap.jpg" alt="product" />
                    </a>
                    <!-- <a :href="'/' + $lang + '/' + site + '/catalog/' + category.alias + '/' + product.alias">{{ product.translation.name }}</a>
                    <div class="price">
                        <span>{{ product.personal_price.price }} <span v-if="product.discount < 1">{{ $currency }}</span> </span>
                        <span v-if="product.discount > 0">{{ product.personal_price.old_price }} {{ $currency }}</span>
                    </div> -->
                <!-- </div> -->
            </slick>
        </div>
    </div>
    <div class="dan">
      <a :href="'/' + $lang + '/' + site + '/catalog/' + category.alias" class="butt">
        <span>{{ trans.vars.General.shopCategory }} <b></b><b></b><b></b></span>
      </a>
    </div>
</section>

</template>

<script>

import Slick from 'vue-slick';
export default {
    components: { Slick },
    props: ['category', 'site'],
    data(){
        return {
            slickOptions: {
                dots: false,
                infinite: true,
                speed: 800,
                slidesToShow: 1,
                slidesToScroll: 1,
                variableWidth: false,
                arrows: false,
            },
        }
    },
    mounted(){},
    methods: {
        viewProductGA(product){
            // bus.$emit('ga-event-clickImpresions', {product: product, list: "View Recently"});
            bus.$emit('ga-event-viewProduct', {product: product, actionField: "HP "+ this.category.translation.name});

        },
        setImage(banner){
            if (banner) {
                return '/images/categories/og/' + banner;
            }else {
                return '/images/APL-APJ-cat-desktop-background.JPG';
            }
        },
    }
}
</script>
