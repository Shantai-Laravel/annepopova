<template>

    <div class="productInner oneProduct">
    <hooper :infiniteScroll="true" :style="'height:' + imageHeight + 'px'">
        <slide  v-if="product.images"
                v-for="image in product.images">
                <img :src="'/images/products/sm/' + image.src" alt="">
        </slide>
        <hooper-pagination slot="hooper-addons"></hooper-pagination>
      </hooper>

      <div class="innerContainer bag2">
          <div class="descriptionInner">
              <div class="info-block">
              <p class="name">{{ product.translation.name }}</p>
              <div class="price">
                  <span>{{ product.personal_price.price }}
                      <span v-if="product.discount == 0">{{ $currency }}</span>
                  </span>
                  <span v-if="product.discount > 0">
                      {{ product.personal_price.old_price }}
                  </span>
                  <span class="currency" v-if="product.discount > 0">{{ $currency }}</span>
              </div>

              <div class="moreDetails">
                  <div class="descriptionInner oneProdDescr">
                      <div class="inner-block">
                          <div class="sizeBtn" @click="openSizesBlock()" v-if="site == 'homewear'">{{ size }}</div>
                          <div class="cartBtn" @click="openSizesBlock()" v-if="!addedToCart">
                              <span>{{ trans.vars.Cart.cartAddTo }} <svg width="24px" height="32px" viewBox="0 0 24 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="Symbols" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd"><g id="project_20200129_150142" transform="translate(-1529.000000, -61.000000)"><g transform="translate(-359.000000, -1055.000000)" id="Group-8"><g id="Group-16" transform="translate(356.500000, 1055.000000)"><g id="Shape-2" transform="translate(1532.050781, 61.363636)"><path d="M4.56105523,8.4535472 L18.8601019,8.10653496 C21.0685906,8.0529389 22.9023727,9.79982458 22.9559687,12.0083133 C22.9589832,12.1325272 22.9562084,12.256814 22.9476534,12.3807696 L21.9919921,26.2275651 C21.8472458,28.324827 20.1037358,29.9521531 18.0014848,29.9521531 L5.04927707,29.9521531 C2.88525509,29.9521531 1.11363495,28.2311249 1.0509553,26.0680108 L0.65977772,12.5682275 C0.59579128,10.3600154 2.33402965,8.5180345 4.54224179,8.45404806 C4.54851254,8.45386635 4.5547837,8.4536994 4.56105523,8.4535472 Z" id="Rectangle" fill="none" opacity="0.916666667" transform="translate(11.896687, 18.976077) rotate(-360.000000) translate(-11.896687, -18.976077) "></path> <path d="M22.5214956,7.96933632 L18.0691228,7.96933632 L18.0691228,6.17062023 C18.0691228,2.76285464 15.2646973,-1.11910481e-13 11.8056573,-1.11910481e-13 C8.34639139,-1.11910481e-13 5.5419659,2.76285464 5.5419659,6.17062023 L5.5419659,7.96933632 L0.721725511,7.96933632 C0.323804812,7.97044937 0.00112980001,8.28789607 1.95399252e-14,8.68036342 L1.95399252e-14,27.7180369 C0.000903851575,29.6810416 1.61586067,31.2720594 3.60817549,31.2727273 L19.6420505,31.2727273 C21.6345913,31.2720594 23.2495481,29.6810416 23.25,27.7180369 L23.25,8.68036342 C23.2454807,8.28656036 22.9207721,7.97000411 22.5214956,7.96933632 Z M6.75,6.20190826 C6.75,3.58021034 8.93286651,1.45454545 11.6248857,1.45454545 C14.3175905,1.45454545 16.5,3.58021034 16.5,6.20190826 L16.5,8 L6.75,8 L6.75,6.20190826 Z M21.75,27.6953403 C21.7464048,28.8665479 20.7846764,29.8148583 19.5973463,29.8181818 L3.6528784,29.8181818 C2.46509894,29.8148583 1.50337056,28.8665479 1.5,27.6953403 L1.5,9.45454545 L5.57566104,9.45454545 L5.57566104,12.3342628 C5.57566104,12.7251085 5.89721089,13.0419505 6.29313742,13.0419505 C6.68951332,13.0419505 7.01083843,12.7251085 7.01083843,12.3342628 L7.01083843,9.45454545 L16.6049779,9.45454545 L16.6049779,12.3342628 C16.6049779,12.7251085 16.926303,13.0419505 17.3226789,13.0419505 C17.7186054,13.0419505 18.0401552,12.7251085 18.0401552,12.3342628 L18.0401552,9.45454545 L21.75,9.45454545 L21.75,27.6953403 Z" id="Shape" fill="#42261D" fillRule="nonzero" transform="translate(11.625000, 15.636364) rotate(-360.000000) translate(-11.625000, -15.636364) "></path></g></g></g></g></g></svg></span>
                          </div>
                          <div class="cartBtn" v-if="addedToCart">
                              <a :href="'/' + $lang + '/cart'">{{ trans.vars.DetailsProductSet.view }} <svg width="24px" height="32px" viewBox="0 0 24 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="Symbols" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd"><g id="project_20200129_150142" transform="translate(-1529.000000, -61.000000)"><g transform="translate(-359.000000, -1055.000000)" id="Group-8"><g id="Group-16" transform="translate(356.500000, 1055.000000)"><g id="Shape-2" transform="translate(1532.050781, 61.363636)"><path d="M4.56105523,8.4535472 L18.8601019,8.10653496 C21.0685906,8.0529389 22.9023727,9.79982458 22.9559687,12.0083133 C22.9589832,12.1325272 22.9562084,12.256814 22.9476534,12.3807696 L21.9919921,26.2275651 C21.8472458,28.324827 20.1037358,29.9521531 18.0014848,29.9521531 L5.04927707,29.9521531 C2.88525509,29.9521531 1.11363495,28.2311249 1.0509553,26.0680108 L0.65977772,12.5682275 C0.59579128,10.3600154 2.33402965,8.5180345 4.54224179,8.45404806 C4.54851254,8.45386635 4.5547837,8.4536994 4.56105523,8.4535472 Z" id="Rectangle" fill="none" opacity="0.916666667" transform="translate(11.896687, 18.976077) rotate(-360.000000) translate(-11.896687, -18.976077) "></path> <path d="M22.5214956,7.96933632 L18.0691228,7.96933632 L18.0691228,6.17062023 C18.0691228,2.76285464 15.2646973,-1.11910481e-13 11.8056573,-1.11910481e-13 C8.34639139,-1.11910481e-13 5.5419659,2.76285464 5.5419659,6.17062023 L5.5419659,7.96933632 L0.721725511,7.96933632 C0.323804812,7.97044937 0.00112980001,8.28789607 1.95399252e-14,8.68036342 L1.95399252e-14,27.7180369 C0.000903851575,29.6810416 1.61586067,31.2720594 3.60817549,31.2727273 L19.6420505,31.2727273 C21.6345913,31.2720594 23.2495481,29.6810416 23.25,27.7180369 L23.25,8.68036342 C23.2454807,8.28656036 22.9207721,7.97000411 22.5214956,7.96933632 Z M6.75,6.20190826 C6.75,3.58021034 8.93286651,1.45454545 11.6248857,1.45454545 C14.3175905,1.45454545 16.5,3.58021034 16.5,6.20190826 L16.5,8 L6.75,8 L6.75,6.20190826 Z M21.75,27.6953403 C21.7464048,28.8665479 20.7846764,29.8148583 19.5973463,29.8181818 L3.6528784,29.8181818 C2.46509894,29.8148583 1.50337056,28.8665479 1.5,27.6953403 L1.5,9.45454545 L5.57566104,9.45454545 L5.57566104,12.3342628 C5.57566104,12.7251085 5.89721089,13.0419505 6.29313742,13.0419505 C6.68951332,13.0419505 7.01083843,12.7251085 7.01083843,12.3342628 L7.01083843,9.45454545 L16.6049779,9.45454545 L16.6049779,12.3342628 C16.6049779,12.7251085 16.926303,13.0419505 17.3226789,13.0419505 C17.7186054,13.0419505 18.0401552,12.7251085 18.0401552,12.3342628 L18.0401552,9.45454545 L21.75,9.45454545 L21.75,27.6953403 Z" id="Shape" fill="#42261D" fillRule="nonzero" transform="translate(11.625000, 15.636364) rotate(-360.000000) translate(-11.625000, -15.636364) "></path></g></g></g></g></g></svg></a>
                          </div>
                          <a href="#" class="availableSizesBtn" v-if="site == 'homewear'" @click.prevent="openSizesBlock()">{{ trans.vars.DetailsProductSet.viewAvailableSizes }}</a>
                          <div class="title" @click="openDescriptionBlock()">{{ trans.vars.DetailsProductSet.description }}</div>
                          <div v-if="descriptionInneBlock" v-html="product.translation.body"></div>
                          <div class="additional-block">
                              <h5>{{ trans.vars.DetailsProductSet.storkaSizes }}</h5>
                              <p>{{ trans.vars.DetailsProductSet.storkaSizesText }} <a href="#" data-toggle="modal" data-target="#modalSize">{{ trans.vars.DetailsProductSet.sizeGuide }}</a></p>

                              <h5>{{ trans.vars.DetailsProductSet.storkaShippingTitle }}</h5>
                              <p><a href="#" data-toggle="modal" data-target="#modalShipping">{{ trans.vars.DetailsProductSet.weShip }}</a> {{ trans.vars.DetailsProductSet.shipRomaniaEU }}</p>

                              <h5>{{ trans.vars.DetailsProductSet.storkaReturnsTitle }}</h5>
                              <p><a href="#" data-toggle="modal" data-target="#modalShipping">{{ trans.vars.DetailsProductSet.canReturn }}</a> {{ trans.vars.DetailsProductSet.returnDays }}</p>

                              <h5>{{ trans.vars.DetailsProductSet.storkaMoreDetailsTitle }}</h5>
                              <p>{{ trans.vars.DetailsProductSet.moreDetailsShipping }} <a href="#" data-toggle="modal" data-target="#modalShipping">{{ trans.vars.Contacts.here }}</a> </p>

                          </div>
                      </div>
                  </div>
              </div>
              </div>
              <div class="sliderSimilars">
                  <h5 class="similar-title">{{ trans.vars.DetailsProductSet.similarProducts }}</h5>
                  <hooper :infiniteScroll="true" :style="'height:' + imageHeight + 'px'">
                      <slide   v-for="productSimilar in similars"
                                v-if="similars && productSimilar.category">
                              <a :href="'/' + $lang + '/' + site + '/catalog/' + productSimilar.category.alias + '/' + productSimilar.alias">
                                  <img :src="'/images/products/sm/' + productSimilar.main_image.src" alt="">
                              </a>
                      </slide>
                      <hooper-navigation slot="hooper-addons"></hooper-navigation>
                    </hooper>
              </div>

              <div class="info-block">

                  <div class="moreDetails">
                      <div class="descriptionInner oneProdDescr">
                          <div class="inner-block">
                              <h5>{{ trans.vars.DetailsProductSet.gotQuestion }}</h5>
                              <p>{{ trans.vars.DetailsProductSet.callServiceSupport }} +40 31 229 4664</p>
                              <a href="tel:+40312294664" class="cartButton">
                                <span> {{ trans.vars.DetailsProductSet.callNow }} </span>
                              </a>
                              <p>{{ trans.vars.DetailsProductSet.contactMessenger }}</p>
                              <div class="elfsight-app-0a2fd1f8-6a2d-41a0-a523-c48e0bfe6480"></div>
                          </div>
                      </div>
                  </div>
              </div>

          </div>
      </div>

      <div    :class="['cancelBackround', 'cancelBackround2', cancelBackround2 == false ? 'hide-block' : '']"
              @click="clooseSizesBlock2()"
              style="z-index:3;"
          >
          <div class="sizeContainerProduct" :style="{ top: offsetTopSizes + 'px'}">
              <div class="head">
                  <p>Select size</p>
                  <p data-toggle="modal" data-target="#modalSize" class="sizeGuide">{{ trans.vars.DetailsProductSet.sizeGuide }}</p>
              </div>

              <div class="sizeCheckContainer">
                  <label class="sizeCheck" v-for="subproduct in product.subproducts">
                      <input type="radio" name="size" :disabled="subproduct.warehouse.stock == 0"
                                                      :value="subproduct.parameter_value.translation.name"
                                                      @click="addToCart(subproduct)"
                                                      >
                      <span class="check">{{ subproduct.parameter_value.translation.name }}
                          <span class="count" v-if="subproduct.warehouse.stock == 0">out of stock</span>
                          <span class="count" v-if="subproduct.warehouse.stock > 0">in stock</span>
                      </span>
                  </label>
              </div>

          </div>
      </div>

    </div>

