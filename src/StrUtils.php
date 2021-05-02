<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis;

/**
 * Class StrUtils
 * @package TomHart\SentimentAnalysis
 */
class StrUtils
{
    /**
     * @param $words
     * @return mixed
     */
    public static function splitSentence($words): mixed
    {
        preg_match_all('/\w+/', $words, $matches);
        return $matches[0];
    }
}