<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DbProvider
 *
 * @author Богдан
 */

class DbProvider {
    //put your code here
    private $conn;
    
    public function DbProvider($servername, $username, $password) {
        $this->conn = new mysqli($servername, $username, $password);
        
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    
    public function connect_error() {
        return $this->conn->connect_error;
    }
    
    public function query($query) {
        return $this->conn->query($query);
    }
    
    public function escape_string($string) {
        return $this->conn->real_escape_string($string);
    }
    
    public static function fetch_assoc($res) {
        return mysqli_fetch_assoc($res);
    }
}
