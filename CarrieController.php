<?php
/**
 * Created by PhpStorm.
 * User: triest
 * Date: 24.07.2019
 * Time: 18:16
 */

class CarrieController
{

    private $host;
    private $password;
    private $login;
    private $database;

    public $carrier_array;

    public function __construct()
    {
        $this->readConnectProperty();
    } //переменныя для считывание ответа от API

    //считываеие данных для подключения из файла connect.txt
    private function readConnectProperty()
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
            }
        } catch (PDOException $e) {
            print "Error!: ".$e->getMessage();
            exit();
        }
    }

    //read carriers from dn
    public function readCarrier()
    {
        echo "databese";
        echo $this->database;

        $mysqli = new mysqli($this->host, $this->login, $this->password,
            $this->database);
        if ($mysqli->connect_errno) {
            echo "Извините, возникла проблема на сайте";
            // На реальном сайте этого делать не следует, но в качестве примера мы покажем
            // как распечатывать информацию о подробностях возникшей ошибки MySQL
            echo "Ошибка: Не удалась создать соединение с базой MySQL и вот почему: \n";
            echo "Номер ошибки: ".$mysqli->connect_errno."\n";
            echo "Ошибка: ".$mysqli->connect_error."\n";

            // Вы можете захотеть показать что-то еще, но мы просто выйдем
            exit;
        }
        $sql = "SELECT * FROM carrier";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "id: ".$row["id"]." - Name: ".$row["name"]." "
                    .$row["lastname"]."<br>";
            }
        } else {
            //   echo "0 results";
        }
        $mysqli->close();
    }


}