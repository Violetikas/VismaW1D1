<?php


namespace Fikusas\DB;

use Fikusas\Cache\FileCache;
use PDOException;
use Fikusas\Hyphenation\WordHyphenator;
use Psr\SimpleCache\CacheInterface;
use PDO;

class WordDB
{
    private $dbConfig;


    public function __construct(DatabaseConnector $dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }

    public function isWordSavedToDB(string $word): bool
    {
        $pdo = $this->dbConfig->getConnection();
        $query = $pdo->prepare("SELECT `word_id` FROM `Words` WHERE `word` =:word;");
        if (!$query->execute(array('word' => $word))) {
            return false;
        }
        return $query->rowCount() == 1;
    }

    public function getHyphenatedWordFromDB(string $word): string
    {
        $pdo = $this->dbConfig->getConnection();
        $query = $pdo->prepare("SELECT hyphenatedWord FROM Words WHERE word=:word;");
        if ($query->execute(array('word' => $word))) {
            return $query->fetch(PDO::FETCH_ASSOC)['hyphenatedWord'];
        }

    }

    public function writeWordToDB(string $word, string $hyphenatedWord): void
    {

        $pdo = $this->dbConfig->getConnection();
        $stmt = $pdo->prepare("REPLACE INTO Words(word, hyphenatedWord) VALUES (?,?)");
        try {
            $pdo->beginTransaction();
            if (!$this->isWordSavedToDB($word)) {
                $stmt->execute([$word, $hyphenatedWord]);
                $pdo->commit();
            }
        } catch (PDOException $exception) {
            $pdo->rollback();
            throw $exception;
        }
    }


    public function writeWordsToDB(array $words, array $hyphenatedWords): void
    {

        $pdo = $this->dbConfig->getConnection();
        $stmt = $pdo->prepare("REPLACE INTO Words(word, hyphenatedWord) VALUES (?,?)");
        try {
            $pdo->beginTransaction();

            for ($i = 0; $i < count($words); $i++) {
                if (!$this->isWordSavedToDB($words[$i])) {
                    $stmt->execute([$words[$i], $hyphenatedWords[$i]]);
                    $pdo->commit();
                }
            }
        } catch (PDOException $exception) {
            $pdo->rollback();
            throw $exception;
        }
    }

    public function storeWordSyllables(string $word, array $syllables): void
    {
        $pdo = $this->dbConfig->getConnection();
        $stmt = $pdo->prepare('INSERT INTO WordsAndPatternsID (word_id, pattern_id)
        VALUES ((SELECT word_id FROM Words WHERE word = ?), (SELECT pattern_id FROM Patterns WHERE pattern = ?))');

        try {
            $pdo->beginTransaction();
            foreach ($syllables as $pattern) {
                $stmt->execute([$word, $pattern]);
            }
            $pdo->commit();
        } catch (PDOException $exception) {
            $pdo->rollback();
            throw $exception;
        }
    }

    public function getWordAndPatterns($word){

        $pdo = $this->dbConfig->getConnection();
        $query = $pdo->prepare("SELECT * FROM Words LEFT JOIN WordsAndPatternsID ON Words.word_id = WordsAndPatternsID.word_id WHERE Words.word = ?;");
        $query->execute([$word]);
    }
}