<?php

namespace App\Util;

class UserUtil
{
    /**
     * Gernerate automatic nickname
     *
     * @return string
     */
    public static function generateNicknema(): string
    {
        $nameList = [
            'Gorilla',
            'Monkey',
            'Scorpion',
            'Spider',
            'Bear',
            'Duck',
            'Eagle',
            'Owl',
            'Vulture',
            'Woodpecker',
            'Cat',
            'Cheetah',
            'Jaguar',
            'Lion',
            'Tiger',
            'Goats',
            'Dog',
            'Coyote',
            'Wolf',
            'Fox',
            'Dolphin',
            'Elephant',
            'Horse',
            'Ant',
            'Butterfly',
            'Kangaroo',
            'Koala',
            'Wombat',
            'Rabbit',
            'Snake',
            'Hamster',
            'Mouse',
        ];
        shuffle($nameList);
        return $nameList[0] . '-' . Sysdate::now()->format('md');
    }
}
