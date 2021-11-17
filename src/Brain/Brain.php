<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\Brain;

use TomHart\SentimentAnalysis\Brain\Traits\DefaultBrainImplementation;

/**
 * Class Brain
 * @package TomHart\SentimentAnalysis
 */
class Brain extends AbstractBrain
{
    use DefaultBrainImplementation;
}