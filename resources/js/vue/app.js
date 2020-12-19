import Vue from 'vue'
import App from './App.vue'
import VModal from 'vue-js-modal';
import VueToastr from "vue-toastr";

Vue.component('kanban-board', App);
Vue.component("vue-toastr", VueToastr);

Vue.use(VModal);

new Vue({
    el : '#kanban-board'
});
