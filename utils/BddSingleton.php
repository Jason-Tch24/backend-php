<?php

    class BddSingleton {
        private static $_INSTANCE = null;
        private $pdo;

        private function __construct(){
            $DSN = 'mysql:host=localhost;port=3306;dbname=autoroute';
            try{
                $this->pdo = new PDO($DSN,'root', '');
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch (PDOExeception $e){
                die("Database connection Error : " . $e->getMessage());
            }
        }

        public function getPdo(){
            return $this->pdo;
        }

        public static function getInstance() {
            if(is_null(self::$_INSTANCE)){
                self::$_INSTANCE = new BddSingleton();
            }
            return self::$_INSTANCE;
        }
        function __destruct(){
            unset($this->pdo);
        }
    }

?>