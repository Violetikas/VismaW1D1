<?php


namespace Fikusas\API;


use Fikusas\DB\DatabaseConnectorInterface;

class ControlerWordList
{
    /** @var DatabaseConnectorInterface */
    private $dbConfig;
    /** @var TemplateRenderer */
    private $renderer;

    public function __construct(DatabaseConnectorInterface $dbConfig, TemplateRenderer $renderer){

        $this->dbConfig = $dbConfig;
        $this->renderer = $renderer;
    }

    public function getWords(): HtmlResponse
    {
        $pdo = $this->dbConfig->getConnection();
        $words = array();
        $data = $pdo->prepare('select * from Words inner join HyphenatedWords HW on Words.word_id = HW.word_id order by Words.word_id');
        $data->execute();

        foreach ($data as $outputData) {
            $words[$outputData['word_id']] = array(
                'word_id' => $outputData['word_id'],
                'word' => $outputData['word'],
                'hyphenatedWord' => $outputData['hyphenatedWord']);
        }

        $html = $this->renderer->render('wordlist.php', ['words' => $words]);
        return new HtmlResponse(200, $html);

    }

}
