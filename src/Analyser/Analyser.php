<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Analyser;

use TomHart\SentimentAnalysis\Brain\Brain;
use TomHart\SentimentAnalysis\Brain\BrainInterface;
use TomHart\SentimentAnalysis\SentimentType;
use TomHart\SentimentAnalysis\StrUtils;

/**
 * Class Analyzer
 * @package TomHart\SentimentAnalysis
 */
class Analyser implements AnalyserInterface
{
    /**
     * What types of sentiment do we support?
     */
    public const VALID_TYPES = [SentimentType::POSITIVE, SentimentType::NEGATIVE];
    protected array $bayesDistrib;
    protected array $bayesDifference;

    /**
     * @var Brain The Analyzers trained brain.
     */
    private BrainInterface $brain;

    /**
     * Analyzer constructor.
     */
    public function __construct(BrainInterface $brain = null)
    {
        $this->bayesDifference = range(-1.0, 1.5, 0.1);
        if (!is_null($brain)) {
            $this->brain = $brain;
        }
    }

    /**
     * @param BrainInterface $brain
     * @return Analyser
     */
    public function setBrain(BrainInterface $brain): Analyser
    {
        $this->brain = $brain;
        return $this;
    }

    /**
     * @param string $string
     * @return AnalysisResult
     */
    public function analyse(string $string): AnalysisResult
    {
        if ($this->brain->getSentenceCount() === 0) {
            return new AnalysisResult(SentimentType::NEUTRAL, 0, 0);
        }

        $this->bayesDistrib = $this->calculateBayesDistribution();

        $sentimentScores = [];

        $words = StrUtils::splitSentence($string);

        foreach (self::VALID_TYPES as $type) {
            $sentimentScores[$type] = 1;

            foreach ($words as $word) {
                $tracker = $this->brain->getSentimentCount($word, $type);
                $wordCount = ($this->brain->getWordTypeCount($type) + $this->brain->getWordCount());
                $sentimentScores[$type] *= ($tracker + 1) / $wordCount;
            }
            $sentimentScores[$type] *= $this->bayesDistrib[$type];
        }

        arsort($sentimentScores);

        if (key($sentimentScores) === SentimentType::POSITIVE) {
            $bayesDifference = $sentimentScores[SentimentType::POSITIVE] / $sentimentScores[SentimentType::NEGATIVE];
        } else {
            $bayesDifference = $sentimentScores[SentimentType::NEGATIVE] / $sentimentScores[SentimentType::POSITIVE];
        }

        $positivity = $sentimentScores[SentimentType::POSITIVE] / ($sentimentScores[SentimentType::POSITIVE] + $sentimentScores[SentimentType::NEGATIVE]);
        $negativity = $sentimentScores[SentimentType::NEGATIVE] / ($sentimentScores[SentimentType::POSITIVE] + $sentimentScores[SentimentType::NEGATIVE]);

        if (in_array(round($bayesDifference, 1), $this->bayesDifference, true)) {
            $sentiment = SentimentType::NEUTRAL;
        } else {
            $sentiment = key($sentimentScores);
        }

        return new AnalysisResult($sentiment, $positivity, $negativity);
    }

    /**
     * @return int[]
     */
    private function calculateBayesDistribution(): array
    {
        $bayesDistrib = [];

        foreach (self::VALID_TYPES as $type) {
            $bayesDistrib[$type] = $this->brain->getSentenceTypeCount($type) / $this->brain->getSentenceCount();
        }

        return $bayesDistrib;
    }
}