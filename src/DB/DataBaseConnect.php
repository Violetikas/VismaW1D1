<?php


namespace DB;
use PDO;
use PDOException;

class DataBaseConnect
{
private $servername = "localhost";
private $username = "username";
private $password = "password";








//
//    private $dbConfig;
//    private $logger;
//    public function __construct(DbConfig $dbConfig, LoggerInterface $logger)
//    {
//        $this->dbConfig = $dbConfig;
//        $this->logger = $logger;
//    }
//    public function importFromFile(string $fileName, CacheInterface $Cache): bool
//    {
//        $patternsArray = PatternDataLoader::loadDataFromFile($fileName, $Cache, $this->logger);
//        $pdo = $this->dbConfig->getPdo();
//        $pdo->beginTransaction();
//        $query = $pdo->prepare('REPLACE INTO `hyphenation_patterns`(`pattern`, `pattern_chars`)
//VALUES(:pattern, :pattern_chars);');
//        $current = 1;
//        foreach ($patternsArray as $pattern) {
//            $patternObj = new Pattern($pattern);
//            $patternCharArray = $patternObj->getPatternCharArray();
//            $serializedPatternCharArray = serialize($patternCharArray);
//            $this->logger->info('Importing pattern {current} / {total} to database',
//                array(
//                    'current' => $current,
//                    'total' => count($patternsArray)
//                ));
//            if (!$query->execute(array(
//                'pattern' => $pattern,
//                'pattern_chars' => $serializedPatternCharArray
//            ))) {
//                $pdo->rollBack();
//                return false;
//            }
//            $current++;
//        }
//        $pdo->commit();
//        return true;
//    }
//    public function getPatternsArray(): array
//    {
//        $patternsArray = array();
//        $pdo = $this->dbConfig->getPdo();
//        $result = $pdo->query('SELECT `pattern` FROM `hyphenation_patterns`;');
//        if ($result) {
//            $patternsArray = $result->fetchAll(PDO::FETCH_COLUMN, 0);
//            $this->logger->notice('Loaded patterns from database.');
//        } else $this->logger->critical('Cannot get patterns from database!');
//        return $patternsArray;
//    }

}