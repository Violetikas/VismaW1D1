<?php
//echo 'success';

//var_dump($_SERVER['REQUEST_URI']);
header('Content-Type: application/json');
echo json_encode(['message'=>'success']);