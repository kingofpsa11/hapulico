/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue").default;
import router from "./routes";
import store from "./store";
import App from "./App.vue";
import BootstrapVue from "bootstrap-vue";

Vue.use(BootstrapVue);

const app = new Vue({
    el: "#app",
    store,
    router,
    render: h => h(App)
});
