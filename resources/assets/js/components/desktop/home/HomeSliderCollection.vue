<template>

    <section class="collection">
        <h4>
          {{ colection.translation.name }} {{ trans.vars.General.collection }}
        </h4>
        <div class="">
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
            <div class="collectionSlider">
                <slick ref="slick" :options="slickOptions">
                    <a
                        :href="'/' + $lang + '/' + site + '/collection/' + colection.alias + '?order=' + set.id"
                        class="item"
                        v-for="set in colection.sets "
                    >
                        <img v-if="set.main_photo" :src="'/images/sets/og/' + set.main_photo.src" alt="" />
                        <img v-else src="/images/no-image-ap.jpg" alt="" />
                    </a>
                </slick>
            </div>
        </div>
        <aside :style="{ backgroundImage: 'url(\'' + setImage(colection.banner) + '\')' }">
            <p>{{ colection.translation.subtitle }}</p>
            <p>Here we go</p>

        </aside>
        <div class="dan">
          <a :href="'/' + $lang + '/' + site + '/collection/' + colection.alias" class="butt">
              <span>{{ trans.vars.General.shopCollection }} <b></b><b></b><b></b></span>
          </a>
        </div>
    </section>

</template>

<script>

import Slick from 'vue-slick';
export default {
    components: { Slick },
    props: ['colection', 'site'],
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
        setImage(banner){
            if (banner) {
                return '/images/collections/' + banner;
            }else{
                if (this.site == 'homewear') {
                    return '/images/APL-collection-desktop-background.JPG';
                }else{
                    return '/images/APJ-collection-desktop-background.JPG';
                }
            }
        },
    }
}
</script>
