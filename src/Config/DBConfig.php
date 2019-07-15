<?php


namespace Config;


use PDO;
use PDOException;

class DBConfig
{

    private $host = 'localhost';
    private $db_name = 'Visma1';
    private $db_username = 'root';
    private $db_password = 'violetatama';
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function connectToDB()
    {
        try {
            $pdo = new PDO($this->host, $this->db_name, $this->db_username, $this->db_password);
        }
        catch (PDOException $exception){
            exit('Error Connecting To DataBase');
        }
    }

}