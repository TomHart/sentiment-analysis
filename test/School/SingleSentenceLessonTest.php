<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Test\School;

use phpDocumentor\Reflection\Types\Static_;
use PHPUnit\Framework\TestCase;
use TomHart\SentimentAnalysis\Brain\DefaultBrain;
use TomHart\SentimentAnalysis\School\SingleSentenceLesson;
use TomHart\SentimentAnalysis\SentimentType;

/**
 * SingleSentenceLessonTest Class Test
 * @package TomHart\SentimentAnalysis\Test\School
 */
class SingleSentenceLessonTest extends TestCase
{
    /** @var SingleSentenceLesson */
    private SingleSentenceLesson $sut;

    public function testTeach(): void
    {
        $mockBrain = $this->createMock(DefaultBrain::class);

        $mockBrain
            ->expects(static::once())
            ->method('incrementSentenceTypeCount')
            ->with(SentimentType::POSITIVE);

        $mockBrain
            ->expects(static::once())
            ->method('addSentence')
            ->withConsecutive(
                ['this is a good sentence', SentimentType::POSITIVE]
            );

        $mockBrain
            ->expects(static::exactly(5))
            ->method('incrementWordTypeCount')
            ->with(SentimentType::POSITIVE);

        $mockBrain
            ->expects(static::exactly(5))
            ->method('addWord')
            ->withConsecutive(
                ['this', SentimentType::POSITIVE],
                ['is', SentimentType::POSITIVE],
                ['a', SentimentType::POSITIVE],
                ['good', SentimentType::POSITIVE],
                ['sentence', SentimentType::POSITIVE]
            );

        $this->sut->teach($mockBrain);
    }

    public function testStopWordsAreNotTaught(): void
    {
        $mockBrain = $this->createMock(DefaultBrain::class);

        $mockBrain
            ->method('getStopWords')
            ->willReturn(['this', 'is', 'a', 'the', 'was']);

        $mockBrain
            ->expects(static::exactly(2))
            ->method('addWord')
            ->withConsecutive(
                ['good', SentimentType::POSITIVE],
                ['sentence', SentimentType::POSITIVE]
            );

        $this->sut->teach($mockBrain);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new SingleSentenceLesson('this is a good sentence', SentimentType::POSITIVE);
    }
}
