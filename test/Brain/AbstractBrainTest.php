<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Test\Brain;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TomHart\SentimentAnalysis\Brain\AbstractBrain;
use TomHart\SentimentAnalysis\Brain\StopWords;

/**
 * AbstractBrainTest Class Test
 * @package TomHart\SentimentAnalysis\Test\Brain
 */
class AbstractBrainTest extends TestCase
{
    /** @var AbstractBrain|MockObject */
    private MockObject|AbstractBrain $sut;

    public function testSetStopWords(): void
    {
        static::assertSame($this->sut, $this->sut->setStopWords(['and', 'or']));
        static::assertEquals(['and', 'or'], $this->sut->getStopWords());
    }

    public function testIsStopWord(): void
    {
        static::assertSame($this->sut, $this->sut->setStopWords(['and', 'or']));
        static::assertTrue($this->sut->isStopWord('and'));
        static::assertFalse($this->sut->isStopWord('is'));
    }

    public function testGetStopWords(): void
    {
        static::assertEquals(StopWords::ENGLISH, $this->sut->getStopWords());
        $this->sut->setStopWords(['and', 'or']);
        static::assertEquals(['and', 'or'], $this->sut->getStopWords());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = $this->getMockForAbstractClass(AbstractBrain::class);
    }
}
