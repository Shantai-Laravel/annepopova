<template>
    <div>
        <div :class="['dropdown', 'sizeContainer', sizeContainerClass]" v-if="product.subproducts.length > 0">
            <div>
                <span>{{ trans.vars.DetailsProductSet.size }}</span>
                <span>
                    <a href="#" data-toggle="modal" data-target="#modalSize">{{ trans.vars.DetailsProductSet.sizeGuide }}</a>
                </span>
            </div>
        </div>

        <div class="buttonsSet">
          <div class="cartButton" @click="showSizesBlock" v-if="!addedToCart">
            <span> {{ trans.vars.Cart.cartAddTo }}
              <svg width="24px" height="32px" viewBox="0 0 24 32" version="1.1" xmlns="http://www.w3.org/2000/svg" > <g id="Symbols" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd"> <g id="project_20200129_150142" transform="translate(-1529.000000, -61.000000)"> <g transform="translate(-359.000000, -1055.000000)" id="Group-8"> <g id="Group-16" transform="translate(356.500000, 1055.000000)"> <g id="Shape-2" transform="translate(1532.050781, 61.363636)"> <path d="M4.56105523,8.4535472 L18.8601019,8.10653496 C21.0685906,8.0529389 22.9023727,9.79982458 22.9559687,12.0083133 C22.9589832,12.1325272 22.9562084,12.256814 22.9476534,12.3807696 L21.9919921,26.2275651 C21.8472458,28.324827 20.1037358,29.9521531 18.0014848,29.9521531 L5.04927707,29.9521531 C2.88525509,29.9521531 1.11363495,28.2311249 1.0509553,26.0680108 L0.65977772,12.5682275 C0.59579128,10.3600154 2.33402965,8.5180345 4.54224179,8.45404806 C4.54851254,8.45386635 4.5547837,8.4536994 4.56105523,8.4535472 Z" id="Rectangle" fill="none" opacity="0.916666667" transform="translate(11.896687, 18.976077) rotate(-360.000000) translate(-11.896687, -18.976077) " ></path> <path d="M22.5214956,7.96933632 L18.0691228,7.96933632 L18.0691228,6.17062023 C18.0691228,2.76285464 15.2646973,-1.11910481e-13 11.8056573,-1.11910481e-13 C8.34639139,-1.11910481e-13 5.5419659,2.76285464 5.5419659,6.17062023 L5.5419659,7.96933632 L0.721725511,7.96933632 C0.323804812,7.97044937 0.00112980001,8.28789607 1.95399252e-14,8.68036342 L1.95399252e-14,27.7180369 C0.000903851575,29.6810416 1.61586067,31.2720594 3.60817549,31.2727273 L19.6420505,31.2727273 C21.6345913,31.2720594 23.2495481,29.6810416 23.25,27.7180369 L23.25,8.68036342 C23.2454807,8.28656036 22.9207721,7.97000411 22.5214956,7.96933632 Z M6.75,6.20190826 C6.75,3.58021034 8.93286651,1.45454545 11.6248857,1.45454545 C14.3175905,1.45454545 16.5,3.58021034 16.5,6.20190826 L16.5,8 L6.75,8 L6.75,6.20190826 Z M21.75,27.6953403 C21.7464048,28.8665479 20.7846764,29.8148583 19.5973463,29.8181818 L3.6528784,29.8181818 C2.46509894,29.8148583 1.50337056,28.8665479 1.5,27.6953403 L1.5,9.45454545 L5.57566104,9.45454545 L5.57566104,12.3342628 C5.57566104,12.7251085 5.89721089,13.0419505 6.29313742,13.0419505 C6.68951332,13.0419505 7.01083843,12.7251085 7.01083843,12.3342628 L7.01083843,9.45454545 L16.6049779,9.45454545 L16.6049779,12.3342628 C16.6049779,12.7251085 16.926303,13.0419505 17.3226789,13.0419505 C17.7186054,13.0419505 18.0401552,12.7251085 18.0401552,12.3342628 L18.0401552,9.45454545 L21.75,9.45454545 L21.75,27.6953403 Z" id="Shape" fill="#42261D" fillRule="nonzero" transform="translate(11.625000, 15.636364) rotate(-360.000000) translate(-11.625000, -15.636364) " ></path> </g> </g> </g> </g> </g> </svg>
            </span>
          </div>
          <div class="cartButton" v-if="addedToCart">
            <a :href="'/' + $lang + '/cart'"> {{ trans.vars.DetailsProductSet.view }}
              <svg width="24px" height="32px" viewBox="0 0 24 32" version="1.1" xmlns="http://www.w3.org/2000/svg" > <g id="Symbols" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd"> <g id="project_20200129_150142" transform="translate(-1529.000000, -61.000000)"> <g transform="translate(-359.000000, -1055.000000)" id="Group-8"> <g id="Group-16" transform="translate(356.500000, 1055.000000)"> <g id="Shape-2" transform="translate(1532.050781, 61.363636)"> <path d="M4.56105523,8.4535472 L18.8601019,8.10653496 C21.0685906,8.0529389 22.9023727,9.79982458 22.9559687,12.0083133 C22.9589832,12.1325272 22.9562084,12.256814 22.9476534,12.3807696 L21.9919921,26.2275651 C21.8472458,28.324827 20.1037358,29.9521531 18.0014848,29.9521531 L5.04927707,29.9521531 C2.88525509,29.9521531 1.11363495,28.2311249 1.0509553,26.0680108 L0.65977772,12.5682275 C0.59579128,10.3600154 2.33402965,8.5180345 4.54224179,8.45404806 C4.54851254,8.45386635 4.5547837,8.4536994 4.56105523,8.4535472 Z" id="Rectangle" fill="none" opacity="0.916666667" transform="translate(11.896687, 18.976077) rotate(-360.000000) translate(-11.896687, -18.976077) " ></path> <path d="M22.5214956,7.96933632 L18.0691228,7.96933632 L18.0691228,6.17062023 C18.0691228,2.76285464 15.2646973,-1.11910481e-13 11.8056573,-1.11910481e-13 C8.34639139,-1.11910481e-13 5.5419659,2.76285464 5.5419659,6.17062023 L5.5419659,7.96933632 L0.721725511,7.96933632 C0.323804812,7.97044937 0.00112980001,8.28789607 1.95399252e-14,8.68036342 L1.95399252e-14,27.7180369 C0.000903851575,29.6810416 1.61586067,31.2720594 3.60817549,31.2727273 L19.6420505,31.2727273 C21.6345913,31.2720594 23.2495481,29.6810416 23.25,27.7180369 L23.25,8.68036342 C23.2454807,8.28656036 22.9207721,7.97000411 22.5214956,7.96933632 Z M6.75,6.20190826 C6.75,3.58021034 8.93286651,1.45454545 11.6248857,1.45454545 C14.3175905,1.45454545 16.5,3.58021034 16.5,6.20190826 L16.5,8 L6.75,8 L6.75,6.20190826 Z M21.75,27.6953403 C21.7464048,28.8665479 20.7846764,29.8148583 19.5973463,29.8181818 L3.6528784,29.8181818 C2.46509894,29.8148583 1.50337056,28.8665479 1.5,27.6953403 L1.5,9.45454545 L5.57566104,9.45454545 L5.57566104,12.3342628 C5.57566104,12.7251085 5.89721089,13.0419505 6.29313742,13.0419505 C6.68951332,13.0419505 7.01083843,12.7251085 7.01083843,12.3342628 L7.01083843,9.45454545 L16.6049779,9.45454545 L16.6049779,12.3342628 C16.6049779,12.7251085 16.926303,13.0419505 17.3226789,13.0419505 C17.7186054,13.0419505 18.0401552,12.7251085 18.0401552,12.3342628 L18.0401552,9.45454545 L21.75,9.45454545 L21.75,27.6953403 Z" id="Shape" fill="#42261D" fillRule="nonzero" transform="translate(11.625000, 15.636364) rotate(-360.000000) translate(-11.625000, -15.636364) " ></path> </g> </g> </g> </g> </g> </svg>
          </a>
          </div>
          <a href="#" @click.prevent="showSizesBlock">{{ trans.vars.DetailsProductSet.viewProduct }}</a>
        </div>


        <div    :class="['cancelBackround', 'cancelBackround2', cancelBackround2 == false ? 'hide-block' : '']"
                @click="clooseSizesBlock2()"
                style="z-index:3;"
            >
                <div class="sizeContainerProduct" :style="'top:' + offsetTopSizes + 'px'">
                    <div class="head">
                        <p>Select size</p>
                        <p data-toggle="modal" data-target="#modalSize" class="sizeGuide">Size Guide</p>
                    </div>
                    <div class="sizeCheckContainer">
                        <label class="sizeCheck" v-for="subproduct in product.subproducts">
                            <input type="radio" name="size" :disabled="subproduct.warehouse.stock == 0"
                                                            :value="subproduct.parameter_value.translation.name"
                                                            @click="addToCartProduct(subproduct)"
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
export default {
    props: ['product', 'site', 'set'],
    data() {
        return {
            subproducts: [],
            subproduct: [],
            btnCart: trans.vars.Cart.cartAddTo,
            btnWish: trans.vars.Cart.cartAddTo,
            sizeContainerClass: '',
            dropContainerClass: '',
            translateYSizes   : '700',
            cancelBackround2  : false,
            addedToCart       : false,
            offsetTopSizes    : 0,
        };
    },
    mounted() {
        this.offsetTopSizes = window.screen.height - $('.sizeContainerProduct').height();
    },
    methods: {
        clooseSizesBlock2(){
            this.translateYSizes = '700';
            this.cancelBackround2 = false;
        },
        showSizesBlock(){
            this.translateYSizes = $('.oneProductContent').height() - $('.sizeContainerProduct').height() + 70;
            this.cancelBackround2 = true;
            this.offsetTopSizes = window.screen.height - $('.sizeContainerProduct').height();
        },
        setSizesPosition(){
            if (this.setSizesPosition == "") {
                this.setSizesPosition = 'show';
                this.dropContainerClass = 'show';
            }else{
                this.setSizesPosition = '';
                this.dropContainerClass = '';
            }
        },
        addToFavorites(product) {
            axios.post('/' + this.$lang + '/' + this.site + '/add-to-favorites', {
                    product_id: product.id
                })
                .then(response => {
                    this.addAnimation("wish", this.$el);
                    this.btnWish = trans.vars.Cart.cartAddedTo;
                    bus.$emit('updateWishBox', {
                        data: response.data
                    });
                })
                .catch(e => {
                    console.log('add favorites error')
                });
        },
        addToCartProduct(subproduct) {
            if (this.product.subproducts.length > 0) {
                if (subproduct.warehouse.stock > 0) {
                    this.addProductToCartAction(subproduct)
                }
            } else {
                this.addProductToCartAction(this.product)
            }
        },
        addProductToCartAction(subproduct) {
            axios.post('/' + this.$lang + '/' + this.site + '/add-product-to-cart', {
                    productId: this.product.id,
                    subproductId: subproduct.id,
                })
                .then(response => {
                    this.addAnimation("cart", this.$el);
                    this.addedToCart = true;
                    this.translateYSizes = '700';
                    this.cancelBackround2 = false;
                    bus.$emit('updateCartBox', {
                        data: response.data
                    });
                    bus.$emit('ga-event-addToCart', {product: this.product, actionField: this.set.collection.translation.name+' > '+this.set.translation.name});
                })
                .catch(e => {
                    this.errors.push(e)
                });
        },
        setProduct(product, subproduct) {
            let ref = 'colSize' + product.id + subproduct.id;

            $('.my' + product.id).removeClass('selectedRadio');
            $(this.$refs[ref]).next().addClass('selectedRadio');

            this.sizeContainerClass = '';
            this.dropContainerClass = '';

            this.btnCart = trans.vars.Cart.cartAddTo;
            this.product = product;
            this.subproduct = subproduct;
        },
        addAnimation(el, target) {
            document.getElementById(el).classList.add("heartBeat");
            if (el == "cart") {
                $(target).find(".cartButton").addClass("elAdded");
            } else if (el == "wish") {
                $(target).find(".wishButton").addClass("elAdded");
            } else {
                return null
            }
            setInterval(() => {
                document.getElementById(el).classList.remove("heartBeat");
            }, 2000)
        }
    }
}
</script>

<style media="screen">
    .sizeContainerProduct{
        top: 0;
        left: 0;
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
    .sizeContainerProduct{
        -webkit-transition: all .5s ease;
        -moz-transition: all .5s ease;
        -o-transition: all .5s ease;
        -ms-transition: all .5s ease;
        transition: all .5s ease;
        bottom: auto;
    }
    .hide-block{
        left: -100%;
    }
</style>
