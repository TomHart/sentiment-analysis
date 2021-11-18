<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Memories\Saver;

use TomHart\SentimentAnalysis\Brain\BrainInterface;

/**
 * Class FileSaver
 * @package TomHart\SentimentAnalysis\Memories
 */
class FileSaver implements SaverInterface
{
    private string $filePath;

    /**
     * FileSaver constructor.
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @inheritDoc
     */
    public function save(BrainInterface $brain): bool
    {

        return file_put_contents($this->filePath, serialize($brain)) !== false;
    }
}