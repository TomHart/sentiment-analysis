<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Brain;

use InvalidArgumentException;
use TomHart\SentimentAnalysis\Analyser\Analyser;
use TomHart\SentimentAnalysis\Memories\LoaderInterface;
use TomHart\SentimentAnalysis\SentimentType;
use TomHart\SentimentAnalysis\StrUtils;

/**
 * Class Brain
 * @package TomHart\SentimentAnalysis
 */
abstract class AbstractBrain implements BrainInterface
{
    protected array $sentiments;
    protected array $wordType;
    protected array $sentenceType;

    /**
     * @param string $type
     * @return int
     */
    public function getSentenceTypeCount(string $type): int
    {
        if (!isset($this->sentenceType[$type])) {
            throw new InvalidArgumentException("Sentence type '$type' doesn't exist");
        }

        return $this->sentenceType[$type];
    }

    /**
     * @param string $type
     * @return int
     */
    public function getWordTypeCount(string $type): int
    {
        if (!isset($this->wordType[$type])) {
            throw new InvalidArgumentException("Word type '$type' doesn't exist");
        }

        return $this->wordType[$type];
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
     * @param string $wordType
     * @return int
     */
    public function getSentimentCount(string $word, string $wordType): int
    {
        if (!isset($this->sentiments[$word][$wordType])) {
            return 0;
        }
        return $this->sentiments[$word][$wordType]++;
    }

    /**
     * @return array
     */
    public function getSentiments(): array
    {
        return $this->sentiments;
    }

    /**
     * @param LoaderInterface $loader
     * @return $this
     */
    public function loadMemories(LoaderInterface $loader): BrainInterface
    {
        $this->sentiments = $loader->getSentiments();
        $this->sentenceType = self::format($loader->getSentenceType());
        $this->wordType = self::format($loader->getWordType());

        return $this;
    }

    /**
     * @param $data
     * @return array
     */
    public static function format($data): array
    {
        return array_merge(
            [
                SentimentType::POSITIVE => 0,
                SentimentType::NEGATIVE => 0
            ],
            $data
        );
    }

    /**
     * @param string $trainingData
     * @param string $dataType
     * @param int $testDataAmount
     * @return BrainInterface
     */
    public function insertTrainingData(string $trainingData, string $dataType, int $testDataAmount): BrainInterface
    {
        if (!in_array($dataType, Analyser::VALID_TYPES, true)) {
            throw new InvalidArgumentException(
                'Invalid Sentiment Type Encountered: A Sentiment Can Only Be Negative or Positive'
            );
        }

        $amountTracker = 0;
        $testData = fopen($trainingData, 'rb');
        while ($sentence = fgets($testData)) {
            if ($amountTracker >= $testDataAmount && $testDataAmount > 0) {
                break;
            }

            $sentence = trim($sentence);

            $amountTracker++;
            $words = StrUtils::splitSentence($sentence);

            $this->incrementSentenceTypeCount($dataType);
            $this->addSentence($sentence, $dataType);

            foreach ($words as $word) {
                $this->incrementWordTypeCount($dataType);
                $this->addSentiment($word, $dataType);
            }
        }

        return $this;
    }
}