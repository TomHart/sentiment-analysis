<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Analyser;

use TomHart\SentimentAnalysis\Brain\DefaultBrain;
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
     * @var DefaultBrain The Analyzers trained brain.
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
        $workings = [];

        $words = StrUtils::splitSentence($string);

        foreach (self::VALID_TYPES as $type) {
            $sentimentScores[$type->value] = 1;

            foreach ($words as $word) {
                if (empty($word) || $this->brain->isStopWord($word)) {
                    continue;
                }

                $tracker = $this->brain->getWordUsageCount($word, $type);
                $wordCount = ($this->brain->getWordTypeCount($type) + $this->brain->getWordCount());
                $sentimentScores[$type->value] *= ($tracker + 1) / $wordCount;
                $workings[$word][$type->value] = [
                    sprintf('times_word_used_in_%s_context', $type->name) => $tracker + 1,
                    sprintf('total_words_plus_%s_words', $type->name) => $wordCount,
                    'score' => ($tracker + 1) / $wordCount
                ];
            }
            $sentimentScores[$type->value] *= $this->bayesDistrib[$type->value];
        }

        arsort($sentimentScores);

        if (key($sentimentScores) === SentimentType::POSITIVE->value) {
            $bayesDifference = $sentimentScores[SentimentType::POSITIVE->value];
            if ($sentimentScores[SentimentType::NEGATIVE->value] > 0) {
                $bayesDifference = $sentimentScores[SentimentType::POSITIVE->value] / $sentimentScores[SentimentType::NEGATIVE->value];
            }
        } else {
            $bayesDifference = $sentimentScores[SentimentType::NEGATIVE->value];
            if ($sentimentScores[SentimentType::POSITIVE->value] > 0) {
                $bayesDifference = $sentimentScores[SentimentType::NEGATIVE->value] / $sentimentScores[SentimentType::POSITIVE->value];
            }
        }

        $positivity = $sentimentScores[SentimentType::POSITIVE->value] / ($sentimentScores[SentimentType::POSITIVE->value] + $sentimentScores[SentimentType::NEGATIVE->value]);
        $negativity = $sentimentScores[SentimentType::NEGATIVE->value] / ($sentimentScores[SentimentType::POSITIVE->value] + $sentimentScores[SentimentType::NEGATIVE->value]);

        if (in_array(round($bayesDifference, 1), $this->bayesDifference, true)) {
            $sentiment = SentimentType::NEUTRAL;
        } else {
            $sentiment = SentimentType::from(key($sentimentScores));
        }

        return new AnalysisResult($sentiment, $positivity, $negativity, $workings);
    }

    /**
     * @return int[]
     */
    private function calculateBayesDistribution(): array
    {
        $bayesDistrib = [];

        foreach (self::VALID_TYPES as $type) {
            $bayesDistrib[$type->value] = $this->brain->getSentenceTypeCount($type) / $this->brain->getSentenceCount();
        }

        return $bayesDistrib;
    }
}