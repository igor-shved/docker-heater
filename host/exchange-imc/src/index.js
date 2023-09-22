import {createApp} from 'vue';
import ExchangeBase from './ExchangeBase.vue';
import axios from "axios";
import VueEventer from "vue-eventer";
import store from "./store";

const app = createApp(ExchangeBase);
app.config.globalProperties.$eventBus = new VueEventer();
app.config.globalProperties.$axios = axios;
app.config.globalProperties.$store = store;
app.mount('#exchange');
