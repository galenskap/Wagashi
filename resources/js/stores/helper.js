import { usePlayerStore } from './playerStore';
import { useGameStore } from './gameStore';
import  Router from '../router';


export const  resetAll = () => {

    const gameStore = useGameStore();
    const playerStore = usePlayerStore();

    playerStore.$reset();
    gameStore.$reset();
    localStorage.removeItem('token');
    Router.push("/");
};
