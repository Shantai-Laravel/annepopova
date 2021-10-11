require('./bootstrap');

window.Vue = require('vue');
Vue.prototype.$lang = document.documentElement.getAttribute('lang');
Vue.prototype.$currency = document.documentElement.getAttribute('currency');
Vue.prototype.$currencyRate = document.documentElement.getAttribute('currency-rate');
Vue.prototype.$mainCurrency = document.documentElement.getAttribute('main-currency');
Vue.prototype.$device = document.documentElement.getAttribute('device');

export const bus = new Vue();
import VeeValidate from 'vee-validate';
import BootstrapVue from 'bootstrap-vue';
import VueNestable from 'vue-nestable';
import Fragment from 'vue-fragment';

Vue.use(VueNestable)

import Slick from 'vue-slick';              //https://github.com/staskjs/vue-slick
Vue.prototype.trans = trans;

import vSelect from 'vue-select';
Vue.component('v-select', vSelect)

let pathname = window.location.pathname;
let segments = pathname.split('/');

Vue.component('google-events', require('./components/googleComponent.vue'));


/************************************
 * Desktop components
 ***********************************/
// if (Vue.prototype.$device == 'og') {
    // home components
    Vue.component('home-sliders', require('./components/desktop/home/HomeSliders.vue'));
    Vue.component('home-slider-category', require('./components/desktop/home/HomeSliderCategory.vue'));
    Vue.component('home-slider-collection', require('./components/desktop/home/HomeSliderCollection.vue'));

    // product/sets components
    Vue.component('category', require('./components/desktop/CategoryComponent.vue'));
    Vue.component('parameters-filter', require('./components/desktop/ParametersFilterComponent.vue'));
    Vue.component('set', require('./components/desktop/SetComponent.vue'));
    Vue.component('new', require('./components/desktop/NewComponent.vue'));
    Vue.component('sale', require('./components/desktop/SaleComponent.vue'));
    Vue.component('view-recently', require('./components/desktop/ViewRecently.vue'));

    // cart components
    Vue.component('cart', require('./components/desktop/cart/CartComponent.vue'));
    Vue.component('cart-summary', require('./components/desktop/cart/CartSummaryComponent.vue'));
    Vue.component('cart-counter', require('./components/desktop/cart/CartCounterComponent.vue'));
    Vue.component('add-to-cart-button', require('./components/desktop/cart/AddToCartButtonComponent.vue'));
    Vue.component('add-to-cart-set-product', require('./components/desktop/cart/AddToCartSetProduct.vue'));
    Vue.component('add-to-cart-wish-product', require('./components/desktop/cart/AddToCartWishProduct.vue'));

    // wish components
    Vue.component('wish-counter', require('./components/desktop/wishlist/WishCounterComponent.vue'));
    Vue.component('wish-block', require('./components/desktop/wishlist/WishBlockComponent.vue'));
    Vue.component('wish-button', require('./components/desktop/wishlist/WishButtonComponent.vue'));

    // auth components
    Vue.component('reset-password', require('./components/desktop/auth/ResetPasswordComponent.vue'));
    Vue.component('auth', require('./components/desktop/auth/Auth.vue'));

    // order components
    Vue.component('order', require('./components/desktop/OrderShippingComponent.vue'));
    Vue.component('order-payment', require('./components/desktop/OrderPaymentComponent.vue'));

    Vue.component('boostrap-popup', require('./components/desktop/BootstrapPopup.vue'));
// }

/************************************
 * Mobile components
 ***********************************/

// DEV
 Vue.component('products-mobile-slider-dev', require('./components/mobile/ProductsSliderHorizontalDev.vue'));
 Vue.component('products-mobile-slider-one-item-dev', require('./components/mobile/ProductsSliderVerticalDev.vue'));

 Vue.component('products-mobile-slider', require('./components/mobile/ProductsSliderHorizontal.vue'));
 Vue.component('products-mobile-slider-one-item', require('./components/mobile/ProductsSliderVertical.vue'));

// DEV
 Vue.component('collections-mobile-slider-dev', require('./components/mobile/CollectionsSliderHorizontalDev.vue'));
 Vue.component('collections-mobile-slider-one-item-dev', require('./components/mobile/CollectionsSliderVerticalDev.vue'));

 Vue.component('collections-mobile-slider', require('./components/mobile/CollectionsSliderHorizontal.vue'));
 Vue.component('collections-mobile-slider-one-item', require('./components/mobile/CollectionsSliderVertical.vue'));

// Dev new
 Vue.component('collection-mobile-dev', require('./components/mobile/dev/collection.vue'));
 Vue.component('set-mobile-dev', require('./components/mobile/dev/set.vue'));
 Vue.component('add-to-cart-set-mobile-dev', require('./components/mobile/dev/addToCart.vue'));

 Vue.component('product-mobile-dev', require('./components/mobile/dev/product.vue'));



