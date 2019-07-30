<?php


namespace Fikusas\API;


use Fikusas\DB\DatabaseConnectorInterface;
use Fikusas\DB\WordDB;


class ControlerWriter
{
    /** @var DatabaseConnectorInterface */
    private $wordDB;
    /** @var TemplateRenderer */
    private $renderer;

    public function __construct(WordDB $wordDB, TemplateRenderer $renderer)
    {

        $this->wordDB = $wordDB;
        $this->renderer = $renderer;
    }

    public function insertWord(Request $request): HtmlResponse
    {
        $context = [];
        if ($request->getMethod() === 'POST') {
            $word = $request->getPostValue('word');
            $this->wordDB->writeToDB($word);
            $context['word'] = $word;
        }

        $html = $this->renderer->render('wordWriter.php', $context);
        return new HtmlResponse(200, $html);
    }
}