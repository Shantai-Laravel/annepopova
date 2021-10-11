<template>
    <div class="sizeContainerProduct" :ref="product.id" v-if="product.subproducts.length > 0">
        <div class="head">
            <p>{{ trans.vars.DetailsProductSet.selectSize }}</p>
            <p class="sizeGuide" data-toggle="modal" data-target="#modalSize">{{ trans.vars.DetailsProductSet.sizeGuide }}</p>
        </div>
        <div class="sizeCheckContainer">
            <label class="sizeCheck" v-for="subproduct in product.subproducts">
                <input type="radio" name="size"  v-if="subproduct.warehouse.stock == 0" disabled/>
                <input type="radio" name="size" @click="chooseSubproduct(subproduct, product)" v-if="subproduct.stoc > 0"/>
                <span class="check" v-if="subproduct.warehouse.stock == 0">{{ subproduct.parameter_value.translation.name }}<span class="count">{{ trans.vars.DetailsProductSet.outOfStock }}</span></span>
                <span class="check" v-if="subproduct.warehouse.stock > 0">{{ subproduct.parameter_value.translation.name }}</span>
            </label>
        </div>
    </div>
</template>

<script>
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
            bus.$on('show' + this.product.id, data => {
                $(this.$refs[this.product.id]).css('bottom', '0px');
            });
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
                    if (this.subproduct.warehouse.stock > 0) {
                        this.addProductToCartAction(product)
                    } else {
                        $(this.$el).find(".chooseSize").addClass("heartBeat");
                        setInterval(() => {
                            $(this.$el).find(".chooseSize").removeClass("heartBeat")
                        }, 2000)
                    }
                } else {
                    this.addProductToCartAction(product)
                }
            },
            addProductToCartAction(product) {
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
                        bus.$emit('updateCart', this.subproduct.code);
                        bus.$emit('ga-event-addToCart', product);
                        $('.buttCart').addClass('flash');
                        setTimeout(function() {
                            $('.buttCart').removeClass('flash');
                        }, 500);
                    })
                    .catch(e => {
                        this.errors.push(e)
                    });
            },
            chooseSubproduct(subproduct, product) {
                this.btn = trans.vars.Cart.cartAddTo;
                this.subproduct = subproduct;
                this.addToCart(product);
                $(this.$el).find(".addToCart").removeClass("elAdded");
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
    }
</script>
