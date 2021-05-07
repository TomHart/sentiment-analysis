<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Memories;

use JetBrains\PhpStorm\ArrayShape;
use TomHart\SentimentAnalysis\SentimentType;

/**
 * Class LoaderInterface
 * @package TomHart\SentimentAnalysis\Memories
 */
interface LoaderInterface
{
    /**
     * Get the sentiments.
     *
     * @return array
     */
    public function getSentiments(): array;

    /**
     * Get the word type data.
     *
     * @return array
     */
    #[ArrayShape([SentimentType::POSITIVE => 'int', SentimentType::NEGATIVE => 'int'])]
    public function getWordType(): array;

    /**
     * Get the sentence type data.
     *
     * @return array
     */
    #[ArrayShape([SentimentType::POSITIVE => 'int', SentimentType::NEGATIVE => 'int'])]
    public function getSentenceType(): array;
}