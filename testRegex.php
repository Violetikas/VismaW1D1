<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-04
 * Time: 11:28
 */

$path="testSentenceForRegex.txt";
$search_expression = file_get_contents($path);
$removeSomething = preg_replace('[^a-zA-Z0-9_]',$search_expression,"");
$words = preg_split('/\s/', $search_expression, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

var_dump($removeSomething);