<?php

/**
 * Read values from file and return all of them as array.
 *
 * @param string $path
 * @return array
 */
function read_values($path = "https://gist.githubusercontent.com/cosmologicon/1e7291714094d71a0e25678316141586/raw/006f7e9093dc7ad72b12ff9f1da649822e56d39d/tex-hyphenation-patterns.txt")
{
    if (false !== ($contents = file_get_contents($path))) {
        return explode("\n", $contents);
    } else {
        throw new RuntimeException('Failed to read ' . $path);
    }
}

function parse_arguments(array $arguments)
{
    if (count($arguments) < 2) {
        throw new RuntimeException('Must provide word!');
    }
    return $arguments[1];
}

