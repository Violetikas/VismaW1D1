<?php


namespace Fikusas\DB;


use Fikusas\Config\ConfigInterface;
use Fikusas\Log\Logger;
use PDO;


class DatabaseConnector implements DatabaseConnectorInterface
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
        $queries = @file_get_contents(__DIR__.'/../../database.sql');
            if ($queries === false) {
                $logger = new Logger();
                $logger->critical("Cannot execute SQL query. Rollback changes.");
            }
            $this->pdo->exec($queries);
        }
}
