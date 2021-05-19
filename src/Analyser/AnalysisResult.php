<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Analyser;

use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use TomHart\SentimentAnalysis\SentimentType;

/**
 * Class AnalysisResult
 * @package TomHart\SentimentAnalysis
 */
class AnalysisResult implements JsonSerializable
{
    private string $result;
    private float $positiveAccuracy;
    private float $negativeAccuracy;
    private array $workings;

    /**
     * AnalysisResult constructor.
     * @param string|null $result
     * @param float|null $positiveAccuracy
     * @param float|null $negativeAccuracy
     */
    public function __construct(
        string $result = null,
        float $positiveAccuracy = null,
        float $negativeAccuracy = null,
        array $workings = null
    ) {
        if ($result) {
            $this->setResult($result);
        }

        if (!is_null($positiveAccuracy)) {
            $this->setPositiveAccuracy($positiveAccuracy);
        }

        if (!is_null($negativeAccuracy)) {
            $this->setNegativeAccuracy($negativeAccuracy);
        }

        if (!is_null($workings)) {
            $this->setWorkings($workings);
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

    /**
     * @return array
     */
    public function getWorkings(): array
    {
        return $this->workings;
    }

    /**
     * @param array $workings
     * @return AnalysisResult
     */
    public function setWorkings(array $workings): AnalysisResult
    {
        $this->workings = $workings;
        return $this;
    }

    /**
     * @return array
     */
    #[ArrayShape(['result' => 'string', 'accuracy' => 'array'])]
    public function jsonSerialize(): array
    {
        return [
            'result' => $this->result,
            'accuracy' => [
                SentimentType::POSITIVE => $this->positiveAccuracy,
                SentimentType::NEGATIVE => $this->negativeAccuracy
            ]
        ];
    }
}