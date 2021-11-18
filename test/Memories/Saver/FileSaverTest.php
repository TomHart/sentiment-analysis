<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Test\Memories\Saver;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TomHart\SentimentAnalysis\Brain\BrainInterface;
use TomHart\SentimentAnalysis\Memories\Saver\FileSaver;

/**
 * FileSaverTest Class Test
 * @package TomHart\SentimentAnalysis\Test\Memories\Saver
 */
class FileSaverTest extends TestCase
{
    /** @var FileSaver */
    private $sut;

    private string|false $path;

    private MockObject|BrainInterface $brain;

    protected function setUp(): void
    {
        parent::setUp();
        $this->path = tempnam(sys_get_temp_dir(), 'saver_');
        $this->brain = $this->createMock(BrainInterface::class);
        $this->sut = new FileSaver($this->path);
    }

    public function testFileIsCreated(): void
    {
        static::assertTrue($this->sut->save($this->brain));
        static::assertFileExists($this->path);
    }
}
