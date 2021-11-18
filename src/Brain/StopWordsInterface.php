<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Brain;

/**
 *
 */
interface StopWordsInterface
{
    /**
     * Set the stop words for the brain to ignore when training
     * @param array $stopWords
     * @return $this
     */
    public function setStopWords(array $stopWords): self;

    /**
     * Return the configured stop words.
     * @return array
     */
    public function getStopWords(): array;

    /**
     * Is the given word a stop word?
     * @param string $word
     * @return bool
     */
    public function isStopWord(string $word): bool;
}