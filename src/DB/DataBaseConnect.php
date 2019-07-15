<?php


namespace Fikusas\DB;

use PDO;
use PDOException;

class DataBaseConnect

{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

//connect to DB using DBConfig

    public function createTable(){
        $this->pdo->query("CREATE TABLE `Visma1`.`Words_from_file` ( `id` INT(6) NOT NULL AUTO_INCREMENT ,
        `words` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`(6))) ENGINE = InnoDB;");
    }


    //    function insertData(){
////        $stmt=$this->pdo->prepare("INSERT INTO Visma1 VALUES (NULL, ?)");
//
//    }

    public function getData()
    {
        $query = $this->pdo->prepare('SELECT * FROM Visma1');
        $query->execute();
        return $query->fetchAll();
    }


}
















//    private $servername = "localhost";
//    private $username = "root";
//    private $password = "violetatama";
//    private $conn;
//
//    /**
//     * DataBaseConnect constructor.
//     * @param string $servername
//     * @param string $username
//     * @param string $password
//     */
//    public function __construct(string $servername, string $username, string $password, PDO $conn)
//    {
//        $this->servername = $servername;
//        $this->username = $username;
//        $this->password = $password;
//        $this->conn = $conn;
//    }
//
//
//    public function connectToDB()
//    {
//
//        try {
//            $conn = new PDO("mysql:host=$this->servername;dbname=Visma1", $this->username, $this->password);
//            // set the PDO error mode to exception
//            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//            echo "Connected successfully";
//        } catch
//        (PDOException $e) {
//            echo "Connection failed: " . $e->getMessage();
//        }
//    }
//
//    public function createDBTable()
//    {
//
//        $sql = "CREATE TABLE Words_from_file (
//          id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//          word VARCHAR(30) NOT NULL,
//          reg_date TIMESTAMP)";
//
//        // use exec() because no results are returned
//
//        $this->conn->exec($sql);
//        echo "Table Words_from_file created successfully";
//    }
//
//
//
//}