<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Test\School;

use PHPUnit\Framework\TestCase;
use TomHart\SentimentAnalysis\Brain\DefaultBrain;
use TomHart\SentimentAnalysis\School\FileBasedLesson;
use TomHart\SentimentAnalysis\SentimentType;

/**
 * FileBasedLessonTest Class Test
 * @package TomHart\SentimentAnalysis\Test\School
 */
class FileBasedLessonTest extends TestCase
{
    /** @var FileBasedLesson */
    private FileBasedLesson $sut;

    public function testTeach(): void
    {
        $mockBrain = $this->createMock(DefaultBrain::class);

        $mockBrain
            ->expects(static::exactly(2))
            ->method('incrementSentenceTypeCount')
            ->with(SentimentType::POSITIVE);

        $mockBrain
            ->expects(static::exactly(2))
            ->method('addSentence')
            ->withConsecutive(
                ['this is a good sentence', SentimentType::POSITIVE],
                ['the service was great', SentimentType::POSITIVE]
            );

        $mockBrain
            ->expects(static::exactly(9))
            ->method('incrementWordTypeCount')
            ->with(SentimentType::POSITIVE);

        $mockBrain
            ->expects(static::exactly(9))
            ->method('addWord')
            ->withConsecutive(
                ['this', SentimentType::POSITIVE],
                ['is', SentimentType::POSITIVE],
                ['a', SentimentType::POSITIVE],
                ['good', SentimentType::POSITIVE],
                ['sentence', SentimentType::POSITIVE],
                ['the', SentimentType::POSITIVE],
                ['service', SentimentType::POSITIVE],
                ['was', SentimentType::POSITIVE],
                ['great', SentimentType::POSITIVE]
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
            ->expects(static::exactly(4))
            ->method('addWord')
            ->withConsecutive(
                ['good', SentimentType::POSITIVE],
                ['sentence', SentimentType::POSITIVE],
                ['service', SentimentType::POSITIVE],
                ['great', SentimentType::POSITIVE]
            );

        $this->sut->teach($mockBrain);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $dir = realpath(__DIR__ . '/example.data');
        $this->sut = new FileBasedLesson($dir, SentimentType::POSITIVE);
    }
}
