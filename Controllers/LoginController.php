<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoginController
 *
 * @author Богдан
 */

require("Controller.php");
require(ROOT_URL."/Models/User.php");

class LoginController extends Controller{
    //put your code here
    
    public function __construct() {
        parent::__construct();
    }
    
    public function signup($data) {
        $view_data["return"] = $data["return"];
        $this->view($view_data);
    }
    
    public function signup_validate($data) {
        //ensure uniq email or login
        
        $val_data["login_or_email"] = $_POST["login_or_email"];
        $val_data["password"] = $_POST["password"];
        
        $res = $this->signup_server_side_validation($val_data);
        
        if (count($res) > 0) {
            http_response_code(500);
        }
        echo json_encode($res);
    }
    
    public function signup_post() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $succ = FALSE;
            
            
            
            if ($succ) {
                header("Location: $return_url");
            }
        }
    }
    
    public function register($data) {
        $view_data["return"] = $data["return"];
        $this->view($view_data);
    }
    
    public function register_validate($data) {
        //ensure uniq email and login
        //unsure country exists
        $val_data = array();
        $val_data["country"] = $_POST["country"];
        $val_data["login"] = $_POST["login"];
        $val_data["email"] = $_POST["email"];
        $res = $this->register_server_side_validation($val_data);
        
        if (count($res) > 0) {
            http_response_code(500);
        }
        
        echo json_encode($res);
    }

    public function register_post() {
        
        $regexes = array(
            "birth_date" => "/.*/",
            "login" => "/^[0-9a-zA-Z]{6,12}$/",
            "real_name" => "/^[0-9a-zA-Z]{2,20}$/",
            "password" => "/^[A-Z][0-9a-zA-Z]{6,12}$/",
            "email" => '/^(([^<>()\[\]\\.,; => \s@"]+(\.[^<>()\[\]\\.,; => \s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/'
        );
        
        $errors = array();

        $isvalid = true;

        foreach ($regexes as $field => $regex) {
            $val = $_POST[$field];
            $is_ok = preg_match($regex, $val);
            
            if (DEBUG) {
                var_dump($is_ok);
            }
            
            if ($field === "email") {
                $is_ok = filter_var($val, FILTER_VALIDATE_EMAIL) != FALSE;
            }

            if ($is_ok == 0) {
                $errors[$field] = "$field seems incorrect";
                $isvalid = 0;
            }
        }
        
        $val_data["country"] = $_POST["country"];
        $val_data["login"] = $_POST["login"];
        $val_data["email"] = $_POST["email"];
        
        if (count($this->register_server_side_validation($val_data)) > 0){
            $isvalid = FALSE;
        }
        
        if (!$isvalid) {
            
            
            //can got here only if client validation has been removed
            if (DEBUG) {
                echo "<pre>REJECTED ";
                var_dump($_POST);
                echo "***</pre>";
            }
            return;
            //header("Location: ".ROOT_URL."/login/register");
            
        } else {
            
            $fields = array(
                "birth_date" ,
                "login" ,
                "real_name" ,
                "email");
            
            $user = new User;
            $user->password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
            
            foreach ($fields as $field) {
                $user->$field = $_POST["$field"];
            }
            
            $user->country_id = $this->db_manager->get_country_id($_POST["country"]);
            
            if (DEBUG) {
                echo "<pre>Going to create: ";
                var_dump(get_object_vars($user));
                //var_dump($_POST);
                echo "***</pre>";
            }
            
            $this->db_manager->create_user($user);
        }
    }
    
    private function signup_server_side_validation($data) {
        $login_or_email = $data["login_or_email"];
        $is_email = filter_var($is_email, FILTER_VALIDATE_EMAIL) != FALSE;
        
        if ($is_email) {
            $res = $this->db_manager->get_user_by_email(
                    $login_or_email, $data["password"]);
        } else {
            $res = $this->db_manager->get_user_by_login(
                    $login_or_email, $data["password"]);
        }
        
        $res_data = array();
        
        if ($res == 0) {
            $res_data["wrong_auth"] = "Login(email) or password incorrect";
        }
        
        return $res_data;
    }
    
    private function register_server_side_validation($data) {
        $errors = array();
        
        if ($this->db_manager->get_user_by_email($data["email"]) !== FALSE) {
            $errors["email"] = "Not unique";
        }
        
        if ($this->db_manager->get_user_by_login($data["login"]) !== FALSE) {
            $errors["login"] = "Not unique";
        }
        
        if ($this->db_manager->get_country_id($data["country"]) == FALSE) {
            $errors["country"] = "Doesn't exist";
        }
        
        return $errors;
    }
}
