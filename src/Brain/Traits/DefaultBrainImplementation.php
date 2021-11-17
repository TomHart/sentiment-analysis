<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Brain\Traits;

use InvalidArgumentException;
use TomHart\SentimentAnalysis\Brain\BrainInterface;
use TomHart\SentimentAnalysis\SentimentType;

/**
 * A default implementation for a brain.
 */
trait DefaultBrainImplementation
{
    /**
     * @param SentimentType $sentenceType
     * @return BrainInterface
     */
    public function incrementSentenceTypeCount(SentimentType $sentenceType): BrainInterface
    {
        if (!isset($this->sentenceType[$sentenceType->value])) {
            throw new InvalidArgumentException("Sentence type '$sentenceType->value' doesn't exist");
        }

        $this->sentenceType[$sentenceType->value]++;
        return $this;
    }

    /**
     * @param SentimentType $wordType
     * @return BrainInterface
     */
    public function incrementWordTypeCount(SentimentType $wordType): BrainInterface
    {
        if (!isset($this->wordType[$wordType->value])) {
            throw new InvalidArgumentException("Word type '$wordType->value' doesn't exist");
        }

        $this->wordType[$wordType->value]++;
        return $this;
    }

    /**
     * @param string $word
     * @param SentimentType $wordType
     * @return BrainInterface
     */
    public function addWord(string $word, SentimentType $wordType): BrainInterface
    {
        if (!isset($this->words[$word][$wordType->value])) {
            $this->words[$word][$wordType->value] = 0;
        }
        $this->words[$word][$wordType->value]++;
        return $this;
    }

    /**
     * @param string $sentence
     * @param SentimentType $sentenceType
     * @return BrainInterface
     */
    public function addSentence(string $sentence, SentimentType $sentenceType): BrainInterface
    {
        return $this;
    }
}