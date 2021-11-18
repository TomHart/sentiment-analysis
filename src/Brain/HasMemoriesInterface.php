<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Brain;

use TomHart\SentimentAnalysis\Memories\LoaderInterface;

/**
 *
 */
interface HasMemoriesInterface
{
    /**
     * Load the brain's data in.
     *
     * @param LoaderInterface $loader
     * @return $this
     */
    public function loadMemories(LoaderInterface $loader): self;
}