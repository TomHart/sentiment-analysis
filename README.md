# PHP Sentiment Analysis

A library to provide a trainable sentiment analyser. 

## Usage

```php
// Create a brain.
use TomHart\SentimentAnalysis\Analyser\Analyser;
use TomHart\SentimentAnalysis\Brain\Brain;
use TomHart\SentimentAnalysis\SentimentType;

$brain = new Brain();

// Train the brain.
$brain->insertTrainingSentence('This is a good sentence', SentimentType::POSITIVE);

// Create an analyser.
$analyser = new Analyser($brain);

// Test it on a sentence.
$result = $analyser->analyse('The experience I had was good');

var_export($result);
TomHart\SentimentAnalysis\Analyser\AnalysisResult(
[
   'result' => 'positive',
   'positiveAccuracy' => 1.0,
   'negativeAccuracy' => 0.0,
   'workings' => [
    'experience' => [
      'positive' => [
        'times_word_used_in_positive_context' => 1,
        'total_words_plus_positive_words' => 4,
        'score' => 0.25,
      ],
      'negative' => [
        'times_word_used_in_negative_context' => 1,
        'total_words_plus_negative_words' => 2,
        'score' => 0.5,
      ],
    ],
    'good' => [
      'positive' => [
        'times_word_used_in_positive_context' => 2,
        'total_words_plus_positive_words' => 4,
        'score' => 0.5,
      ],
      'negative' => [
        'times_word_used_in_negative_context' => 1,
        'total_words_plus_negative_words' => 2,
        'score' => 0.5,
      ],
    ],
  ],
]);
```