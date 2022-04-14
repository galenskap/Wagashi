require('./bootstrap');
import { createApp } from 'vue';
import App from './App.vue';
import router from './router.js';
import { createPinia } from 'pinia'




const app = createApp(App);
app.use(router);
app.use(createPinia());
// app.config.unwrapInjectedRef = true;
app.mount('#app');

