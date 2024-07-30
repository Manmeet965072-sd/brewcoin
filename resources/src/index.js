/**
 * Main app entry file.
 */
window.DEBUG = false;
const cors = cors;
// app dependencies
import Toast from "vue-toastification";

import Ajax from "./modules/ajax";
import Binance from "./modules/binance";
import Tooltip from "./modules/tooltip";
import utils from "./modules/utils";

import VueAppend from "vue-append";
import VLazyImage from "./modules/v-lazy-image/v-lazy-image";
import VueMobileDetection from "vue-mobile-detection";
import SmartTable from "vuejs-smart-table";
import VueSkeletonLoader from "skeleton-loader-vue";
import VueRouter from "vue-router";


import App from "./App.vue";
import Vue from "vue";

Vue.config.productionTip = false;

Vue.use(VueMobileDetection);
Vue.use(VueRouter);
Vue.use(VueAppend);

const routes = [
];
const router = new VueRouter({
    routes, // short for `routes: routes`
});
router.afterEach((to, from) => {
    Vue.nextTick(() => {
        document.title = to.meta.title || "Dashboard";
    });
});
Vue.use(Toast, {
    hideProgressBar: true,
    closeOnClick: false,
    closeButton: false,
    icon: true,
    timeout: 2000,
    toastClassName: ["bg-light"],
    bodyClassName: [],
    transition: "Vue-Toastification__fade",
});
Vue.use(require("vue-moment"));
Vue.use(SmartTable);
Vue.component("vue-skeleton-loader", VueSkeletonLoader);
Vue.component("v-lazy-image", VLazyImage);

window.axios = require("axios");
Vue.prototype.$http = window.axios;

// setup common helper classes
const _ajax = new Ajax();
const _binance = new Binance();
const _tooltip = new Tooltip();

// create custom global vue properties
Object.defineProperties(Vue.prototype, {

    $ajax: {
        get() {
            return _ajax;
        },
    },
    $binance: {
        get() {
            return _binance;
        },
    },
    $utils: {
        get() {
            return utils;
        },
    },
});

// single tooltip instance for entire app
Vue.directive("tooltip", {
    bind: (el) => {
        _tooltip.select(el);
    },
    unbind: (el) => {
        _tooltip.unselect(el);
    },
});

// global filters used to format currency and price change values
Vue.filter("toLinks", (text) => utils.linkUrl(text));
Vue.filter("toNoun", (num, s, p) => utils.noun(num, s, p));
Vue.filter("toElapsed", (time, suffix, short) =>
    utils.elapsed((Date.now() - time) / 1000, suffix, short)
);
Vue.filter("toDate", (time, full) => utils.date(time, full));
Vue.filter("toMoney", (num, decimals) => utils.money(num, decimals));
Vue.filter("toMoney2", (num, decimals) => utils.money_ccxt(num, decimals));
Vue.filter("toFixed", (num, asset) => utils.fixed(num, asset));
Vue.filter("toInternationalCurrency", (num) => convertToInternationalCurrencySystem(num))

window.addEventListener("load", (e) => {
    if (window.top !== window) return;
    document.body.setAttribute("tabindex", "0");
    new Vue({
        el: "#app",
        router,
        render: (h) => h(App),
    });
});
