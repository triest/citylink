<?php
/**
 * Created by PhpStorm.
 * User: triest
 * Date: 24.07.2019
 * Time: 18:16
 */
require_once "Сarrier.php";

class CarrieController
{

    private $host;
    private $password;
    private $login;
    private $database;

    public $carrier_array = array();


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
                $carrier = new Сarrier();
                $carrier->setName($row["name"]);
                $carrier->setRateType($row["rate_type"]);
                $carrier->setPriceMinWeight($row["price_min_weight"]);
                $carrier->setPriceMaxWeight($row["price_max_weight"]);
                $carrier->setMinWeight($row["min_weight"]);
                array_push($this->carrier_array, $carrier);
            }
        } else {
            return false;
        }
        $mysqli->close();

        return true;
    }

    public function printCarrierArray()
    {
        print_r($this->carrier_array);
    }

    public function getCarrierByName($name)
    {
        foreach ($this->carrier_array as $struct) {
            if ($name == $struct->name) {
                return $struct;
            }
        }
    }

    public function calc($carrier, $weight)
    {
        $rez = $this->getCarrierByName($carrier);
        if ($rez == null) {
            return null;
        }

        return $rez->calculateCost($weight);
    }
}