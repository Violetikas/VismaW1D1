<?php


namespace Fikusas\DB;


use Fikusas\Config\ConfigInterface;
use PDO;

class DatabaseConnector
{
    /** @var PDO */
    private $pdo;
    /** @var ConfigInterface */
    private $config;

    /**
     * DatabaseConnector constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @return PDO
     * @throws \Fikusas\Config\Error\ParameterNotFoundError
     */
    public function getConnection(): PDO
    {

        if (!$this->pdo) {
            $this->pdo = new PDO(
                sprintf(
                    'mysql:host=%s;dbname=%s',
                    $this->config->getParameter('db_host'),
                    $this->config->getParameter('db_name')
                ),
                $this->config->getParameter('db_username'),
                $this->config->getParameter('db_password')
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->initDB();
        }

        return $this->pdo;
    }

    private function initDB(): void
    {
        $this->pdo->query("CREATE TABLE IF NOT EXISTS `Words_from_file` ( `id` INT(6) NOT NULL AUTO_INCREMENT ,
        `words` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        $this->pdo->query("CREATE TABLE IF NOT EXISTS Patterns (`id` INT(6) NOT NULL AUTO_INCREMENT ,
         `patterns` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        $this->pdo->query("CREATE TABLE IF NOT EXISTS 'Hyphenated Words' ('id' INT(6) NOT NULL AUTO_INCREMENT ,
        'hyphenated_words' VARCHAR(255)NOT NULL, PRIMARY KEY ('id') ) ENGINE = InnoDB;");

        // TODO: create all tables
    }
}
