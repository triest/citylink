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


    /**
     * CarrieController constructor.
     */
    public function __construct()
    {
        $rez = $this->readConnectProperty();
        if (!$rez == true) {
            // return $rez->getMessage();
            exit();
        }

    } //переменныя для считывание ответа от API

    //считываеие данных для подключения из файла connect.txt
    public function readConnectProperty()
    {
        try {
            if (file_exists("connect.txt")
                && $file = fopen("connect.txt", "r")
            ) {
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
            } else {
                $this->writeLog("file not fund");
            }
        } catch (PDOException $e) {
            $this->writeLog($e);

            return false;
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

    private function writeLog($error)
    {
        $date = new \DateTime('now');
        file_put_contents('errors.txt', implode(
            "\n".$date->format('D M d, Y G:i')." "."error:".$error."\n",
            FILE_APPEND));
    }

    //read carriers from dn

    /**
     * @return bool
     */
    public function readCarrier()
    {
        if (!$this->connectorValidate()) {
            $this->writeLog($this->connectorValidate());

            return false;
        }

        $mysqli = new mysqli($this->host, $this->login, $this->password,
            $this->database);
        if ($mysqli->connect_errno) {
            $this->writeLog($mysqli->connect_error);

            return false;
        }
        $sql = "SELECT * FROM carrier";
        $result = $mysqli->query($sql);
        if ($result) {
            if ($result->num_rows > 0) {
                // output data of each row
                try {
                    while ($row = $result->fetch_assoc()) {
                        $carrier = new Сarrier();
                        $carrier->setName($row["name"]);
                        $carrier->setRateType($row["rate_type"]);
                        $carrier->setPriceMinWeight($row["price_min_weight"]);
                        $carrier->setPriceMaxWeight($row["price_max_weight"]);
                        $carrier->setMinWeight($row["min_weight"]);
                        array_push($this->carrier_array, $carrier);
                    }
                } catch (PDOException $e) {
                    $this->writeLog($e);
                }
            } else {
                return false;
            }
            $mysqli->close();
        } else {
            $this->writeLog($mysqli->error);
        }

        return true;
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

    //ищиv конкретного перевозчика в базе дынныхю;
    public function findCarrieByNameInDatabase($name)
    {
        if (!$this->connectorValidate()) {
            $this->writeLog($this->connectorValidate());

            return false;
        }

        $mysqli = new mysqli($this->host, $this->login, $this->password,
            $this->database);
        if ($mysqli->connect_errno) {
            $this->writeLog($mysqli->connect_error);

            return false;
        }

        $stmt = $mysqli->prepare("SELECT * FROM carrier where name=? limit 1");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($result->num_rows > 0) {
                // output data of each row
                try {
                    while ($row = $result->fetch_assoc()) {
                        $carrier = new Сarrier();
                        $carrier->setName($row["name"]);
                        $carrier->setRateType($row["rate_type"]);
                        $carrier->setPriceMinWeight($row["price_min_weight"]);
                        $carrier->setPriceMaxWeight($row["price_max_weight"]);
                        $carrier->setMinWeight($row["min_weight"]);

                        //   array_push($this->carrier_array, $carrier);
                        return $carrier;
                    }
                } catch (PDOException $e) {
                    $this->writeLog($e);

                    return null;
                }

            }
        }

        return null;
    }

    public function calc($carrier, $weight)
    {
        //   $rez = $this->getCarrierByName($carrier);
        $rez = $this->findCarrieByNameInDatabase($carrier);
        if ($rez == null) {
            return 404;
        }

        return $rez->calculateCost($weight);
    }

    /**
     * @param mixed $host
     */
    public function setHost($host)
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