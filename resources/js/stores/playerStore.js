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
                {
                    "id": 23,
                    "text": "le fromage de pied",
                },
                {
                    "id": 24,
                    "text": "une fine nuance d'os avec une typo à empattements et de légers reliefs",
                },
            ]
        }
      },
});
