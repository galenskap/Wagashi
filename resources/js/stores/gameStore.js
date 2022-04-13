import { defineStore } from 'pinia';

export const useGameStore = defineStore('gameStore', {
    state: () => {
        return {
          dealer: 0,
          players: [
            {
                id: 1,
                name: "René Coty",
                score: 0,
            },
            {
                id: 2,
                name: "José Luis",
                score: 0,
            },
            {
                id: 3,
                name: "Juan Carlos",
                score: 0,
            },
            {
                id: 6,
                name: "Alphonse Robichu de ritournelle",
                score: 0,
            },
            {
                id: 4,
                name: "Pantoulge",
                score: 0,
            },
            {
                id: 5,
                name: "Flutadbe",
                score: 0,
            },
          ],
          question: '',
          owner: 1,
        }
      },
});
