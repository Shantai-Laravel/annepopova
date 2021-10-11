<template>

    <div class="slide-wrapp">
        <hooper v-if="imageHeight > 0" :vertical="true"
                :style="'height:' + imageHeight + 'px'"
                @beforeSlide="beforeSlide()"
                @afterSlide="afterSlide()"
                @slide="(event) => slide(event)"
            >
            <slide v-if="product.images.length" v-for="image in product.images">
                <div class="slider-one-product">
                    <img :src="'/images/products/sm/' + image.src">
                </div>
            </slide>

            <hooper-pagination slot="hooper-addons"></hooper-pagination>
        </hooper>

        <div :class="['description', descrMode]"  v-if="loading"
                v-hammer:pan.horizontal="onPanVertical"
                v-hammer:panmove="(event) => onMove(event)"
                v-hammer:panend="onPanEnd"
                :style="{ transform: 'translateY(-' + translateY + 'px)'}"
            >
            <div    :class="['close-block', closeBlockClass]"
                    @click="handleСurtain()"
                    v-hammer:pan.horizontal="onPanVertical"
                    v-hammer:panmove="(event) => onMove(event)"
                    v-hammer:panend="onPanEnd"
                    >
                  <span></span>
                  <span></span>
            </div>

            <div class="innerContainer bag2">
                <div class="descriptionInner">
                    <div class="heading" :style="{ height: bottomPosition }">
                        <p class="name">{{ product.translation.name }}</p>
                        <div class="price"><span>{{ product.personal_price.price }} {{ $currency }}</span></div>
                    </div>
                    <div class="moreDetails">
                        <div class="descriptionInner oneProdDescr">
                            <div class="inner-block"  :style="{ height: scrollBlockHeight + 'px' }">
                                <div class="cartBtn"  @click="openSizesBlock()" v-if="!addedToCart">
                                    <span>{{ trans.vars.Cart.cartAddTo }} <svg width="24px" height="32px" viewBox="0 0 24 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="Symbols" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd"><g id="project_20200129_150142" transform="translate(-1529.000000, -61.000000)"><g transform="translate(-359.000000, -1055.000000)" id="Group-8"><g id="Group-16" transform="translate(356.500000, 1055.000000)"><g id="Shape-2" transform="translate(1532.050781, 61.363636)"><path d="M4.56105523,8.4535472 L18.8601019,8.10653496 C21.0685906,8.0529389 22.9023727,9.79982458 22.9559687,12.0083133 C22.9589832,12.1325272 22.9562084,12.256814 22.9476534,12.3807696 L21.9919921,26.2275651 C21.8472458,28.324827 20.1037358,29.9521531 18.0014848,29.9521531 L5.04927707,29.9521531 C2.88525509,29.9521531 1.11363495,28.2311249 1.0509553,26.0680108 L0.65977772,12.5682275 C0.59579128,10.3600154 2.33402965,8.5180345 4.54224179,8.45404806 C4.54851254,8.45386635 4.5547837,8.4536994 4.56105523,8.4535472 Z" id="Rectangle" fill="none" opacity="0.916666667" transform="translate(11.896687, 18.976077) rotate(-360.000000) translate(-11.896687, -18.976077) "></path> <path d="M22.5214956,7.96933632 L18.0691228,7.96933632 L18.0691228,6.17062023 C18.0691228,2.76285464 15.2646973,-1.11910481e-13 11.8056573,-1.11910481e-13 C8.34639139,-1.11910481e-13 5.5419659,2.76285464 5.5419659,6.17062023 L5.5419659,7.96933632 L0.721725511,7.96933632 C0.323804812,7.97044937 0.00112980001,8.28789607 1.95399252e-14,8.68036342 L1.95399252e-14,27.7180369 C0.000903851575,29.6810416 1.61586067,31.2720594 3.60817549,31.2727273 L19.6420505,31.2727273 C21.6345913,31.2720594 23.2495481,29.6810416 23.25,27.7180369 L23.25,8.68036342 C23.2454807,8.28656036 22.9207721,7.97000411 22.5214956,7.96933632 Z M6.75,6.20190826 C6.75,3.58021034 8.93286651,1.45454545 11.6248857,1.45454545 C14.3175905,1.45454545 16.5,3.58021034 16.5,6.20190826 L16.5,8 L6.75,8 L6.75,6.20190826 Z M21.75,27.6953403 C21.7464048,28.8665479 20.7846764,29.8148583 19.5973463,29.8181818 L3.6528784,29.8181818 C2.46509894,29.8148583 1.50337056,28.8665479 1.5,27.6953403 L1.5,9.45454545 L5.57566104,9.45454545 L5.57566104,12.3342628 C5.57566104,12.7251085 5.89721089,13.0419505 6.29313742,13.0419505 C6.68951332,13.0419505 7.01083843,12.7251085 7.01083843,12.3342628 L7.01083843,9.45454545 L16.6049779,9.45454545 L16.6049779,12.3342628 C16.6049779,12.7251085 16.926303,13.0419505 17.3226789,13.0419505 C17.7186054,13.0419505 18.0401552,12.7251085 18.0401552,12.3342628 L18.0401552,9.45454545 L21.75,9.45454545 L21.75,27.6953403 Z" id="Shape" fill="#42261D" fillRule="nonzero" transform="translate(11.625000, 15.636364) rotate(-360.000000) translate(-11.625000, -15.636364) "></path></g></g></g></g></g></svg></span>
                                </div>
                                <div class="cartBtn" v-if="addedToCart">
                                    <a :href="'/' + $lang + '/cart'">{{ trans.vars.DetailsProductSet.view }} <svg width="24px" height="32px" viewBox="0 0 24 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="Symbols" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd"><g id="project_20200129_150142" transform="translate(-1529.000000, -61.000000)"><g transform="translate(-359.000000, -1055.000000)" id="Group-8"><g id="Group-16" transform="translate(356.500000, 1055.000000)"><g id="Shape-2" transform="translate(1532.050781, 61.363636)"><path d="M4.56105523,8.4535472 L18.8601019,8.10653496 C21.0685906,8.0529389 22.9023727,9.79982458 22.9559687,12.0083133 C22.9589832,12.1325272 22.9562084,12.256814 22.9476534,12.3807696 L21.9919921,26.2275651 C21.8472458,28.324827 20.1037358,29.9521531 18.0014848,29.9521531 L5.04927707,29.9521531 C2.88525509,29.9521531 1.11363495,28.2311249 1.0509553,26.0680108 L0.65977772,12.5682275 C0.59579128,10.3600154 2.33402965,8.5180345 4.54224179,8.45404806 C4.54851254,8.45386635 4.5547837,8.4536994 4.56105523,8.4535472 Z" id="Rectangle" fill="none" opacity="0.916666667" transform="translate(11.896687, 18.976077) rotate(-360.000000) translate(-11.896687, -18.976077) "></path> <path d="M22.5214956,7.96933632 L18.0691228,7.96933632 L18.0691228,6.17062023 C18.0691228,2.76285464 15.2646973,-1.11910481e-13 11.8056573,-1.11910481e-13 C8.34639139,-1.11910481e-13 5.5419659,2.76285464 5.5419659,6.17062023 L5.5419659,7.96933632 L0.721725511,7.96933632 C0.323804812,7.97044937 0.00112980001,8.28789607 1.95399252e-14,8.68036342 L1.95399252e-14,27.7180369 C0.000903851575,29.6810416 1.61586067,31.2720594 3.60817549,31.2727273 L19.6420505,31.2727273 C21.6345913,31.2720594 23.2495481,29.6810416 23.25,27.7180369 L23.25,8.68036342 C23.2454807,8.28656036 22.9207721,7.97000411 22.5214956,7.96933632 Z M6.75,6.20190826 C6.75,3.58021034 8.93286651,1.45454545 11.6248857,1.45454545 C14.3175905,1.45454545 16.5,3.58021034 16.5,6.20190826 L16.5,8 L6.75,8 L6.75,6.20190826 Z M21.75,27.6953403 C21.7464048,28.8665479 20.7846764,29.8148583 19.5973463,29.8181818 L3.6528784,29.8181818 C2.46509894,29.8148583 1.50337056,28.8665479 1.5,27.6953403 L1.5,9.45454545 L5.57566104,9.45454545 L5.57566104,12.3342628 C5.57566104,12.7251085 5.89721089,13.0419505 6.29313742,13.0419505 C6.68951332,13.0419505 7.01083843,12.7251085 7.01083843,12.3342628 L7.01083843,9.45454545 L16.6049779,9.45454545 L16.6049779,12.3342628 C16.6049779,12.7251085 16.926303,13.0419505 17.3226789,13.0419505 C17.7186054,13.0419505 18.0401552,12.7251085 18.0401552,12.3342628 L18.0401552,9.45454545 L21.75,9.45454545 L21.75,27.6953403 Z" id="Shape" fill="#42261D" fillRule="nonzero" transform="translate(11.625000, 15.636364) rotate(-360.000000) translate(-11.625000, -15.636364) "></path></g></g></g></g></g></svg></a>
                                </div>
                                <a href="#" class="availableSizesBtn" @click="openSizesBlock()">{{ trans.vars.DetailsProductSet.viewAvailableSizes }}</a>
                                <div class="title" @click="openDescriptionBlock()">description</div>
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
                    <div class="sliderContainer"></div>
                </div>
            </div>
        </div>

        <div class="sizeContainerProduct" :style="{ top: translateYSizes + 'px'}"
                                          v-hammer:panmove="(event) => onMoveSizesBlock(event)"
                                          v-hammer:panend="onPanEndSizesBlock">
            <div class="head">
                <p>Select size</p>
                <p data-toggle="modal" data-target="#modalSize" class="sizeGuide">Size Guide</p>
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

        <div    class="cancelBackround"
                v-if="cancelBackround"
                @click="handleСurtain()">
        </div>
        <div    class="cancelBackround"
                v-if="cancelBackround2"
                @click="clooseSizesBlock2()"
                style="z-index:3;">
        </div>
    </div>
