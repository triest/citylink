<?php
/**
 * Created by PhpStorm.
 * User: triest
 * Date: 26.07.2019
 * Time: 23:58
 */

$host;
$password;
$login;
$database;

$carrier_array = array();
$filename = "connect.txt";

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
