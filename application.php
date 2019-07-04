<?php

require __DIR__ .'/fileReader.php';
require __DIR__ .'/hyphenation.php';
require __DIR__ .'/resultOutput.php';

$values = read_values();
$word = parse_arguments($argv);
$result = hyphenate($word, $values);
print_result($result);
