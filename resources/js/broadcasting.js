import Echo from 'laravel-echo';
import { useGameStore } from './stores/gameStore';
import { usePlayerStore } from './stores/playerStore';



export function connectGeneral($id) {

    const gameStore = useGameStore();

    window.Echo.private(`general-${$id}`)
    .listen('GeneralBroadcastQuestion', (e) => {
        console.log(e);
        // set new dealer in gamestore
        gameStore.current_dealer = e.dealer_id;
        // set new question in gamestore
        gameStore.current_question = e.question;
        // empty propositions in gamestore
        gameStore.propositions = {};
        log("fini");
    });

}


export function connectPlayer($id) {

    const playerStore = usePlayerStore();


    window.Echo.private(`player-${$id}`)
    .listen('PlayerCards', (e) => {
        playerStore.answers = e.answers;
    });

}


export function setupBroadcast() {

    window.Pusher = require('pusher-js');

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: process.env.MIX_PUSHER_APP_KEY,
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        wsHost: window.location.hostname,
        wsPort: 6001,
        forceTLS: false,
        auth: {
            headers: {
                Authorization: token,
            }
        }
    });

}
