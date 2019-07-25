<?php


namespace Fikusas\DB;


use PDO;
use PDOException;

class HyphenatedWordsDB
{
    private $dbConfig;

    public function __construct(DatabaseConnectorInterface $dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }

    public function getFromDB($word)
    {

        $pdo = $this->dbConfig->getConnection();

        $selector = $pdo->prepare('SELECT HyphenatedWords.hyphenatedWord
        FROM HyphenatedWords INNER JOIN Words ON HyphenatedWords.word_id = Words.word_id WHERE word=:word;');

        if ($selector->execute(array('word' => $word))) {
            return $selector->fetch(PDO::FETCH_ASSOC)['hyphenatedWord'];
        }

    }

    public function writeToDB($word, $hyphenatedWord)
    {

        $pdo = $this->dbConfig->getConnection();
        $writer = $pdo->prepare('REPLACE INTO HyphenatedWords (word_id, hyphenatedWord)
        VALUES ((SELECT word_id FROM Words WHERE word = ?), ?)');
        try {
            $pdo->beginTransaction();
            $writer->execute([$word, $hyphenatedWord]);
            $pdo->commit();

        } catch (PDOException $exception) {
            $pdo->rollback();
            throw $exception;
        }
    }

}