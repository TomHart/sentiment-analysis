<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Analyser;

use TomHart\SentimentAnalysis\Brain\BrainInterface;

/**
 * Interface AnalyzerInterface
 * @package TomHart\SentimentAnalysis
 */
interface AnalyserInterface
{
    /**
     * Set the analyser brain.
     * @param BrainInterface $brain
     * @return AnalyserInterface
     */
    public function setBrain(BrainInterface $brain): AnalyserInterface;

    /**
     * Analyse a string for it's sentiment.
     * @param string $string
     * @return AnalysisResult
     */
    public function analyse(string $string): AnalysisResult;
}