</template>

<script>
import { bus } from '../../../app';
import { VueHammer } from 'vue2-hammer'
import { Hooper, Slide, Pagination as HooperPagination, Navigation as HooperNavigation } from 'hooper';
import 'hooper/dist/hooper.css';
import Slick from 'vue-slick'

Vue.use(VueHammer)

export default {
    components: { Slick, Hooper, Slide, HooperPagination, HooperNavigation},
    props: ['product', 'category_id', 'other_products', 'site', 'similars'],
    data(){
        return {
            imageHeight : 0,
            addedToCart : false,
            descriptionInneBlock: true,
            size: 'size',
            translateYSizes : 0,
            cancelBackround2 : false,
            offsetTopSizes    : 0,
        }
    },
    mounted(){
        // this.imageHeight = 0.78 * window.innerHeight;
        this.imageHeight = window.innerWidth / 0.75 + 30;
        this.offsetTopSizes = window.innerHeight - $('.sizeContainerProduct').height();
    },
    methods: {
        clooseSizesBlock2(){
            this.translateYSizes = '700';
            this.cancelBackround2 = false;
        },
        openSizesBlock(){
            if (this.product.subproducts.length > 0) {
                this.translateYSizes = $('.oneProductContent').height() - $('.sizeContainerProduct').height() + 70;
                this.cancelBackround2 = true;
                this.offsetTopSizes = window.innerHeight - $('.sizeContainerProduct').height();
            }else{
                this.addProductToCartAction(this.product);
            }

        },
        openDescriptionBlock(){
            if (this.descriptionInneBlock == true) {
                this.descriptionInneBlock = false;
            }else{
                this.descriptionInneBlock = true;
            }
        },
        addToCart(subproduct) {
            this.subproduct = subproduct;
            if (this.product.subproducts.length > 0) {
                if (this.subproduct.warehouse.stock > 0) {
                    this.addProductToCartAction(this.product)
                }
            } else {
                this.addProductToCartAction(this.product)
            }
        },
        addProductToCartAction(product) {
            let subproduct = 0;
            if (this.subproduct) {
                subproduct = this.subproduct.id;
            }
            axios.post('/' + this.$lang + '/' + this.site + '/add-product-to-cart', {
                    productId: this.product.id,
                    subproductId: subproduct,
                })
                .then(response => {
                    this.cancelBackround2 = false;
                    this.translateYSizes = '800';
                    this.addedToCart = true;
                    this.size = this.subproduct.parameter_value.translation.name;

                    this.addAnimation("cart", this.$el);
                    bus.$emit('updateCartBox', {data: response.data});
                    bus.$emit('updateCart', this.subproduct.code);
                    bus.$emit('ga-event-addToCart', {product: this.product, actionField: this.product.translation.name });

                    $('.buttCart').addClass('flash');
                    setTimeout(function() {
                        $('.buttCart').removeClass('flash');
                    }, 500);
                })
                .catch(e => {
                    console.log(e);
                });
        },
        addAnimation(el, target) {
            document.getElementById(el).classList.add("heartBeat");

            if (el == "cart") {
                $(target).find(".addToCart").addClass("elAdded");
                $('.cartBtn').addClass('heartBeat');
            } else if (el == "wish") {
                $(target).find(".addToWish").addClass("elAdded");
            } else {
                return null
            }

            setInterval(() => {
                document.getElementById(el).classList.remove("heartBeat");
                $('.cartBtn').removeClass('heartBeat');

            }, 2500)
        },
    },
}
</script>

<style lang="css" scoped>
    .hooper{
        height: auto;
    }
    .hooper-next, .hooper-prev{
        top: 22% !important;
    }
    .innerContainer{
        height: auto;
    }
    .sizeContainerProduct{
        bottom: auto;
    }
    .cancelBackround2{
        z-index: 5;
        width: 100%;
        height: 100vh;
        top: 0;
        left: 0;
        position: fixed;
        background-color: rgba(0, 0, 0, 0.1);
    }
    .hide-block{
        left: -100%;
    }
    .oneProduct{
        padding: 0 !important;
    }
    .info-block{
        padding: 0 15px;
    }
    .productInner{
        width: 100%;
    }
    .similar-title{
        margin-left: 15px;
    }
</style>
