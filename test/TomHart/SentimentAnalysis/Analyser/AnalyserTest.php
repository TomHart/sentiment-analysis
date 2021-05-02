<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Test\Analyser;

use Exception;
use PHPUnit\Framework\TestCase;
use TomHart\SentimentAnalysis\Analyser\Analyser;
use TomHart\SentimentAnalysis\Analyser\AnalyserInterface;
use TomHart\SentimentAnalysis\Analyser\AnalysisResult;
use TomHart\SentimentAnalysis\Brain\Brain;
use TomHart\SentimentAnalysis\Brain\BrainInterface;
use TomHart\SentimentAnalysis\Memories\NoopLoader;
use TomHart\SentimentAnalysis\SentimentType;

/**
 * Class AnalyzerTest
 * @package TomHart\SentimentAnalysis\Test
 */
class AnalyserTest extends TestCase
{
    private Analyser $sut;

    public function testImplementsInterface()
    {
        static::assertInstanceOf(AnalyserInterface::class, $this->sut);
    }

    /**
     * @throws Exception
     */
    public function testAnalyzeSentence()
    {
        $brain = $this->createMock(BrainInterface::class);

        $brain
            ->expects(static::exactly(6))
            ->method('getSentenceTypeCount')
            ->with(
                static::logicalOr(
                    static::equalTo(SentimentType::POSITIVE),
                    static::equalTo(SentimentType::NEGATIVE)
                )
            )
            ->willReturnCallback(
                static function (string $arg) {
                    return [
                        SentimentType::POSITIVE => 70,
                        SentimentType::NEGATIVE => 70
                    ][$arg];
                }
            );

        $brain
            ->expects(static::exactly(6))
            ->method('getSentenceCount')
            ->willReturn(140);

        $brain
            ->expects(static::exactly(12))
            ->method('getSentimentCount')
            ->withConsecutive(
                ['It', SentimentType::POSITIVE],
                ['was', SentimentType::POSITIVE],
                ['terrible', SentimentType::POSITIVE],
                ['It', SentimentType::NEGATIVE],
                ['was', SentimentType::NEGATIVE],
                ['terrible', SentimentType::NEGATIVE],
                ['It', SentimentType::POSITIVE],
                ['was', SentimentType::POSITIVE],
                ['amazing', SentimentType::POSITIVE],
                ['It', SentimentType::NEGATIVE],
                ['was', SentimentType::NEGATIVE],
                ['amazing', SentimentType::NEGATIVE],
            )
            ->willReturnOnConsecutiveCalls(0, 0, 0, 0, 0, 10, 0, 0, 20, 0, 0, 0);

        $brain
            ->expects(static::exactly(12))
            ->method('getWordCount')
            ->willReturn(1000);

        $this->sut->setBrain($brain);

        $negativeResult = $this->sut->analyse('It was terrible');
        $positiveResult = $this->sut->analyse('It was amazing');
        $neutralResult = $this->sut->analyse('');

        static::assertInstanceOf(AnalysisResult::class, $negativeResult);
        static::assertInstanceOf(AnalysisResult::class, $positiveResult);
        static::assertInstanceOf(AnalysisResult::class, $neutralResult);

        static::assertEquals(SentimentType::NEGATIVE, $negativeResult->getResult());
        static::assertEquals(SentimentType::POSITIVE, $positiveResult->getResult());
        static::assertEquals(SentimentType::NEUTRAL, $neutralResult->getResult());

        static::assertEquals(0.083333333333333, $negativeResult->getPositiveAccuracy());
        static::assertEquals(0.91666666666667, $negativeResult->getNegativeAccuracy());

        static::assertEquals(0.95454545454545, $positiveResult->getPositiveAccuracy());
        static::assertEquals(0.045454545454545, $positiveResult->getNegativeAccuracy());

        static::assertEquals(0.5, $neutralResult->getPositiveAccuracy());
        static::assertEquals(0.5, $neutralResult->getNegativeAccuracy());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new Analyser();
        $brain = new Brain();
        $brain->loadMemories(new NoopLoader());
        $this->sut->setBrain($brain);
    }
}
