<?php


namespace Fikusas\Patterns;


class PatternLoaderFile implements PatternLoaderInterface
{
    /** @var string */
    private $path;

    /**
     * PatternLoaderFile constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function loadPatterns(): array
    {
        $contents = file_get_contents($this->path);
        return explode("\n", $contents);
    }
}
