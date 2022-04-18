import { defineStore } from 'pinia';

export const useGameStore = defineStore('gameStore', {
    state: () => {
        return {
            id: 0,
            slug: "",
            lobby_owner: 0,
            current_dealer: 0,
            current_question: "",
            players: [
            //     {
            //         id: 1,
            //         pseudo: "René Coty",
            //         score: 0,
            //     },
            //     {
            //         id: 2,
            //         pseudo: "José Luis",
            //         score: 0,
            //     },
            ],
            propositions: {
                // 1: [{
                //     player_id: 1,
                //     text: "chier dans un onsen",
                //     answer_id: 10,
                //     order: 1,
                // }],
                // 2: [{
                //     player_id: 2,
                //     text: "les juifs",
                //     answer_id: 10,
                //     order: 1,
                // }],
            },
            playersHavingPropositions: [],
            previous_turn: {
                question: "",
                answers: [
                ],
                winner: 0,
            },
            result_popin: false,
            result_popin_round: false,
        }
      },
    getters: {
        getCurrentDealer: (state) => {
            return state.players.find(player => player.id === state.current_dealer);
        },
        getLobbyOwner: (state) => {
            return state.players.find(player => player.id === state.lobby_owner);
        },
        getWinnerPlayer: (state) => {
            return state.players.find(player => player.id === state.previous_turn.winner);
        },
        countQuestionHoles: (state) => {
            return state.current_question.match(/##/g).length;
        },
        getHTMLQuestion: (state) => {
            return state.current_question.replace(/##/g, '<span class="question-hole"></span>');
        },
        getSplittedQuestion: (state) => {
            return state.current_question.split('##');
        },
        getPlayersByScore: (state) => {
            return state.players.sort((a, b) => b.current_score - a.current_score);
        }
    }
});
