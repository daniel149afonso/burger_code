<?php
    #CONNECTION DATABASE
    class Database{
        #INIT VAR
        private static $dbHost= "localhost";
        private static $dbName= "burger_code";
        private static $dbUser= "root";
        private static $dbUserPassword= "";
        private static $connection = null;

        #Connection
        public static function connect(){
            try{
                $connection = new PDO('mysql:host='. self::$dbHost . ';dbname=' . self::$dbName, self::$dbUser, self::$dbUserPassword);
                
            }
            catch(PDOException $e){
                die($e->getMessage());#stop le code et affiche message d'erreur
            }
            return $connection;
        }
        

        #Disconnection
        public static function disconnect(){
            self::$connection = null; #annule la connexion
        }
        
    }
    Database::connect();
 
?>