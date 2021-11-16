<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\School;

use TomHart\SentimentAnalysis\Brain\BrainInterface;

/**
 * Interface LessonInterface
 * @package TomHart\SentimentAnalysis\School
 */
interface LessonInterface
{
    /**
     * Teach a brain.
     * @param BrainInterface $brain
     * @return BrainInterface
     */
    public function teach(BrainInterface $brain): BrainInterface;
}