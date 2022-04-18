import Echo from 'laravel-echo';
import { useGameStore } from './stores/gameStore';
import { usePlayerStore } from './stores/playerStore';



export function connectGeneral($id) {

    const gameStore = useGameStore();

    window.Echo.private(`general-${$id}`)
    .listen('GeneralBroadcastQuestion', (e) => {
        // set new dealer in gamestore
        gameStore.current_dealer = e.dealer_id;
        // set new question in gamestore
        gameStore.current_question = e.question;
        // empty propositions in gamestore
        gameStore.propositions = {};
        gameStore.playersHavingPropositions = [];
    })
    .listen('GeneralBroadcastNewPlayer', (e) => {
        // set new dealer in gamestore
        gameStore.players = e.players;
    })
    .listen('GeneralBroadcastNewProposition', (e) => {
        // replace special players list
        gameStore.playersHavingPropositions = e.playersHavingPropositions;
    })
    .listen('GeneralBroadcastAllPropositions', (e) => {
        // put propositions in the gamestore
        gameStore.propositions = e.propositions;
    })
    .listen('GeneralBroadcastRoundWinner', (e) => {
        // put propositions in the gamestore
        gameStore.previous_turn.question = e.question;
        gameStore.previous_turn.answers = e.answers;
        gameStore.previous_turn.winner = e.winner;
        gameStore.players = e.players; // update players list in order to get valid scores
        gameStore.result_popin_round = true;
        gameStore.result_popin = true;
        gameStore.game_winner = e.game_winner;
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
        enabledTransports: ['ws', 'wss'],
        auth: {
            headers: {
                Authorization: token,
            }
        }
    });

}
