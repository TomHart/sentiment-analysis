<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Brain;

use InvalidArgumentException;

/**
 * Class Brain
 * @package TomHart\SentimentAnalysis
 */
class Brain extends AbstractBrain
{
    /**
     * @param string $sentenceType
     * @return $this
     */
    public function incrementSentenceTypeCount(string $sentenceType): self
    {
        if (!isset($this->sentenceType[$sentenceType])) {
            throw new InvalidArgumentException("Sentence type '$sentenceType' doesn't exist");
        }

        $this->sentenceType[$sentenceType]++;
        return $this;
    }

    /**
     * @param string $wordType
     * @return $this
     */
    public function incrementWordTypeCount(string $wordType): self
    {
        if (!isset($this->wordType[$wordType])) {
            throw new InvalidArgumentException("Word type '$wordType' doesn't exist");
        }

        $this->wordType[$wordType]++;
        return $this;
    }

    /**
     * @param string $word
     * @param string $wordType
     * @return $this
     */
    public function addSentiment(string $word, string $wordType): self
    {
        if (!isset($this->sentiments[$word][$wordType])) {
            $this->sentiments[$word][$wordType] = 0;
        }
        $this->sentiments[$word][$wordType]++;
        return $this;
    }
}