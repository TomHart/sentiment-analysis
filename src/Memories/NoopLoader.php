<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Memories;

use TomHart\SentimentAnalysis\SentimentType;

/**
 * Class NoopLoader
 * @package TomHart\SentimentAnalysis\Memories
 */
class NoopLoader implements LoaderInterface
{
    public function getSentiments(): array
    {
        return [];
    }

    public function getWordType(): array
    {
        return [SentimentType::POSITIVE->value => 0, SentimentType::NEGATIVE->value => 0];
    }

    public function getSentenceType(): array
    {
        return [SentimentType::POSITIVE->value => 0, SentimentType::NEGATIVE->value => 0];
    }
}