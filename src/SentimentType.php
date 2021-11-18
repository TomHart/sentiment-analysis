<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis;

/**
 * Class SentimentType
 * @package TomHart\SentimentAnalysis
 */
enum SentimentType: string
{
    case POSITIVE = 'positive';
    case NEUTRAL = 'neutral';
    case NEGATIVE = 'negative';
}