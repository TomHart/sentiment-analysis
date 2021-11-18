<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Analyser;

use Exception;
use PHPUnit\Framework\TestCase;
use TomHart\SentimentAnalysis\Brain\DefaultBrain;
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

    public function testImplementsInterface(): void
    {
        static::assertInstanceOf(AnalyserInterface::class, $this->sut);
    }

    /**
     * @throws Exception
     */
    public function testAnalyzeSentence(): void
    {
        $brain = $this->createMock(DefaultBrain::class);

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
                static function (SentimentType $arg) {
                    return [
                        SentimentType::POSITIVE->value => 70,
                        SentimentType::NEGATIVE->value => 70
                    ][$arg->value];
                }
            );

        $brain
            ->expects(static::exactly(9))
            ->method('getSentenceCount')
            ->willReturn(140);

        $brain
            ->expects(static::exactly(4))
            ->method('getWordUsageCount')
            ->withConsecutive(
                ['terrible', SentimentType::POSITIVE],
                ['terrible', SentimentType::NEGATIVE],
                ['amazing', SentimentType::POSITIVE],
                ['amazing', SentimentType::NEGATIVE],
            )
            ->willReturnOnConsecutiveCalls(0, 10, 10, 0);

        $brain
            ->expects(static::exactly(4))
            ->method('getWordCount')
            ->willReturn(1000);

        $brain
            ->expects(static::exactly(12))
            ->method('isStopWord')
            ->willReturnMap(
                [
                    ['It', true],
                    ['was', true],
                    ['terrible', false],
                    ['amazing', false],
                ]
            );

        $this->sut->setBrain($brain);

        $negativeResult = $this->sut->analyse('It was terrible');
        $positiveResult = $this->sut->analyse('It was amazing');
        $neutralResult = $this->sut->analyse('');

        static::assertEquals(SentimentType::NEGATIVE, $negativeResult->getResult());
        static::assertEquals(SentimentType::POSITIVE, $positiveResult->getResult());
        static::assertEquals(SentimentType::NEUTRAL, $neutralResult->getResult());

        static::assertEquals(0.083333333333333, $negativeResult->getPositiveAccuracy());
        static::assertEquals(0.91666666666667, $negativeResult->getNegativeAccuracy());

        static::assertEquals(0.91666666666667, $positiveResult->getPositiveAccuracy());
        static::assertEquals(0.083333333333333, $positiveResult->getNegativeAccuracy());

        static::assertEquals(0.5, $neutralResult->getPositiveAccuracy());
        static::assertEquals(0.5, $neutralResult->getNegativeAccuracy());
    }

    public function testUntrainedBrain(): void
    {
        $brain = $this->createMock(BrainInterface::class);
        $brain
            ->expects(static::once())
            ->method('getSentenceCount')
            ->willReturn(0);

        $this->sut->setBrain($brain);

        $neutralResult = $this->sut->analyse('It was terrible');
        self::assertEquals(new AnalysisResult(SentimentType::NEUTRAL, 0, 0), $neutralResult);

        static::assertEquals(SentimentType::NEUTRAL, $neutralResult->getResult());
        static::assertEquals(0, $neutralResult->getPositiveAccuracy());
        static::assertEquals(0, $neutralResult->getNegativeAccuracy());
    }

    public function testBrainCanBeSet(): void
    {
        self::assertInstanceOf(AnalyserInterface::class, $this->sut->setBrain(new DefaultBrain()));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $brain = new DefaultBrain();
        $brain->loadMemories(new NoopLoader());
        $this->sut = new Analyser($brain);
    }
}