if (Vue.prototype.$device == 'sm') {

// home components
    Vue.component('home-sliders-mob', require('./components/mobile/home/HomeSliders.vue'));
    Vue.component('home-slider-category-mob', require('./components/mobile/home/HomeSliderCategory.vue'));
    Vue.component('home-slider-collection-mob', require('./components/mobile/home/HomeSliderCollection.vue'));

    // product/sets components
    Vue.component('category-mob', require('./components/mobile/CategoryComponent.vue'));
    Vue.component('parameters-filter-mob', require('./components/mobile/ParametersFilterComponent.vue'));
    Vue.component('set-mob', require('./components/mobile/SetComponent.vue'));
    Vue.component('set-mob-dev', require('./components/mobile/SetComponentDev.vue'));
    Vue.component('new-mob', require('./components/mobile/NewComponent.vue'));
    Vue.component('sale-mob', require('./components/mobile/SaleComponent.vue'));
    Vue.component('view-recently-mob', require('./components/mobile/ViewRecently.vue'));

    // cart components
    Vue.component('cart-mob', require('./components/mobile/cart/CartComponent.vue'));
    Vue.component('cart-summary-mob', require('./components/mobile/cart/CartSummaryComponent.vue'));
    Vue.component('cart-counter-mob', require('./components/mobile/cart/CartCounterComponent.vue'));
    Vue.component('add-to-cart-button-mob', require('./components/mobile/cart/AddToCartButtonComponent.vue'));
    Vue.component('add-to-cart-set-product-mob', require('./components/mobile/cart/AddToCartSetProduct.vue'));
    Vue.component('add-to-cart-wish-product-mob', require('./components/mobile/cart/AddToCartWishProduct.vue'));
    Vue.component('sizes-mob', require('./components/mobile/cart/Sizes.vue'));

    // wish components
    Vue.component('wish-counter-mob', require('./components/mobile/wishlist/WishCounterComponent.vue'));
    Vue.component('wish-block-mob', require('./components/mobile/wishlist/WishBlockComponent.vue'));
    Vue.component('wish-button-mob', require('./components/mobile/wishlist/WishButtonComponent.vue'));

    // auth components
    Vue.component('reset-password-mob', require('./components/mobile/auth/ResetPasswordComponent.vue'));
    Vue.component('auth-mob', require('./components/mobile/auth/Auth.vue'));

    // order components
    Vue.component('order-mob', require('./components/mobile/OrderShippingComponent.vue'));
    Vue.component('order-payment-mob', require('./components/mobile/OrderPaymentComponent.vue'));

    Vue.component('boostrap-popup-mob', require('./components/mobile/BootstrapPopup.vue'));
}

if (segments.includes('back')) {
    // admin Components - CRM
    // CRMAddProductsToSet
    Vue.component('crm-payment', require('./components/admin/CRM/CRMPayment.vue'));
    Vue.component('crm-shipping', require('./components/admin/CRM/CRMShipping.vue'));
    Vue.component('order-search', require('./components/admin/CRM/OrderSearch.vue'));
    Vue.component('crm-cart', require('./components/admin/CRM/CRMCart.vue'));

    // admin Components - Collections
    Vue.component('collections', require('./components/admin/collections/Collections.vue'));
    Vue.component('sets', require('./components/admin/collections/Sets.vue'));
    Vue.component('products-depth', require('./components/admin/collections/Products.vue'));

    // admin Components - Categories
    Vue.component('categories', require('./components/admin/Categories.vue'));
    Vue.component('categories-add-new', require('./components/admin/CategoriesAddNew.vue'));

    // admin Components - Blog Categories
    Vue.component('blog-categories', require('./components/admin/blogCategories/Categories.vue'));
    Vue.component('blog-categories-add-new', require('./components/admin/blogCategories/CategoriesAddNew.vue'));

    // admin Components - Autoupload
    Vue.component('autoupload', require('./components/admin/Autoupload.vue'));
    Vue.component('top-bar-autoupload', require('./components/admin/AutouploadTopBar.vue'));
    Vue.component('edit-autoupload', require('./components/admin/AutouploadEdit.vue'));
    Vue.component('create-autoupload', require('./components/admin/AutouploadCreate.vue'));

    // admin Components - Parameters
    Vue.component('create-parameter', require('./components/admin/ParameterCreate.vue'));
    Vue.component('edit-parameter', require('./components/admin/ParameterEdit.vue'));

    // admin Components - Orders
    Vue.component('order-filter', require('./components/admin/OrderFilter.vue'));
    Vue.component('orders', require('./components/admin/Orders.vue'));
    Vue.component('order-admin', require('./components/admin/Order.vue'));
    Vue.component('order-create', require('./components/admin/OrderCreate.vue'));
    Vue.component('order-edit', require('./components/admin/OrderEdit.vue'));

    // admin Components - Returns
    Vue.component('return-filter', require('./components/admin/ReturnFilter.vue'));
    Vue.component('returns', require('./components/admin/Returns.vue'));
    Vue.component('return-create', require('./components/admin/ReturnCreate.vue'));
    Vue.component('return-edit', require('./components/admin/ReturnEdit.vue'));
    Vue.component('return', require('./components/admin/Return.vue'));

    // admin Components - Translations
    Vue.component('top-bar-translations', require('./components/admin/TranslationTopBar.vue'));
    Vue.component('group-translations', require('./components/admin/TranslationGroup.vue'));
    Vue.component('item-translations', require('./components/admin/TranslationItem.vue'));
}

Vue.use(VeeValidate, {
  classes: true,
  classNames: {
    valid: "is-valid",
    invalid: "is-invalid"
  }
});


Vue.directive('select2', {
    inserted(el) {
        $(el).on('select2:select', () => {
            const event = new Event('change', { bubbles: true, cancelable: true });
            el.dispatchEvent(event);
        });

        $(el).on('select2:unselect', () => {
            const event = new Event('change', {bubbles: true, cancelable: true})
            el.dispatchEvent(event)
        })
    },
});

const app = new Vue({
    el: '#cover'
});
