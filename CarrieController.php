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
    private $filename = "connect.txt";

    /**
     * @param string $filename
     */
    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }


    public function __construct()
    {
        $rez = $this->readConnectProperty();
        if (!$rez) {
            return $rez->getMessage();
        }

        return null;
    } //переменныя для считывание ответа от API

    //считываеие данных для подключения из файла connect.txt
    public function readConnectProperty()
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

                return true;
            }
        } catch (PDOException $e) {
            return $e;
        }


    }


    public function connectorValidate()
    {
        if ($this->host == null) {
            return "empty host";
        }
        if ($this->login == null) {
            return "empty login";
        }
        if ($this->database == null) {
            return "empty database";
        }

        return true;
    }

    //read carriers from dn
    public function readCarrier()
    {
        $mysqli = new mysqli($this->host, $this->login, $this->password,
            $this->database);
        if ($mysqli->connect_errno) {
            return $mysqli->connect_error;
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

        return null;
    }

    public function calc($carrier, $weight)
    {
        $rez = $this->getCarrierByName($carrier);
        if ($rez == null) {
            return "carrier not found";
        }

        return $rez->calculateCost($weight);
    }

    /**
     * @param mixed $host
     */
    public function setHost($host): void
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login): void
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param mixed $database
     */
    public function setDatabase($database): void
    {
        $this->database = $database;
    }
}