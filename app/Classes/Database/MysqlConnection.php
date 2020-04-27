<?php
namespace Classes\Database;


class MysqlConnection implements DatabaseConnection
{
    private $db_host = "localhost";
    private $db_user = "root";
    private $db_password = "";
    private $db_name = "myblog";
    private $pdo;

    public function connect()
    {
        if (!isset($this->pdo)) {
            try {
                $connetion = new \PDO("mysql:host=".$this->db_host."; dbname=".$this->db_name, $this->db_user, $this->db_password);
                $connetion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $connetion->exec("SET CHARACTER SET utf8");

                $this->pdo = $connetion;
                return $this->pdo;
            } catch(\PDOException $e) {
                die("Failed to connect with database". $e->getMessage());
            }
        }
    }

}

