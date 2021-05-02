<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Brain;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TomHart\SentimentAnalysis\Memories\NoopLoader;
use TomHart\SentimentAnalysis\SentimentType;

/**
 * Class BrainTest
 * @package TomHart\SentimentAnalysis\Test
 */
class BrainTest extends TestCase
{
    private Brain $brain;

    public function testWordType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Word type 'neutral' doesn't exist");

        self::assertEquals(0, $this->brain->getWordTypeCount(SentimentType::POSITIVE));
        self::assertEquals(0, $this->brain->getWordTypeCount(SentimentType::NEGATIVE));
        self::assertInstanceOf(Brain::class, $this->brain->incrementWordTypeCount(SentimentType::POSITIVE));
        self::assertEquals(1, $this->brain->getWordTypeCount(SentimentType::POSITIVE));
        self::assertEquals(0, $this->brain->getWordTypeCount(SentimentType::NEGATIVE));
        $this->brain->getWordTypeCount(SentimentType::NEUTRAL);
    }

    public function testSentiment()
    {
        self::assertEmpty($this->brain->getSentimentCount('abc', SentimentType::POSITIVE));
        self::assertInstanceOf(Brain::class, $this->brain->addSentiment('abc', SentimentType::POSITIVE));
        self::assertEquals(1, $this->brain->getSentimentCount('abc', SentimentType::POSITIVE));
    }

    public function testSentenceType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Sentence type 'neutral' doesn't exist");

        self::assertEquals(0, $this->brain->getSentenceTypeCount(SentimentType::POSITIVE));
        self::assertEquals(0, $this->brain->getSentenceTypeCount(SentimentType::NEGATIVE));
        self::assertEquals(0, $this->brain->getSentenceTypeCount(SentimentType::POSITIVE));
        self::assertEquals(0, $this->brain->getSentenceTypeCount(SentimentType::NEGATIVE));
        self::assertInstanceOf(Brain::class, $this->brain->incrementSentenceTypeCount(SentimentType::POSITIVE));
        self::assertInstanceOf(Brain::class, $this->brain->incrementSentenceTypeCount(SentimentType::NEGATIVE));
        self::assertEquals(1, $this->brain->getSentenceTypeCount(SentimentType::POSITIVE));
        self::assertEquals(1, $this->brain->getSentenceTypeCount(SentimentType::NEGATIVE));
        $this->brain->getSentenceTypeCount(SentimentType::NEUTRAL);
    }

    public function testIncrementSentenceType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Sentence type 'neutral' doesn't exist");

        $this->brain->incrementSentenceTypeCount(SentimentType::NEUTRAL);
    }

    public function testIncrementWordType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Word type 'neutral' doesn't exist");

        $this->brain->incrementWordTypeCount(SentimentType::NEUTRAL);
    }

    public function testSentiments()
    {
        self::assertEmpty($this->brain->getSentiments());
        self::assertInstanceOf(Brain::class, $this->brain->addSentiment('word', SentimentType::NEUTRAL));
        self::assertCount(1, $this->brain->getSentiments());
    }

    public function testSentenceCount()
    {
        self::assertEquals(0, $this->brain->getSentenceCount());
        self::assertInstanceOf(Brain::class, $this->brain->incrementSentenceTypeCount(SentimentType::POSITIVE));
        self::assertEquals(1, $this->brain->getSentenceCount());
    }

    public function testGetWordCount()
    {
        self::assertEquals(0, $this->brain->getWordCount());
        self::assertInstanceOf(Brain::class, $this->brain->incrementWordTypeCount(SentimentType::POSITIVE));
        self::assertEquals(1, $this->brain->getWordCount());
    }

    /**
     * @throws Exception
     */
    public function testInvalidSentimentType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Invalid Sentiment Type Encountered: A Sentiment Can Only Be Negative or Positive'
        );
        $this->brain->insertTrainingData('', 'something_else', 1);
    }

    /**
     * @throws Exception
     */
    public function testTrainingTheBrain()
    {
        $this->brain->insertTrainingData('trainingSet/data.neg', SentimentType::NEGATIVE, 5000);
        $this->brain->insertTrainingData('trainingSet/data.pos', SentimentType::POSITIVE, 5000);

        static::assertCount(17812, $this->brain->getSentiments());
        static::assertEquals(96798, $this->brain->getWordTypeCount(SentimentType::POSITIVE));
        static::assertEquals(96840, $this->brain->getWordTypeCount(SentimentType::NEGATIVE));
        static::assertEquals(5000, $this->brain->getSentenceTypeCount(SentimentType::POSITIVE));
        static::assertEquals(5000, $this->brain->getSentenceTypeCount(SentimentType::NEGATIVE));
        static::assertEquals(193638, $this->brain->getWordCount());
        static::assertEquals(10000, $this->brain->getSentenceCount());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->brain = new Brain();
        $this->brain->loadMemories(new NoopLoader());
    }
}
