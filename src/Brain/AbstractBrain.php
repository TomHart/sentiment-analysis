<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Brain;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use TomHart\SentimentAnalysis\Memories\LoaderInterface;
use TomHart\SentimentAnalysis\SentimentType;

/**
 * Class Brain
 * @package TomHart\SentimentAnalysis
 */
abstract class AbstractBrain implements BrainInterface, StopWordsInterface, HasMemoriesInterface
{
    /** @var array Words listed how many good/bad times they were used */
    protected array $words;

    /** @var array How many positive/negative words there are */
    protected array $wordType = [];

    /** @var array How many positive/negative words there are */
    protected array $sentenceType = [];

    /** @var array|string[] List of stop words */
    protected array $stopWords = StopWords::ENGLISH;

    #[Pure]
    public function __construct()
    {
        $this->wordType = self::ensureBothValues([]);
        $this->sentenceType = self::ensureBothValues([]);
    }

    /**
     * Ensure there's both a positive and negative value.
     * @param $data
     * @return array
     */
    public static function ensureBothValues($data): array
    {
        return array_merge(
            [
                SentimentType::POSITIVE->value => 0,
                SentimentType::NEGATIVE->value => 0
            ],
            $data
        );
    }

    /**
     * @param SentimentType $type
     * @return int
     */
    public function getSentenceTypeCount(SentimentType $type): int
    {
        if (!isset($this->sentenceType[$type->value])) {
            throw new InvalidArgumentException("Sentence type '$type->value' doesn't exist");
        }

        return $this->sentenceType[$type->value];
    }

    /**
     * @param SentimentType $type
     * @return int
     */
    public function getWordTypeCount(SentimentType $type): int
    {
        if (!isset($this->wordType[$type->value])) {
            throw new InvalidArgumentException("Word type '$type->value' doesn't exist");
        }

        return $this->wordType[$type->value];
    }

    /**
     * @return int
     */
    public function getWordCount(): int
    {
        return array_sum($this->wordType);
    }

    /**
     * @return int
     */
    public function getSentenceCount(): int
    {
        return array_sum($this->sentenceType);
    }

    /**
     * @param string $word
     * @param SentimentType $wordType
     * @return int
     */
    public function getWordUsageCount(string $word, SentimentType $wordType): int
    {
        if (!isset($this->words[$word][$wordType->value])) {
            return 0;
        }
        return $this->words[$word][$wordType->value]++;
    }

    /**
     * @return array
     */
    public function getWords(): array
    {
        return $this->words;
    }

    /**
     * @return string[]
     */
    public function getStopWords(): array
    {
        return $this->stopWords;
    }

    /**
     * Set the stop words for the brain to ignore when training
     * @param array $stopWords
     * @return $this
     */
    public function setStopWords(array $stopWords): StopWordsInterface
    {
        $this->stopWords = array_map('mb_strtolower', $stopWords);
        return $this;
    }

    /**
     * Is the given word a stop word?
     * @param string $word
     * @return bool
     */
    public function isStopWord(string $word): bool
    {
        return in_array(mb_strtolower($word), $this->stopWords, true);
    }

    /**
     * @inheritDoc
     */
    public function loadMemories(LoaderInterface $loader): HasMemoriesInterface
    {
        $this->words = $loader->getSentiments();
        $this->sentenceType = self::ensureBothValues($loader->getSentenceType());
        $this->wordType = self::ensureBothValues($loader->getWordType());

        return $this;
    }
}