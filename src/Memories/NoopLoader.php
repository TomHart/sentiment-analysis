<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Memories;

use JetBrains\PhpStorm\ArrayShape;
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

    #[ArrayShape([SentimentType::POSITIVE => 'int', SentimentType::NEGATIVE => 'int'])]
    public function getWordType(): array
    {
        return [SentimentType::POSITIVE => 0, SentimentType::NEGATIVE => 0];
    }

    #[ArrayShape([SentimentType::POSITIVE => 'int', SentimentType::NEGATIVE => 'int'])]
    public function getSentenceType(): array
    {
        return [SentimentType::POSITIVE => 0, SentimentType::NEGATIVE => 0];
    }

    public function getWordCount(): int
    {
        return 0;
    }
}