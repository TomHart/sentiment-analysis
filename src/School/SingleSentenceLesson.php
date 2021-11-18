<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\School;

use TomHart\SentimentAnalysis\Brain\BrainInterface;
use TomHart\SentimentAnalysis\SentimentType;

class SingleSentenceLesson extends AbstractLesson
{
    public function __construct(
        private string $sentence,
        protected SentimentType $type
    ) {
    }

    /**
     * @inheritDoc
     */
    public function teach(BrainInterface $brain): BrainInterface
    {
        return $this->insertTrainingSentence($brain, $this->sentence, $this->type);
    }
}