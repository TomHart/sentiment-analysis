<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Memories\Saver;

use TomHart\SentimentAnalysis\Brain\BrainInterface;

/**
 * Interface SaverInterface
 * @package TomHart\SentimentAnalysis\Memories
 */
interface SaverInterface
{
    /**
     * @param BrainInterface $brain
     * @return bool
     */
    public function save(BrainInterface $brain): bool;
}