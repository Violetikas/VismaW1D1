<?php


namespace Fikusas\API;

use Fikusas\DB\DatabaseConnectorInterface;
use Fikusas\DB\HyphenatedWordsDB;
use Fikusas\DB\PatternDB;
use Fikusas\DB\WordDB;
use Fikusas\Hyphenation\WordHyphenatorInterface;
use Fikusas\Patterns\PatternLoaderInterface;

class Controller
{

    /** @var DatabaseConnectorInterface */
    private $dbConfig;
    private $patterns;
    private $patternDB;
    private $hdb;
    private $wordDB;
    private $hyphenator;
    private $response;


    public function __construct(Response $response, WordHyphenatorInterface $hyphenator, PatternLoaderInterface $loader, DatabaseConnectorInterface $dbConfig, WordDB $wordDB, PatternDB $patternDB, HyphenatedWordsDB $hdb)
    {
        $this->patterns = $loader->loadPatterns();
        $this->dbConfig = $dbConfig;
        $this->wordDB = $wordDB;
        $this->patternDB = $patternDB;
        $this->hdb = $hdb;
        $this->hyphenator = $hyphenator;
        $this->response = $response;
    }

    public function getWords(): Response
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

        if (!$words == null) {
            return new Response($words);
        } else {
            return new Response(array("message" => "No words found."), 404);
        }
    }

    public function respond(Response $response): void
    {
        http_response_code($this->response->getStatus());
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        echo $this->response->getContentEncoded();
    }

    public function insertWord(): Response
    {
        $input = json_decode(file_get_contents('php://input'));
        $word = $input->word;
        $this->wordDB->writeToDB($word);
        return new Response(['message' => "Word written to DB", "word" => $word], 201);
    }

    public function deleteWord(string $word): Response
    {
        $this->wordDB->deleteFromDB($word);
        return new Response(['message' => "Word deleted from to DB", "word" => $word], 200);
    }

    public function updateWord(string $word): Response
    {
        $hyphenated = $this->hyphenator->hyphenate($word);

        return new Response(['message' => "Word updated", "word" => $word, "hyphenated" => $hyphenated], 200);
    }
}


