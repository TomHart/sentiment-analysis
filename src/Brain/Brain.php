<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Brain;

use InvalidArgumentException;

/**
 * Class Brain
 * @package TomHart\SentimentAnalysis
 */
class Brain extends AbstractBrain implements \Serializable
{
    /**
     * @param string $sentenceType
     * @return $this
     */
    public function incrementSentenceTypeCount(string $sentenceType): BrainInterface
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
    public function incrementWordTypeCount(string $wordType): BrainInterface
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
    public function addSentiment(string $word, string $wordType): BrainInterface
    {
        if (!isset($this->sentiments[$word][$wordType])) {
            $this->sentiments[$word][$wordType] = 0;
        }
        $this->sentiments[$word][$wordType]++;
        return $this;
    }

    /**
     * @param string $sentence
     * @param string $sentenceType
     * @return BrainInterface
     */
    public function addSentence(string $sentence, string $sentenceType): BrainInterface
    {
        return $this;
    }

    /**
     * @return string
     */
    public function serialize(): string
    {

    }

    /**
     * @param string $data
     */
    public function unserialize($data): void
    {

    }
}