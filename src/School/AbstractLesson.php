<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis\School;

use TomHart\SentimentAnalysis\Brain\BrainInterface;
use TomHart\SentimentAnalysis\SentimentType;
use TomHart\SentimentAnalysis\StrUtils;

/**
 *
 */
abstract class AbstractLesson implements LessonInterface
{
    /**
     * @param BrainInterface $brain
     * @param string $sentence
     * @param SentimentType $type
     * @return BrainInterface
     */
    protected function insertTrainingSentence(
        BrainInterface $brain,
        string $sentence,
        SentimentType $type
    ): BrainInterface {
        $sentence = trim($sentence);

        $words = StrUtils::splitSentence($sentence);

        $brain->incrementSentenceTypeCount($type);
        $brain->addSentence($sentence, $type);

        foreach ($words as $word) {
            if (in_array(mb_strtolower($word), $brain->getStopWords(), true)) {
                continue;
            }

            $brain->incrementWordTypeCount($type);
            $brain->addWord($word, $type);
        }

        return $brain;
    }
}