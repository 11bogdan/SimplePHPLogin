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
    
    public function is_authorized() {
        session_start();
        $query = sprintf('SELECT * from `default`.`authors` WHERE `id` = "%s"', session_id());
        $res = DbManager::$db_connect->query($query);
        
        if (DEBUG) {
            echo "query is auth: $query<br>";
            echo "res nrows: $res->num_rows";
        }
        
        if ($res->num_rows > 0) {
            return TRUE;

        } else {
            return FALSE;
        }
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
        $query = "SELECT `name` FROM `default`.`countries`";
        $res = DbManager::$db_connect->query($query);
        
        if (DEBUG) {
            echo "res of country query:".json_encode($res);
        }
        
        $countries = array();
        
        while($row = DbProvider::fetch_assoc($res)){
            $countries[] = $row["name"];
        }
        
        return $countries;
    }
    
    public function get_country_id($country) {
        $query = sprintf('SELECT id FROM `default`.countries WHERE name = "%s"', 
                DbManager::$db_connect->escape_string($country));
        $res = DbManager::$db_connect->query($query);
        
        if ($res == FALSE) {
            return FALSE;
        }
        
        $country_id = DbProvider::fetch_assoc($res)["id"];
        return $country_id;
    }
    
    public function get_user_by_login($login) {
        
        if (DEBUG) {
            echo "login usual invoked";
        }
                
        $query = sprintf('SELECT * FROM `default`.users WHERE login = "%s"', 
                DbManager::$db_connect->escape_string($login));
        
        $res = DbManager::$db_connect->query($query);
        $user = DbProvider::fetch_assoc($res);
        
        $obj_user = new User;
        if ($user != NULL) {
            foreach ($user as $key => $prop) {
                $obj_user->$key = $prop;
            }
            
            return $obj_user;
        } else {
            return null;
        }
    }
    
    public function get_user_by_email($email) {
        
        $query = sprintf('SELECT * FROM `default`.users WHERE email = "%s"', 
        DbManager::$db_connect->escape_string($email));
        
        $res = DbManager::$db_connect->query($query);
        $user = DbProvider::fetch_assoc($res);
        
        $obj_user = new User;
        if ($user != NULL) {
            foreach ($user as $key => $prop) {
                $obj_user->$key = $prop;
            }
            
            return $obj_user;
        } else {
            return null;
        }
    }
    
    public function get_user_by_login_p($login, $password) {
        $user = $this->get_user_by_login($login);
        
        if ($user) {            
            if (password_verify($password, $user->password_hash) == FALSE) {
                return null;
            }
        }
        return $user;
    }

    public function get_user_by_email_p($email, $password) {
        $user = $this->get_user_by_email($email);

        if ($user) {            
            if (password_verify($password, $user->password_hash) == FALSE) {
                return null;
            }
        }
        return $user;
    }
    
    
    /*public function __call($method, $arguments) {
        if (DEBUG) {
            echo "nargs: ".count($arguments).".";
        }
        
        if($method == 'get_user_by_email') {
            if(count($arguments) == 1) {
               return call_user_func_array(array($this,'get_user_by_email'), $arguments);
            }
            else if(count($arguments) == 2) {
               return call_user_func_array(array($this,'get_user_by_email_p'), $arguments);
            }
        }

        if($method == 'get_user_by_login') {
           if(count($arguments) == 1) {
              return call_user_func_array(array($this,'get_user_by_login'), $arguments);
           }
           else if(count($arguments) == 2) {
              return call_user_func_array(array($this,'get_user_by_login_p'), $arguments);
           }
       }
   } */
   
    public function sign_in($user) {
        session_start();
        $_SESSION["user"] = $user;
        
        if (DEBUG) {
            echo "sign in reached";
        }
        
        $query = sprintf('DELETE FROM `default`.`authors` WHERE `user_login` = "%s"',
                $user->login);
       
        $res = DbManager::$db_connect->query($query);
        
        if (DEBUG) {
            echo "Done ($query)";
        }
        
        $query = sprintf('INSERT INTO `default`.`authors`
            (`id`,
            `user_login`)
            VALUES
            ("%s","%s");', 
            DbManager::$db_connect->escape_string(session_id()),
            DbManager::$db_connect->escape_string($user->login));
       
        $res = DbManager::$db_connect->query($query);
        
        if (DEBUG) {
            echo "Repeating query: $query<br>".json_encode($res);
        }
        
        if (!$res) {
            return FALSE;
        }
        
        $_SESSION["test"] = "data1";
        return TRUE;
    }
    
    public function sign_out() {
        session_start();
        $query = sprintf('DELETE FROM `default`.`authors`
            WHERE `id` = "%s";', 
            (session_id()));
        
        $res = DbManager::$db_connect->query($query);
        
        if (DEBUG) {
            echo "(test: ".$_SESSION["test"].")Signedout with $query";
        }
        
        session_destroy();
        return $res;
    }
    
    
    private static function create_connection() {
        $server = "localhost";
        $user = "root";
        $password = "123";
        
        $conn = new DbProvider($server, $user, $password);
       
        return $conn;
    }
}
