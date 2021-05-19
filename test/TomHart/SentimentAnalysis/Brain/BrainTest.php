<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Brain;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TomHart\SentimentAnalysis\Exception\InvalidSentimentTypeException;
use TomHart\SentimentAnalysis\Memories\NoopLoader;
use TomHart\SentimentAnalysis\SentimentType;

/**
 * Class BrainTest
 * @package TomHart\SentimentAnalysis\Test
 */
class BrainTest extends TestCase
{
    private Brain $brain;

    public function testWordType(): void
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

    public function testSentiment(): void
    {
        self::assertEmpty($this->brain->getSentimentCount('abc', SentimentType::POSITIVE));
        self::assertInstanceOf(Brain::class, $this->brain->addSentiment('abc', SentimentType::POSITIVE));
        self::assertEquals(1, $this->brain->getSentimentCount('abc', SentimentType::POSITIVE));
    }

    public function testSentenceType(): void
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

    public function testIncrementSentenceType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Sentence type 'neutral' doesn't exist");

        $this->brain->incrementSentenceTypeCount(SentimentType::NEUTRAL);
    }

    public function testIncrementWordType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Word type 'neutral' doesn't exist");

        $this->brain->incrementWordTypeCount(SentimentType::NEUTRAL);
    }

    public function testSentiments(): void
    {
        self::assertEmpty($this->brain->getSentiments());
        self::assertInstanceOf(Brain::class, $this->brain->addSentiment('word', SentimentType::NEUTRAL));
        self::assertCount(1, $this->brain->getSentiments());
    }

    public function testSentenceCount(): void
    {
        self::assertEquals(0, $this->brain->getSentenceCount());
        self::assertInstanceOf(Brain::class, $this->brain->incrementSentenceTypeCount(SentimentType::POSITIVE));
        self::assertEquals(1, $this->brain->getSentenceCount());
    }

    public function testGetWordCount(): void
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
        $this->expectException(InvalidSentimentTypeException::class);
        $this->brain->insertTrainingData('trainingSet/data.neg', 'something_else', 1);
    }

    /**
     * @throws Exception
     */
    public function testTrainingTheBrain(): void
    {
        $this->brain->insertTrainingData('trainingSet/data.neg', SentimentType::NEGATIVE, 5000);
        $this->brain->insertTrainingData('trainingSet/data.pos', SentimentType::POSITIVE, 5000);

        static::assertCount(17686, $this->brain->getSentiments());
        static::assertEquals(54786, $this->brain->getWordTypeCount(SentimentType::POSITIVE));
        static::assertEquals(54037, $this->brain->getWordTypeCount(SentimentType::NEGATIVE));
        static::assertEquals(5000, $this->brain->getSentenceTypeCount(SentimentType::POSITIVE));
        static::assertEquals(5000, $this->brain->getSentenceTypeCount(SentimentType::NEGATIVE));
        static::assertEquals(108823, $this->brain->getWordCount());
        static::assertEquals(10000, $this->brain->getSentenceCount());
    }

    public function testStopWords(): void
    {
        $words = ['this', 'then', 'and', 'OF'];
        self::assertSame($this->brain, $this->brain->setStopWords($words));
        self::assertSame(['this', 'then', 'and', 'of'], $this->brain->getStopWords());
        self::assertTrue($this->brain->isStopWord('this'));
        self::assertTrue($this->brain->isStopWord('THIS'));

        $this->brain->insertTrainingSentence('this good then excellent and', SentimentType::POSITIVE);
        $this->brain->insertTrainingSentence('this bad then rubbish and', SentimentType::NEGATIVE);

        static::assertCount(4, $this->brain->getSentiments());
        static::assertEquals(2, $this->brain->getWordTypeCount(SentimentType::POSITIVE));
        static::assertEquals(2, $this->brain->getWordTypeCount(SentimentType::NEGATIVE));
        static::assertEquals(1, $this->brain->getSentenceTypeCount(SentimentType::POSITIVE));
        static::assertEquals(1, $this->brain->getSentenceTypeCount(SentimentType::NEGATIVE));
        static::assertEquals(4, $this->brain->getWordCount());
        static::assertEquals(2, $this->brain->getSentenceCount());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->brain = new Brain();
        $this->brain->loadMemories(new NoopLoader());
    }
}
