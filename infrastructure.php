<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

const DEBUG = FALSE;
const ROOT_DIR = "SimplePHPLogin";
const ROOT_URL = __DIR__;
const SITE_URL = "http://localhost/SimplePHPLogin/";
error_reporting(DEBUG == TRUE ? -1 : 0);


function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}