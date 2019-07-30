<?php


namespace Fikusas\API;


class TemplateRenderer
{
    /** @var string */
    private $templateDir;

    /**
     * TemplateRenderer constructor.
     * @param string $templateDir
     */
    public function __construct(string $templateDir = __DIR__ . '/../../templates')
    {
        $this->templateDir = $templateDir;
    }

    public function render(string $template, array $context = []): string
    {
        ob_start();
        require $this->templateDir . '/' . $template;
        return ob_get_clean();
    }
}
