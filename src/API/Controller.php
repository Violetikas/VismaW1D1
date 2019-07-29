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

    public function __construct(WordHyphenatorInterface $hyphenator, PatternLoaderInterface $loader, DatabaseConnectorInterface $dbConfig, WordDB $wordDB, PatternDB $patternDB, HyphenatedWordsDB $hdb)
    {
        $this->patterns = $loader->loadPatterns();
        $this->dbConfig = $dbConfig;
        $this->wordDB = $wordDB;
        $this->patternDB = $patternDB;
        $this->hdb = $hdb;
        $this->hyphenator = $hyphenator;
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

    public function insertWord(Request $request): Response
    {
        $input = json_decode($request->getContent());
        $word = $input->word;
        $this->wordDB->writeToDB($word);
        return new Response(['message' => "Word written to DB", "word" => $word], 201);
    }

    public function deleteWord(string $word): Response
    {
        $this->wordDB->deleteFromDB($word);
        return new Response([], 200);
    }

    public function updateWord(string $word): Response
    {
        $hyphenated = $this->hyphenator->hyphenate($word);

        return new Response(['message' => "Word updated", "word" => $word, "hyphenated" => $hyphenated], 200);
    }
}


