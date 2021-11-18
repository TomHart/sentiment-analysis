<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\School;

use TomHart\SentimentAnalysis\Brain\BrainInterface;
use TomHart\SentimentAnalysis\SentimentType;

class FileBasedLesson extends AbstractLesson
{
    public function __construct(
        private string $filePath,
        protected SentimentType $type
    ) {
    }

    /**
     * @inheritDoc
     */
    public function teach(BrainInterface $brain): BrainInterface
    {
        $testData = fopen($this->filePath, 'rb');
        while ($sentence = fgets($testData)) {
            $brain = $this->insertTrainingSentence($brain, $sentence, $this->type);
        }

        return $brain;
    }
}