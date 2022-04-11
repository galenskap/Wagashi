<?php
namespace App;

class CustomHelper
{
    const WORDS = [
        'matcha',
        'sakura',
        'kakao',
        'mango',
        'ichigo',
        'dango',
        'daifuku',
        'dorayaki',
        'taiyaki',
        'anko',
        'azuki',
        'mochi',
        'amanatto',
        'senbei',
        'monaka',
        'yokan',
        'konpeito',
        'karinto',
        'yuzu',
        'ohagi',
        'gokabo',
        'goma',
        'taro',
        'kinako',
        'yomogi',
        'kakigori',
        'castella',
        'purin',
        'arare',
        'suama',
        'manju',
    ];

    /**
     * Get three random words from the words array.
     */
    public static function generateSlug()
    {
        $words = self::WORDS;
        shuffle($words);
        $threeWords = array_slice($words, 0, 3);

        return implode('-', $threeWords);
    }
}
