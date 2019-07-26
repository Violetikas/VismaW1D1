<?php
require __DIR__ . '/vendor/autoload.php';

use Fikusas\Main;

$app = new Main();
$app->run($argv);
//var_dump(file_get_contents('/home/violeta/Documents/Visma1/tex-hyphenation-patterns.txt'));
