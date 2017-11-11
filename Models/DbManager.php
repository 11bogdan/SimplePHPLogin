<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DbManager
 *
 * @author Богдан
 */
require("DbProvider.php");
class DbManager {
    
    private static $db_connect;
    
    public static function get_instance() {
        if (!isset(DbManager::$db_connect)) {
            DbManager::$db_connect = DbManager::create_connection();
        }
        
        return new DbManager;
    }
    
    public function create_user($user) {
        
        try {
            $query = sprintf('INSERT INTO `default`.`users`(`login`, `password_hash`, `birth_date`, `timestamp`, `country_id`, `email`, `real_name`) 
                VALUES ("%s", "%s", "%s", current_timestamp(), "%s", "%s", "%s");
                ', 
                DbManager::$db_connect->escape_string($user->login), 
                DbManager::$db_connect->escape_string($user->password_hash), 
                DbManager::$db_connect->escape_string($user->birth_date), 
                DbManager::$db_connect->escape_string($user->country_id), 
                DbManager::$db_connect->escape_string($user->email), 
                DbManager::$db_connect->escape_string($user->real_name));
            
            $res = DbManager::$db_connect->query($query);
            
            if ($res == FALSE) {
                if (DEBUG) {
                    echo $query;
                    echo DbManager::$db_connect->connect_error();
                }
            }
        } catch (Exception $ex) {
            if (DEBUG) {
                echo "<pre>";
                var_dump($ex);
                echo "</pre>";
            }
        }
    }
    
    public function get_countries() {
        
    }
    
    public function get_country_id($country) {
        $query = sprintf('SELECT id FROM `default`.countries WHERE name = "%s"', 
                DbManager::$db_connect->escape_string($country));
        $res = DbManager::$db_connect->query($query);
        
        if ($res == FALSE) {
            return FALSE;
        }
        
        $country_id = DbProvider::fetch_assoc($res)["id"];
        
        if (DEBUG) {
            echo "<pre>country id res:";
            echo $query."<br>";
            var_dump($country_id);
            echo "Err:".DbManager::$db_connect->connect_error();
            echo "</pre>";
        }
        return $country_id;
    }
    
    public function get_user_by_login($login) {
        
    }
    
    public function get_user_by_email($email) {
        
    }
    
    private static function create_connection() {
        $server = "localhost";
        $user = "root";
        $password = "";
        
        $conn = new DbProvider($server, $user, $password);
        
        if (DEBUG){
            echo "<pre>";
            var_dump($conn);
            echo "</pre>";
        }
        return $conn;
    }
}
