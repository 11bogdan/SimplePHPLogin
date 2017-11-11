<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

const DEBUG = TRUE;
const ROOT_DIR = "SimplePHPLogin";
const ROOT_URL = __DIR__;
error_reporting(-1);

function ensure_auth($current_url) {
    if(session_status() == PHP_SESSION_NONE) {

        //do redirect to login controller
        if (DEBUG) {
            echo "Routing to login...";
        }

        header("Location: ".ROOT_URL."/login/signup?return=$current_url");
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}