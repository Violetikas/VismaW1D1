<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-04
 * Time: 11:28
 */

echo str_replace("world", "Petefvraewfrewfr", "Hello world, nice to meet you!");

$hyphenator = new Fikusas\WordHyphenator();
$sentencePrepare = new \Fikusas\SentenceHyphenator($sylables, $userInput, $hyphenator);

//$hyphenate = new \Fikusas\Hyphenate($sylables, $userInput);
