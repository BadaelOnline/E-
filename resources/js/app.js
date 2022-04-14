require('./bootstrap');

import { createApp } from 'vue';
import router from './router/router';
import store from './store/Store';
import axios from 'axios';

import App from './components/App.vue';

const app = createApp({
    el : '#app'
}).use(router,store);
app.config.globalProperties.axios = axios;
app.component('app', App)

app.mount('#app')
