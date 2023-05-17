import {createApp, provide} from "vue";

const app = createApp({});
app.component('menu_list', require('../components/MenuList.vue').default);
app.mount('#menu');
