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
    public function testConstructor(): void
    {
        $result = new AnalysisResult(SentimentType::POSITIVE, 1, 2, ['a' => 'b']);
        self::assertEquals(SentimentType::POSITIVE, $result->getResult());
        self::assertEquals(1, $result->getPositiveAccuracy());
        self::assertEquals(2, $result->getNegativeAccuracy());
        self::assertSame(
            [
                'result' => SentimentType::POSITIVE,
                'accuracy' => [
                    SentimentType::POSITIVE => 1.0,
                    SentimentType::NEGATIVE => 2.0
                ]
            ],
            $result->jsonSerialize()
        );
        self::assertSame(['a' => 'b'], $result->getWorkings());
    }

    public function testResult(): void
    {
        $result = new AnalysisResult();
        $result->setResult(SentimentType::POSITIVE);
        self::assertEquals(SentimentType::POSITIVE, $result->getResult());
    }

    public function testPositiveAccuracy(): void
    {
        $result = new AnalysisResult();
        $result->setPositiveAccuracy(1);
        self::assertEquals(1, $result->getPositiveAccuracy());
    }

    public function testNegativeAccuracy(): void
    {
        $result = new AnalysisResult();
        $result->setNegativeAccuracy(1);
        self::assertEquals(1, $result->getNegativeAccuracy());
    }

    public function testUnsetResult(): void
    {
        $this->expectErrorMessage(
            'Typed property TomHart\SentimentAnalysis\Analyser\AnalysisResult::$result must not be accessed before initialization'
        );
        $result = new AnalysisResult();
        self::assertEmpty($result->getResult());
    }

    public function testUnsetPositiveAccuracy(): void
    {
        $this->expectErrorMessage(
            'Typed property TomHart\SentimentAnalysis\Analyser\AnalysisResult::$positiveAccuracy must not be accessed before initialization'
        );
        $result = new AnalysisResult();
        self::assertEmpty($result->getPositiveAccuracy());
    }

    public function testUnsetNegativeAccuracy(): void
    {
        $this->expectErrorMessage(
            'Typed property TomHart\SentimentAnalysis\Analyser\AnalysisResult::$negativeAccuracy must not be accessed before initialization'
        );
        $result = new AnalysisResult();
        self::assertEmpty($result->getNegativeAccuracy());
    }
}
