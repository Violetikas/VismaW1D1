<?php


namespace Fikusas\API;

use Fikusas\DB\DatabaseConnector;
use PDO;


class ApiRequest
{
    /**
     * @var DatabaseConnector
     */
    private $db;
    /**
     * ApiRequest constructor.
     * @param DatabaseConnector $db
     */
    public function __construct( DatabaseConnector $db)
    {
        $this->db = $db;
    }

    public function getWordList(): void
    {
        $pdo = $this->db->getConnection();
        $words = array();
        $data = $pdo->prepare('select * from Words order by word_id');
        $data->execute();
        while ($outputData = $data->fetch(PDO::FETCH_ASSOC)) {

            $words[$outputData['word_id']] = array(
                'word_id'        => $outputData['word_id'],
                'word'           => $outputData['word'],
                'hyphenatedWord' => $outputData['hyphenatedWord']);
        }
        return json_encode($words);
    }

}