<?php

declare(strict_types=1);

namespace TomHart\SentimentAnalysis;

use PHPUnit\Framework\TestCase;

/**
 * Class StrUtilsTest
 * @package TomHart\SentimentAnalysis\Test
 */
class StrUtilsTest extends TestCase
{
    /**
     * @dataProvider splittingDataProvider
     */
    public function testSplitting($expected, $input)
    {
        static::assertEquals($expected, StrUtils::splitSentence($input));
    }

    /**
     * @return array[]
     */
    public function splittingDataProvider(): array
    {
        return [
            [['my', 'string'], 'my string']
        ];
    }
}
