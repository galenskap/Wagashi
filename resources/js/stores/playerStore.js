import { defineStore } from 'pinia';

export const usePlayerStore = defineStore('playerStore', {
    state: () => {
        return {
            "id": 0,
            "answers": [
            ]
        }
      },
});
