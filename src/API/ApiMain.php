<?php


namespace Fikusas\API;

use Fikusas\Config\JsonConfigLoader;
use Fikusas\DB\DatabaseConnector;
use Fikusas\DB\WordDB;
use Fikusas\Hyphenation\DBHyphenator;
use Fikusas\Hyphenation\WordHyphenator;
use Fikusas\Patterns\PatternLoaderDb;
use Fikusas\Patterns\PatternLoaderFile;
use PDO;

class ApiMain
{
    /** @var DatabaseConnector */
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

    private function respond(Response $response): void
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

    private function updateWord(string $word): Response
    {
        $loader = new PatternLoaderDb($this->connector);
        $hyphenator = new DBHyphenator(new WordHyphenator($loader, $this->connector), new WordDB($this->connector));
        $hyphenated = $hyphenator->hyphenate($word);

        return new Response(['message' => "Word updated", "word" => $word, "hyphenated" => $hyphenated], 200);
    }

    public function handle()
    {
        // Remove fixed prefix from URL.
        $uri = substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['SCRIPT_NAME'])));
        $method = $_SERVER['REQUEST_METHOD'];
        if ($uri == '/words') {
            if ($method == 'GET') {
                $response = $this->getWords();
            } elseif ($method == 'POST') {
                $response = $this->insertWord();
            }
        } elseif (preg_match('#^/words/(.+)#', $uri, $matches)) {
            $word = $matches[1];
            if ($method == 'DELETE') {
                $response = $this->deleteWord($word);
            } elseif ($method == 'POST') {
                $response = $this->updateWord($word);
            }
        }

        if (!isset($response)) {
            $response = new Response(['error' => 'Unsupported request'], 400);
        }

        $this->respond($response);
    }

}
