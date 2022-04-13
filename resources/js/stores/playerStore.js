import { defineStore } from 'pinia';

export const usePlayerStore = defineStore('playerStore', {
    state: () => {
        return {
            "id": 4,
            "answers": [
                {
                    "id": 1,
                    "text": "chier dans un onsen",
                },
                {
                    "id": 156,
                    "text": "les juifs",
                },
            ]
        }
      },
});
