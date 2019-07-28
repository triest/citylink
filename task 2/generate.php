<?php
/**
 * Created by PhpStorm.
 * User: triest
 * Date: 26.07.2019
 * Time: 23:58
 */
/*
class CreateDatabase
{


    public function getHost(): string
    {
        return $this->host;
    }


    public function getPassword()
    {
        return $this->password;
    }


    public function getLogin()
    {
        return $this->login;
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public $password;
    public $login;
    public $database;
    public $carrier_array = array();
    public $filename = "connect.txt";
    public $host = "";

    function readConnectProperty()
    {
        try {
            if ($file = fopen("connect.txt", "r")) {
                while ($line = fgets($file)) {
                    $arr = explode("=", $line);
                    if ($arr[0] == "host") {
                        $arr[1] = str_replace('/\s+/', '', $arr[1]);
                        $this->host = trim($arr[1]);
                    }
                    if ($arr[0] == "login") {
                        $arr[1] = preg_replace('/\s+/', '', $arr[1]);
                        $this->login = trim($arr[1]);
                        //  echo $this->login;
                    }
                    if ($arr[0] == "password") {
                        $arr[1] = str_replace('/\s+/', '', $arr[1]);
                        $this->password = trim($arr[1]);
                    }
                    if ($arr[0] == "database") {
                        $arr[1] = str_replace('/\s+/', '', $arr[1]);
                        $this->database = $arr[1];
                    }
                }
                fclose($file);

                return true;
            }
        } catch (PDOException $e) {
            return $e;
        }
    }
}

$database = new CreateDatabase();

$database->readConnectProperty();
$dbh = new PDO("mysql:host=".$database->getHost()
    .";dbname="
    .$database->getDatabase().",".
    "root".",".
    $database->getPassword());
$query = file_get_contents("publishing.sql");
$stmt = $dbh->prepare($query);
$stmt->execute();
*/


$db = new PDO("mysql:host=127.0.0.1;dbname=publishing2", "root", "");
$sql = file_get_contents('publishing.sql');

$qr = $db->exec($sql);

