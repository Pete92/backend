<?php 
    class Database {
        private $host = "eu-cdbr-west-01.cleardb.com:3306";
        private $database_name = "heroku_a81f96d2d97a626";
        private $username = "bcf33cad39131d";
        private $password = "078d8662";

        public $conn;

        public function getConnection(){
            $this->conn = null;
            try{
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
                $this->conn->exec("set names utf8");
            }catch(PDOException $exception){
                echo "Database could not be connected: " . $exception->getMessage();
            }
            return $this->conn;
        }
    }  
?>