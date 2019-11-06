import Vue from "vue";
import App from "./App";
import Notifications from 'vue-notification'

Vue.use(Notifications)

new Vue({
    el: '#app',
    template: '<App/>',
    components: { App }
})