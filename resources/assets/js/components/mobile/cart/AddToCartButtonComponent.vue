<template>
    <fragment>
        <div class="addToWishProduct" @click="addToFavorites">
            <svg
                width="29px"
                height="23px"
                viewBox="0 0 29 23"
                version="1.1"
                xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                >
                <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g
                        id="wishProduct"
                        transform="translate(-280.000000, -58.000000)"
                        fill="#42261D"
                        fill-rule="nonzero"
                        >
                        <g id="Topbar">
                            <g id="Group-2">
                                <g>
                                    <g id="Group-25" transform="translate(280.000000, 58.000000)">
                                        <g id="Shape-3">
                                            <path
                                                d="M26.5021889,6.74642446 C26.5021889,5.88873522 26.3860292,5.13163916 26.1537098,4.47513628 C25.9213904,3.8186334 25.6242377,3.29713716 25.2622516,2.91064757 C24.9002656,2.52415797 24.4599393,2.20914248 23.9412727,1.96560109 C23.4226061,1.7220597 22.9147451,1.55793398 22.4176896,1.47322393 C21.9206341,1.38851388 21.391162,1.34615885 20.8292732,1.34615885 C20.2673844,1.34615885 19.6622734,1.48116549 19.0139402,1.75117877 C18.365607,2.02119205 17.7686002,2.36003225 17.2229197,2.76769936 C16.6772392,3.17536647 16.209899,3.55656169 15.8208991,3.91128502 C15.4318992,4.26600835 15.1077326,4.59161259 14.8483993,4.88809777 C14.6538993,5.1210504 14.3891632,5.23752672 14.0541911,5.23752672 C13.7192189,5.23752672 13.4544829,5.1210504 13.2599829,4.88809777 C13.0006496,4.59161259 12.676483,4.26600835 12.2874831,3.91128502 C11.8984831,3.55656169 11.4311429,3.17536647 10.8854625,2.76769936 C10.339782,2.36003225 9.74277517,2.02119205 9.09444195,1.75117877 C8.44610873,1.48116549 7.84099773,1.34615885 7.27910894,1.34615885 C6.71722015,1.34615885 6.18774802,1.38851388 5.69069255,1.47322393 C5.19363708,1.55793398 4.68577606,1.7220597 4.16710948,1.96560109 C3.64844291,2.20914248 3.2081166,2.52415797 2.84613055,2.91064757 C2.4841445,3.29713716 2.18699178,3.8186334 1.95467237,4.47513628 C1.72235297,5.13163916 1.60619327,5.88873522 1.60619327,6.74642446 C1.60619327,8.52533548 2.61651253,10.4048397 4.63715107,12.3849371 L14.0541911,21.2794922 L23.4550228,12.4008202 C25.4864669,10.4101341 26.5021889,8.52533548 26.5021889,6.74642446 Z M28.1083822,7.0078125 C28.1083822,9.21397569 26.9110571,11.4600694 24.516407,13.7460938 L14.7443522,22.7304688 C14.5561265,22.9101563 14.3260728,23 14.0541911,23 C13.7823094,23 13.5522557,22.9101563 13.3640299,22.7304688 L3.57628969,13.7161458 C3.47171982,13.6362847 3.32793624,13.5065104 3.14493896,13.3268229 C2.96194168,13.1471354 2.67176028,12.820204 2.27439476,12.3460286 C1.87702924,11.8718533 1.52149167,11.3851997 1.20778205,10.8860677 C0.894072424,10.3869358 0.61434801,9.78298611 0.368608806,9.07421875 C0.122869602,8.36545139 -5.36459765e-13,7.67664931 -5.36459765e-13,7.0078125 C-5.36459765e-13,4.81163194 0.664018701,3.09461806 1.9920561,1.85677083 C3.3200935,0.618923611 5.15529479,-1.0658141e-14 7.49765997,-1.0658141e-14 C8.14599319,-1.0658141e-14 8.80739765,0.107313368 9.48187334,0.321940104 C10.156349,0.53656684 10.7837683,0.826063368 11.3641311,1.19042969 C11.9444939,1.55479601 12.443815,1.89670139 12.8620945,2.21614583 C13.280374,2.53559028 13.6777395,2.875 14.0541911,3.234375 C14.4306426,2.875 14.8280081,2.53559028 15.2462876,2.21614583 C15.6645671,1.89670139 16.1638883,1.55479601 16.7442511,1.19042969 C17.3246139,0.826063368 17.9520331,0.53656684 18.6265088,0.321940104 C19.3009845,0.107313368 19.962389,-1.0658141e-14 20.6107222,-1.0658141e-14 C22.9530874,-1.0658141e-14 24.7882887,0.618923611 26.1163261,1.85677083 C27.4443635,3.09461806 28.1083822,4.81163194 28.1083822,7.0078125 Z"
                                                id="Shape"
                                                ></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </g>
                </g>
            </svg>
        </div>
        <div class="sizeButtonBox">
            <div class="sizeButton" @click="addToCart(product)"></div>
        </div>
    </fragment>
</template>

<script>
    import { Fragment } from 'vue-fragment';
    import { bus } from '../../../app';

    export default {
        props: ['product', 'site'],
        data() {
            return {
                subproduct: [],
                btn: trans.vars.Cart.cartAddTo,
            }
        },
        mounted() {
            bus.$emit('ga-event-viewProduct', this.product);
        },
        methods: {
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
            // add product to cart emit event in CartBoxComponent
            addToCart(product) {
                if (product.subproducts.length > 0) {
                    bus.$emit('show' + this.product.id);
                    // if (this.subproduct.stoc > 0) {
                    //     this.addProductToCartAction(product)
                    // } else {
                    //     $(this.$el).find(".chooseSize").addClass("heartBeat");
                    //     setInterval(() => {
                    //         $(this.$el).find(".chooseSize").removeClass("heartBeat")
                    //     }, 2000)
                    // }
                } else {
                    this.addProductToCartAction(product)
                }
            },
            addProductToCartAction(product) {
                if (product.warehouse.stock > 0) {
                axios.post('/' + this.$lang + '/' + this.site + '/add-product-to-cart', {
                        productId: product.id,
                        subproductId: this.subproduct.id,
                    })
                    .then(response => {
                        this.addAnimation("cart", this.$el);
                        this.btn = trans.vars.Cart.cartAddedTo;
                        bus.$emit('updateCartBox', {
                            data: response.data
                        });
                        bus.$emit('ga-event-addToCart', {product: this.product, actionField: this.product.translation.name });
                        bus.$emit('updateCart', this.subproduct.code);
                        $('.buttCart').addClass('flash');
                        setTimeout(function() {
                            $('.buttCart').removeClass('flash');
                        }, 500);
                    })
                    .catch(e => {
                        this.errors.push(e)
                    });
                }
            },
            chooseSubproduct(subproduct, product) {
                this.btn = trans.vars.Cart.cartAddTo;
                this.subproduct = subproduct;
                $(this.$el).find(".addToCart").removeClass("elAdded");
                this.addToCart(product);
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
            }
        },
        components: { Fragment },
    }
</script>


<style media="screen">
    .sizeContainerProduct{
        position: absolute;
    }
</style>
