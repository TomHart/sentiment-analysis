<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Brain;

use TomHart\SentimentAnalysis\Brain\Traits\DefaultBrainImplementation;

/**
 * Class Brain
 * @package TomHart\SentimentAnalysis
 */
class DefaultBrain extends AbstractBrain
{
    use DefaultBrainImplementation;
}