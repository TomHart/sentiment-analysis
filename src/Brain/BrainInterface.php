<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Brain;

use TomHart\SentimentAnalysis\SentimentType;

/**
 * Brain Interface
 * @package TomHart\SentimentAnalysis
 */
interface BrainInterface
{
    /**
     * When training the brain, increment the number
     * of training sentences are positive or negative
     *
     * @param SentimentType $sentenceType
     * @return $this
     */
    public function incrementSentenceTypeCount(SentimentType $sentenceType): self;

    /**
     * Get how many sentences from training were positive or negative.
     *
     * @param SentimentType $type
     * @return int
     */
    public function getSentenceTypeCount(SentimentType $type): int;

    /**
     * Increment the number of either positive or negative words that have been trained.
     *
     * @param SentimentType $wordType
     * @return $this
     */
    public function incrementWordTypeCount(SentimentType $wordType): self;

    /**
     * Get the number of positive/negative trained words.
     *
     * @param SentimentType $type
     * @return int
     */
    public function getWordTypeCount(SentimentType $type): int;

    /**
     * Get how many sentences have been trained.
     *
     * @return int
     */
    public function getSentenceCount(): int;

    /**
     * Get the number of words that have been used in training.
     *
     * @return int
     */
    public function getWordCount(): int;

    /**
     * Add a sentiment as either positive or negative. In this
     *
     * @param string $word
     * @param SentimentType $wordType
     * @return $this
     */
    public function addWord(string $word, SentimentType $wordType): self;

    /**
     * Add a sentiment as either positive or negative.
     *
     * @param string $sentence
     * @param SentimentType $sentenceType
     * @return $this
     */
    public function addSentence(string $sentence, SentimentType $sentenceType): self;

    /**
     * Get the count of how many times a sentiment has been classified as positive or negative.
     *
     * @param string $word
     * @param SentimentType $wordType
     * @return int
     */
    public function getWordUsageCount(string $word, SentimentType $wordType): int;

    /**
     * Get all the trained sentiments.
     *
     * @return array
     */
    public function getWords(): array;
}