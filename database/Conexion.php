<?php

class Conexion {

    private $con;
    
    function __construct() {
        try {
            $this->con = new PDO("mysql:host=localhost;dbname=greenghost", "root", "");
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    function getCon() {
        return $this->con;
    }
}
