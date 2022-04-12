require('./bootstrap');
import { createApp } from 'vue';
import App from './App.vue';
import router from './router.js';
// import "minireset.css";


const app = createApp(App);
app.use(router);
app.config.unwrapInjectedRef = true;
app.mount('#app');

