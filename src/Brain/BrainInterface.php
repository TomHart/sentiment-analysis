<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Brain;

use TomHart\SentimentAnalysis\Memories\LoaderInterface;

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
     * @param string $sentenceType
     * @return $this
     */
    public function incrementSentenceTypeCount(string $sentenceType): self;

    /**
     * Get how many sentences from training were positive or negative.
     *
     * @param string $type
     * @return int
     */
    public function getSentenceTypeCount(string $type): int;

    /**
     * Increment the number of either positive or negative words that have been trained.
     *
     * @param string $wordType
     * @return $this
     */
    public function incrementWordTypeCount(string $wordType): self;

    /**
     * Get the number of positive/negative trained words.
     *
     * @param string $type
     * @return int
     */
    public function getWordTypeCount(string $type): int;

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
     * Add a sentiment as either positive or negative.
     *
     * @param string $word
     * @param string $wordType
     * @return $this
     */
    public function addSentiment(string $word, string $wordType): self;

    /**
     * Get the count of how many times a sentiment has been classified as positive or negative.
     *
     * @param string $word
     * @param string $wordType
     * @return int
     */
    public function getSentimentCount(string $word, string $wordType): int;

    /**
     * Get all the trained sentiments.
     *
     * @return array
     */
    public function getSentiments(): array;

    /**
     * Load the brains data in.
     *
     * @param LoaderInterface $loader
     * @return $this
     */
    public function loadMemories(LoaderInterface $loader): self;

    /**
     * Adds a new string as training data.
     *
     * @param string $trainingData
     * @param string $dataType
     * @param int $testDataAmount
     * @return $this
     */
    public function insertTrainingData(string $trainingData, string $dataType, int $testDataAmount): self;
}