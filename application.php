<?php
require __DIR__ . '/vendor/autoload.php';

use Fikusas\FileRead\FileRead;
use Fikusas\FileRead\FileReadFromInput;
use Fikusas\Hyphenation\WordHyphenator;
use Fikusas\Hyphenation\SentenceHyphenator;
use Fikusas\Log\Logger;
use Fikusas\UserInteraction\OptionDivider;
use Fikusas\UserInteraction\UserInteraction;
use Fikusas\TimeKeeping\TimeKeeping;
use Fikusas\Cache\FileCache;


//require_once 'config.php';
//require_once DataBaseConnect::class;
//$db = new ($pdo);
//$rows = $db->getData();

$cache = new FileCache('cache', 86400);
$fileRead = new FileRead($cache);
$syllables= $fileRead->readHyphenationPatterns();
$logger = new Logger();
$hyphenator = new WordHyphenator($syllables, $cache);
$sentenceHyphenator = new SentenceHyphenator($logger, $hyphenator);


$main = new Fikusas\Main(new TimeKeeping(), $logger, $cache,
    $fileRead, new UserInteraction(), $hyphenator,
    $sentenceHyphenator, new FileReadFromInput(), new OptionDivider($hyphenator, $sentenceHyphenator, $fileRead, new FileReadFromInput()), $hyphenator);
echo $main->mainApplication($argv) . "\n";




