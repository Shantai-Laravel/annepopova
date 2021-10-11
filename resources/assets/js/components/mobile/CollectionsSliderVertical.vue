<template>
    <div class="slide-wrapp">
        <hooper v-if="imageHeight > 0" :vertical="true"
                :style="'height:' + imageHeight + 'px'"
                @beforeSlide="beforeSlide()"
                @afterSlide="afterSlide()"
                @slide="(event) => slide(event)"
            >

            <slide v-if="set.photos.length > 0" v-for="image in set.photos">
                <div class="slider-one-product">
                    <img :src="'/images/sets/md/' + image.src">
                </div>
            </slide>

            <slide v-if="set.photos.length == 0">
                <div class="slider-one-product">
                    <img src="/images/no-image-ap.jpg">
                </div>
            </slide>

            <hooper-pagination slot="hooper-addons"></hooper-pagination>
        </hooper>

        <div :class="['description', descrMode]" v-if="loading"
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

            <div class="heading" :style="{ height: bottomPosition }">
                <p class="name collectionName">{{ set.translation.name }}</p>
                <div class="countSet">
                    {{ trans.vars.DetailsProductSet.have }}
                    {{ set.products.length }}
                    {{ trans.vars.DetailsProductSet.productsFromSet }}
                </div>
            </div>

            <div class="innerContainer collectionInner" :id="'collectionInner' + set.id" :style="{ height: scrollBlockHeight + 'px' }">
                <div class="descriptionInner">
                    <set-mob :set="set" :site="site"></set-mob>
                </div>
            </div>
        </div>

        <div    class="cancelBackround"
                v-if="cancelBackround"
                @click="handleСurtain()">
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
    components: {Hooper, Slide, HooperPagination },
    props: ['set', 'site'],
    data(){
        return {
            openDescription     : false,
            descriptionHeight   : 0,
            imageHeight         : 0,
            sizesHeight         : 0,
            bottomPosition      : 0,
            translateY          : '-20',
            loading             : false,
            innerScroll         : 0,
            descrMode           : '',
            cancelBackround     : false,
            scrollBlockHeight   : 0,
            closeBlockClass     : ''
        }
    },
    mounted(){
        this.imageHeight        = 0.78 * window.innerHeight;
        this.descriptionHeight  = 0.65 * window.innerHeight;
        this.bottomPosition     = 0.12 * window.innerHeight + 'px';
        this.sizesHeight        = $('.sizeContainerProduct').height();
        this.scrollBlockHeight  = this.descriptionHeight - 30;
        this.loading            = true;
        $("html").css({
            "touch-action": "pan-down"
        });
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
                this.translateYSizes    = '-500';
                this.translateY         = 0;
                this.descrMode          = '';
                this.cancelBackround    = false;
                this.closeBlockClass    = '';
                this.bottomPosition     = 0.15 * window.innerHeight + 'px';
            }
        },
        handleScroll(){
            console.log(this.innerScroll++);
        },
        slide(event){
            if (event.currentSlide == event.slideFrom && event.currentSlide > 0) {
                this.translateY = this.descriptionHeight;
                this.descrMode = 'description-open';
                this.openDescription = true;
                this.cancelBackround = true;
                this.closeBlockClass = 'arrow-icon';
                this.bottomPosition     = 'auto';
            }else{
                this.translateY = 0;
            }
        },
        onMove(event){
            if (this.innerScroll == 0) {
                if (this.openDescription == false) {
                    if (event.direction == 8) {
                        this.translateY = this.descriptionHeight;
                        this.descrMode = 'description-open';
                        this.cancelBackround = true;
                        this.closeBlockClass = 'arrow-icon';
                        this.bottomPosition  = 'auto';
                    }
                }else{
                    // if (event.direction == 16) {
                    //     this.translateY = 0;
                    //     this.descrMode = '';
                    // }
                }
            }
        },
        onPanEnd(){
            if (this.openDescription == false) {
                this.openDescription = true;
            }else{
                this.openDescription = false
            }
        },
        onPanVertical() {},
        beforeSlide()   {},
        afterSlide()    {},
        scrollInner(event){
            console.log(event);
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
        bottom: 0 !important;
    }
    .moreDetails .descriptionInner div:nth-child(2){
        opacity: 1 !important;
        height: auto !important;
    }
    .sizeContainerProduct{
        transform: translateY(0px);
        height: 320px;
        transition: -webkit-transform .3s ease;
        transition: transform .3s ease;
        transition: transform .3s ease, -webkit-transform .3s ease;
    }
    .cancelBackround{
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 4;
        background-color:rgba(0, 0, 0, 0.2);
        transition: -webkit-transform .3s ease;
        transition: transform .3s ease;
        transition: transform .3s ease, -webkit-transform .3s ease;
    }
    .slide-wrapp{
        position: relative;
    }
    .hooper-pagination{
        top: 12%;
        right: -7px;
    }
    .moreDetails{
        margin-top: 45px;
    }
    .slider-one-product img{
        margin-top: -15px;
    }
    .cancelBackround{
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 2;
        background-color:rgba(0, 0, 0, 0.2);
        transition: -webkit-transform .3s ease;
        transition: transform .3s ease;
        transition: transform .3s ease, -webkit-transform .3s ease;
    }
    .close-block{
        width: 50px;
        margin: auto;
        position: absolute !important;
        top: -23px;
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
        -webkit-transition: .60s ease-in-out;
        -moz-transition: .60s ease-in-out;
        -o-transition: .60s ease-in-out;
        transition: .60s ease-in-out;
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
    .innerContainer{
        overflow-y: scroll;
        margin-top: 30px;
        padding-top: 0;
        /* touch-action: pan-down; */
        overscroll-behavior-y: contain;
    }
    .products{
        margin-top: 0px;
    }
    .oneProductContent .description-open:after{
        display: none;
    }
    .collectionContent.collectionContent .description .name{
        margin-top: 15px;
    }
</style>
