import Home from './pages/Home.vue';
import NewGame from './pages/NewGame.vue';
import Join from './pages/Join.vue';
import Error404 from './pages/Error404.vue';
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
