import Home from './pages/Home.vue';
import NewGame from './pages/NewGame.vue';
import Join from './pages/Join.vue';
import Game from './pages/Game.vue';
import Error404 from './pages/Error404.vue';
import {createRouter, createWebHistory} from 'vue-router';


const routes = [
    { path: '/', component: Home, meta: { class: 'back-dark'} },
    { path: '/new-game', component: NewGame, meta: { class: 'back-dark'}  },
    { path: '/join-game', component: Join, meta: { class: 'back-dark'}  },
    { path: '/game/:gameslug', component: Game },
    { path: '/:pathMatch(.*)*', component: Error404 },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
