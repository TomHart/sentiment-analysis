<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Analyser;

/**
 * Class AnalysisResult
 * @package TomHart\SentimentAnalysis
 */
class AnalysisResult
{
    private string $result;
    private float $positiveAccuracy;
    private float $negativeAccuracy;

    /**
     * AnalysisResult constructor.
     * @param string|null $result
     * @param float|null $positiveAccuracy
     * @param float|null $negativeAccuracy
     */
    public function __construct(string $result = null, float $positiveAccuracy = null, float $negativeAccuracy = null)
    {
        if ($result) {
            $this->setResult($result);
        }

        if ($positiveAccuracy) {
            $this->setPositiveAccuracy($positiveAccuracy);
        }

        if ($negativeAccuracy) {
            $this->setNegativeAccuracy($negativeAccuracy);
        }
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        return $this->result;
    }

    /**
     * @param string $result
     * @return AnalysisResult
     */
    public function setResult(string $result): AnalysisResult
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return float
     */
    public function getPositiveAccuracy(): float
    {
        return $this->positiveAccuracy;
    }

    /**
     * @param float $positiveAccuracy
     * @return AnalysisResult
     */
    public function setPositiveAccuracy(float $positiveAccuracy): AnalysisResult
    {
        $this->positiveAccuracy = $positiveAccuracy;
        return $this;
    }

    /**
     * @return float
     */
    public function getNegativeAccuracy(): float
    {
        return $this->negativeAccuracy;
    }

    /**
     * @param float $negativeAccuracy
     * @return AnalysisResult
     */
    public function setNegativeAccuracy(float $negativeAccuracy): AnalysisResult
    {
        $this->negativeAccuracy = $negativeAccuracy;
        return $this;
    }
}