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
    public function getWords(): array
    {
        return [];
    }

    public function getWordTypeCount(): array
    {
        return [SentimentType::POSITIVE->value => 0, SentimentType::NEGATIVE->value => 0];
    }

    public function getSentenceTypeCount(): array
    {
        return [SentimentType::POSITIVE->value => 0, SentimentType::NEGATIVE->value => 0];
    }
}