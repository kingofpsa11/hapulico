/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue").default;
window.moment = require("moment");
require("daterangepicker");
import router from "./routes";
import store from "./store";
import App from "./App.vue";
import { BootstrapVue, BootstrapVueIcons } from "bootstrap-vue";
import VueTheMask from "vue-the-mask";
import money from "v-money";
import { ServerTable, Event } from "vue-tables-2";
import Vue from "vue";
import ExcelJS from "exceljs";

Vue.use(money, { precision: 4 });
Vue.use(BootstrapVue);
Vue.use(BootstrapVueIcons);
Vue.use(VueTheMask);
Vue.use(ServerTable, {}, false, "bootstrap4");

const app = new Vue({
    el: "#app",
    store,
    router,
    render: h => h(App)
});
