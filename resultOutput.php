<?php

function print_result(string $result): void
{
    for ($i = 0; $i < strlen($result); $i++) {
        if (!is_numeric($result[$i])) {
            continue;
        }
        if (((int)$result[$i]) % 2 !== 0) {
            $result = str_replace($result[$i], '-', $result);
        } else {
            $result = str_replace($result[$i], '', $result);
        }
    }
    echo $result."\n";

}
