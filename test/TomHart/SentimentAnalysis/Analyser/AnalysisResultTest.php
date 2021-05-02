<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Analyser;

use PHPUnit\Framework\TestCase;
use TomHart\SentimentAnalysis\SentimentType;

/**
 * Class AnalysisResultTest
 * @package TomHart\SentimentAnalysis\Analyser
 */
class AnalysisResultTest extends TestCase
{
    public function testConstructor()
    {
        $result = new AnalysisResult(SentimentType::POSITIVE, 1, 2);
        self::assertEquals(SentimentType::POSITIVE, $result->getResult());
        self::assertEquals(1, $result->getPositiveAccuracy());
        self::assertEquals(2, $result->getNegativeAccuracy());
    }

    public function testResult()
    {
        $result = new AnalysisResult();
        $result->setResult(SentimentType::POSITIVE);
        self::assertEquals(SentimentType::POSITIVE, $result->getResult());
    }

    public function testPositiveAccuracy()
    {
        $result = new AnalysisResult();
        $result->setPositiveAccuracy(1);
        self::assertEquals(1, $result->getPositiveAccuracy());
    }

    public function testNegativeAccuracy()
    {
        $result = new AnalysisResult();
        $result->setNegativeAccuracy(1);
        self::assertEquals(1, $result->getNegativeAccuracy());
    }

    public function testUnsetResult()
    {
        $this->expectErrorMessage(
            'Typed property TomHart\SentimentAnalysis\Analyser\AnalysisResult::$result must not be accessed before initialization'
        );
        $result = new AnalysisResult();
        self::assertEmpty($result->getResult());
    }

    public function testUnsetPositiveAccuracy()
    {
        $this->expectErrorMessage(
            'Typed property TomHart\SentimentAnalysis\Analyser\AnalysisResult::$positiveAccuracy must not be accessed before initialization'
        );
        $result = new AnalysisResult();
        self::assertEmpty($result->getPositiveAccuracy());
    }

    public function testUnsetNegativeAccuracy()
    {
        $this->expectErrorMessage(
            'Typed property TomHart\SentimentAnalysis\Analyser\AnalysisResult::$negativeAccuracy must not be accessed before initialization'
        );
        $result = new AnalysisResult();
        self::assertEmpty($result->getNegativeAccuracy());
    }
}
