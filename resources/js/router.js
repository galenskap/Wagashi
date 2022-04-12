import Home from './components/Home.vue';
import NewGame from './components/NewGame.vue';
import Join from './components/Join.vue';
import Error404 from './components/Error404.vue';
import {createRouter, createWebHistory} from 'vue-router';


const routes = [
    { path: '/', component: Home },
    { path: '/new-game', component: NewGame },
    { path: '/join-game', component: Join },
    { path: '/:pathMatch(.*)*', component: Error404 },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
