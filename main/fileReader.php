<?php

/**
 * Read values from file and return all of them as array.
 *
 * @param string $path
 * @return array
 */
function read_values($path = "tex-hyphenation-patterns.txt")
{
    if (false !== ($contents = file_get_contents($path))) {
        return explode("\n", $contents);
    } else {
        throw new RuntimeException('Failed to read ' . $path);
    }
}

