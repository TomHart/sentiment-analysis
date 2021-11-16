<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\School;

use TomHart\SentimentAnalysis\Analyser\Analyser;
use TomHart\SentimentAnalysis\Brain\BrainInterface;
use TomHart\SentimentAnalysis\Exception\InvalidSentimentTypeException;
use TomHart\SentimentAnalysis\SentimentType;
use TomHart\SentimentAnalysis\StrUtils;

/**
 *
 */
class FileBasedLesson implements LessonInterface
{
    public function __construct(
        private string $filePath,
        private SentimentType $type
    ) {
    }

    public function teach(BrainInterface $brain): BrainInterface
    {
        $testData = fopen($this->filePath, 'rb');
        while ($sentence = fgets($testData)) {
            $brain = $this->insertTrainingSentence($brain, $sentence);
        }

        return $brain;
    }

    /**
     * @param BrainInterface $brain
     * @param string $sentence
     * @return BrainInterface
     */
    private function insertTrainingSentence(BrainInterface $brain, string $sentence): BrainInterface
    {
        $sentence = trim($sentence);

        $words = StrUtils::splitSentence($sentence);

        $brain->incrementSentenceTypeCount($this->type);
        $brain->addSentence($sentence, $this->type);

        foreach ($words as $word) {
            if (in_array(mb_strtolower($word), $brain->getStopWords(), true)) {
                continue;
            }

            $brain->incrementWordTypeCount($this->type);
            $brain->addWord($word, $this->type);
        }

        return $brain;
    }
}