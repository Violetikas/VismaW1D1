<?php


namespace Fikusas\API;
use Fikusas\Config\JsonConfigLoader;
use Fikusas\DB\DatabaseConnector;
use Fikusas\DB\DatabaseConnectorInterface;
use Fikusas\DB\WordDB;
use Fikusas\Hyphenation\DBHyphenator;
use Fikusas\Hyphenation\WordHyphenator;
use Fikusas\Patterns\PatternLoaderDb;

class Controller
{

    /** @var DatabaseConnectorInterface */
    private $connector;
    private $config;

    public function __construct()
    {
        $this->config = JsonConfigLoader::load(__DIR__ . '/../../config.json');
        $this->connector = new DatabaseConnector($this->config);
    }

    public function getWords(): Response
    {
        $pdo = $this->connector->getConnection();
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
        http_response_code($response->getStatus());
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        echo $response->getContentEncoded();
    }

    public function insertWord(): Response
    {
        $wordDb = new WordDB($this->connector);
        $input = json_decode(file_get_contents('php://input'));
        $word = $input->word;
        $wordDb->writeWordToDB($word);
        return new Response(['message' => "Word written to DB", "word" => $word], 201);
    }

    public function deleteWord(string $word): Response
    {
        $wordDb = new WordDB($this->connector);
        $wordDb->deleteWord($word);
        return new Response(['message' => "Word deleted from to DB", "word" => $word], 200);
    }

    public function updateWord(string $word): Response
    {
        $loader = new PatternLoaderDb($this->connector);
        $hyphenator = new DBHyphenator(new WordHyphenator($loader, $this->connector), new WordDB($this->connector));
        $hyphenated = $hyphenator->hyphenate($word);

        return new Response(['message' => "Word updated", "word" => $word, "hyphenated" => $hyphenated], 200);
    }
}