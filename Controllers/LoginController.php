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
        $view_data["incorrect"] = $_GET["incorrect"] == TRUE? "Login or password is incorrect": "";
        $view_data["return"] = $data["return"];
        $this->view($view_data);
    }
    
    public function signout() {
        if (DEBUG) {
            echo "sid: ".session_id();
        }
        $this->db_manager->sign_out();
        $this->view();
    }
    
    public function signup_post() {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            $val_data["login_or_email"] = $_POST["login_or_email"];
            $val_data["password"] = $_POST["password"];

            $user = $this->signup_server_side_validation($val_data);
            if ($user) {
                $res = $this->db_manager->sign_in($user);
            }
            
            if ($res !== TRUE) {
                if (DEBUG) {
                    echo "incorrect";
                }
                if (!DEBUG) header("Location: signup?incorrect=true");
            } else {
                if (DEBUG) {
                    echo "signup sid id:".session_id().". again: ". session_id();
                    echo "Going to redirect...";
                }
                //header('HTTP/1.0 302 Found');
                if (!DEBUG) header("Location: ".SITE_URL."index/index");
            }
        }
    }
    
    private function signup_server_side_validation($data) {
        $login_or_email = $data["login_or_email"];
        $is_email = filter_var($login_or_email, FILTER_VALIDATE_EMAIL) != FALSE;
        
        if (DEBUG) {
            echo "isemail: $is_email";
        }
        if ($is_email) {
            $res = $this->db_manager->get_user_by_email_p(
                    $login_or_email, $data["password"]);
        } else {
            $res = $this->db_manager->get_user_by_login_p(
                    $login_or_email, $data["password"]);
        }
        
        if (DEBUG) {
            echo "res: ". json_encode($res);
        }
        return $res;
    }
    
    
    public function register($data) {
        $view_data["return"] = $data["return"];
        $view_data["countries"] = $this->db_manager->get_countries();
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
        
        if (DEBUG) {
            echo "DELIMITER11!";
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
            return;
            if (!DEBUG) header("Location: ".ROOT_URL."/login/register");
            
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
            $this->db_manager->create_user($user);
            $this->db_manager->sign_in($user);
            if (!DEBUG) header("Location: ".ROOT_URL."index/index");
        }
    }
    
    private function register_server_side_validation($data) {
        $errors = array();
        
        $user = $this->db_manager->get_user_by_email($data["email"]);
        
        if ($user !== NULL) {
            $errors["email"] = "Not unique";
        }
        
        if (strlen($data["email"]) < 6) {
            $errors["email"] = "Too short";
        }
        
        $user = $this->db_manager->get_user_by_login($data["login"]);

        if ($user !== NULL) {
            $errors["login"] = "Not unique";
        }
        
         if (strlen($data["login"]) < 6) {
            $errors["login"] = "Too short";
        }
        
        if ($this->db_manager->get_country_id($data["country"]) == FALSE) {
            $errors["country"] = "Doesn't exist";
        }
        
        return $errors;
    }
}
