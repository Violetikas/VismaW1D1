<?php


namespace Fikusas;

require __DIR__ . '../vendor/autoload.php';

class Main
{
public function startTime(){
    $time_start = microtime(true);
}
public function readFile (){
    $fileReader = new \Fikusas\FileRead();
    return $syllables = $fileReader->readHyphenationPatterns();

}

public function interactWithUser (){
    $userInteraction = new UserInteraction();

    return $userInput = $userInteraction->getUserInput($argv);
}

public function hyphenateUserInput(){

    $hyphenate = new \Fikusas\WordHyphenator($this->readFile());

    $sentenceHyphenator = new \Fikusas\SentenceHyphenator($hyphenate);

    $optionDivider = new OptionDivider($hyphenate, $sentenceHyphenator);

    $result = $optionDivider->divideOptions($this->interactWithUser());

    echo $result . "\n";

}

    public function stopTime (){

        $time_end = microtime(true);

        $time = $time_end - $this->startTime();

        echo "\n script took $time seconds to execute\n";
    }

}