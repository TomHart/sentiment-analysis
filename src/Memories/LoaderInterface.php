<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Memories;

/**
 * Class LoaderInterface
 * @package TomHart\SentimentAnalysis\Memories
 */
interface LoaderInterface
{
    /**
     * Get the words.
     *
     * @return array
     */
    public function getWords(): array;

    /**
     * Get the word type data.
     *
     * @return array
     */
    public function getWordTypeCount(): array;

    /**
     * Get the sentence type data.
     *
     * @return array
     */
    public function getSentenceTypeCount(): array;
}