</template>

<script>

import { bus } from '../../app';
import { VueHammer } from 'vue2-hammer'
import { Hooper, Slide, Pagination as HooperPagination } from 'hooper';
import 'hooper/dist/hooper.css';
Vue.use(VueHammer)

export default {
    components: { VueHammer, Hooper, Slide, HooperPagination },
    props: ['product', 'site'],
    data(){
        return {
            openDescription     : false,
            openSizeBlock       : false,
            descriptionHeight   : 0,
            imageHeight         : 0,
            bottomPosition      : 0,
            sizesHeight         : 0,
            translateY          : '-20',
            translateYSizes     : '800',
            loading             : false,
            descriptionInneBlock: true,
            cancelBackround     : false,
            cancelBackround2     : false,
            descrMode           : '',
            scrollBlockHeight   : 0,

            addedToCart         : false,
            closeBlockClass     : '',
        }
    },
    mounted(){
        this.imageHeight        = 0.78 * window.innerHeight;
        this.descriptionHeight  = 0.65 * window.innerHeight;
        this.bottomPosition     = 0.12 * window.innerHeight + 'px';
        this.sizesHeight        = $('.sizeContainerProduct').height();
        this.scrollBlockHeight  = this.descriptionHeight - 30;
        $("html").css({
            "touch-action": "pan-down"
        });
        let vm = this;
        setTimeout(function(){ vm.loading = true }, 2000);
    },
    methods: {
        handleСurtain(){
            if (this.descrMode == '') {
                this.translateY         = this.descriptionHeight;
                this.descrMode          = 'description-open';
                this.openDescription    = true;
                this.cancelBackround    = true;
                this.closeBlockClass    = 'arrow-icon';
                this.bottomPosition     = 'auto';
            }else{
                this.translateYSizes    = '800';
                this.translateY         = 0;
                this.descrMode          = '';
                this.cancelBackround    = false;
                this.closeBlockClass    = '';
                this.bottomPosition     = 0.15 * window.innerHeight + 'px';
            }
        },
        clooseSizesBlock2(){
            this.translateYSizes = '800';
            this.cancelBackround2 = false;
        },
        openSizesBlock(){
            this.cancelBackround2 = true;
            this.translateYSizes = $('.oneProductContent').height() - $('.sizeContainerProduct').height() - 40;
        },
        openDescriptionBlock(){
            if (this.descriptionInneBlock == true) {
                this.descriptionInneBlock = false;
            }else{
                this.descriptionInneBlock = true;
            }
        },
        slide(event){
            if (event.currentSlide == event.slideFrom && event.currentSlide > 0) {
                this.translateY = this.descriptionHeight;
                this.descrMode = 'description-open';
                this.openDescription = true;
                this.cancelBackround = true;
                this.closeBlockClass = 'arrow-icon';
                this.bottomPosition  = 'auto';
            }else{
                this.translateY = 0;
            }
        },
        beforeSlide(){},
        afterSlide(){},
        addToFavorites() {
            axios.post('/' + this.$lang + '/' + this.site + '/add-to-favorites', {
                    product_id: this.product.id
                })
                .then(response => {
                    this.addAnimation("wish", this.$el);
                    bus.$emit('updateWishBox', {
                        data: response.data
                    });
                    bus.$emit('ga-event-addToFavorites', this.product);
                    if (response.data.status == 'false') {
                        this.successWishModal = 'Produsul a fost adaugat in favorite cu succes.';
                    } else {
                        this.successWishModal = 'Produsul a fost sters din favorite.';
                    }
                })
                .catch(e => {
                    console.log('add favorites error')
                });
        },
        addToCart(subproduct) {
            this.subproduct = subproduct;
            if (this.product.subproducts.length > 0) {
                if (this.subproduct.warehouse.stock > 0) {
                    this.addProductToCartAction(this.product)
                } else {
                    $(this.$el).find("button").addClass("heartBeat");
                    setInterval(() => {
                        $(this.$el).find("button").removeClass("heartBeat")
                    }, 2000)
                }
            } else {
                this.addProductToCartAction(this.product)
            }
        },
        addProductToCartAction(product) {
            axios.post('/' + this.$lang + '/' + this.site + '/add-product-to-cart', {
                    productId: this.product.id,
                    subproductId: this.subproduct.id,
                })
                .then(response => {
                    this.cancelBackround2 = false;
                    this.translateYSizes = '800';
                    this.addedToCart = true;

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
            } else if (el == "wish") {
                $(target).find(".addToWish").addClass("elAdded");
            } else {
                return null
            }

            setInterval(() => {
                document.getElementById(el).classList.remove("heartBeat");
            }, 2000)
        },
        onMove(event){
            if (this.openDescription == false) {
                if (event.direction == 8) {
                    this.translateY = this.descriptionHeight;
                    this.descrMode = 'description-open';
                    this.cancelBackround = true;
                    this.closeBlockClass = 'arrow-icon';
                    this.bottomPosition  = 'auto';
                }
            }
        },
        onPanEndSizesBlock(){
            if (this.openSizeBlock == false) {
                this.openSizeBlock = true;
            }else{
                this.openSizeBlock = false
            }
        },
        onPanEnd(){
            if (this.openDescription == false) {
                this.openDescription = true;
            }else{
                this.openDescription = false
            }
        },
        onPanVertical(){
            console.log('onPanVertical');
        },
    },
}
</script>

<style lang="css" scoped>
    .slider-one-product img{
        width: calc(100vw - 30px) !important;
    }
    .slider-one-product{
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        height: calc(var(--vh, 1vh) * 100 - 135px) !important;
    }
    .oneProductContent .description{
        bottom: 12px;
    }
    .moreDetails .descriptionInner div:nth-child(2){
        opacity: 1 !important;
        height: auto !important;
    }
    .sizeContainerProduct{
        transform: translateY(0px);
        height: 320px;
        -webkit-transition: all .5s ease;
        -moz-transition: all .5s ease;
        -o-transition: all .5s ease;
        -ms-transition: all .5s ease;
        transition: all .5s ease;
    }
    .cancelBackround{
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 2;
        background-color:rgba(0, 0, 0, 0.1);
    }
    .slide-wrapp{
        position: relative;
    }
    .hooper-pagination{
        top: 12%;
        right: -7px;
    }
    .hooper-indicator{
        border: 1px solid red;
    }
    .moreDetails{
        margin-top: 10px;
    }
    .slider-one-product img{
        margin-top: -15px;
    }
    .oneProductContent .description-open:after{
        transform:scaleY(-1);
    }
    .close-block{
        width: 50px;
        margin: auto;
        position: absolute !important;
        top: -17px;
        right: 50%;
        height: 50px;
        background-size: 40px;
        background-repeat: no-repeat;
        background-position: center;
        padding: 5px;
        margin-right: -30px;
        border-radius: 50%;
    }
    .arrow-icon{
        background-color: #fffcf5;
    }
    .close-block span{
        display: block;
        position: absolute;
        height: 1.5px;
        width: 47%;
        background: #B1B1B1;
        border-radius: 1px;
        opacity: 1;
        left: 0;
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
        -webkit-transition: .40s ease-in-out;
        -moz-transition: .40s ease-in-out;
        -o-transition: .40s ease-in-out;
        transition: .40s ease-in-out;
    }
    .close-block span:nth-child(1) {
        transform: rotate(-26deg);
        bottom: 0;
        top: 50%;
        left: 4%;
    }

    .close-block span:nth-child(2) {
        transform: rotate(26deg);
        top: 50%;
        left: 46%;
    }

    .arrow-icon span:nth-child(1) {
        transform: rotate(-45deg);
        top: 48%;
        left: 26%;
    }

    .arrow-icon span:nth-child(2) {
        transform: rotate(45deg);
        top: 48%;
        left: 26%;
    }
    .description:after{
        display: none !important;
    }

    .inner-block{
        overflow-y: scroll;
        margin-top: 0px;
        padding-top: 0;
        overscroll-behavior-y: contain;
    }
    .oneProductContent .description-open:after{
        display: none;
    }
    .cartBtn{
        margin-top: 30px;
        text-align: center;
        border: 1px solid #42261D;
        background: #eddcd5;
        color: #42261D;
        display: block;
    }
    .heading{
        padding-top: 10px;
        display: flex;
        align-items: center;
        flex-direction: column;
    }

</style>
