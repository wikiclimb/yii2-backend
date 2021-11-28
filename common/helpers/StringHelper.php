<?php

namespace common\helpers;

use Exception;

class StringHelper
{
    /**
     * Generate a random string, using a cryptographically secure
     * pseudorandom number generator (random_int)
     *
     * For PHP 7, random_int is a PHP core function
     * For PHP 5.x, depends on https://github.com/paragonie/random_compat
     *
     * @param int $length How many characters do we want?
     * @param string $keyspace A string of all possible characters
     *                         to select from
     * @return string
     * @throws \yii\base\Exception
     */
    public static function random_str(int $length, string $keyspace =
    '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string
    {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            try {
                $pieces [] = $keyspace[random_int(0, $max)];
            } catch (Exception $e) {
                throw new \yii\base\Exception($e->getMessage());
            }
        }
        return implode('', $pieces);
    }
}
