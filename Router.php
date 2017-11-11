<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require "infrastructure.php";

$path = $_GET["path"];
$router = new Router();
$router->route($path);

class Router {
    public function route($url){
        
        if ($url == ""){
            $url = "index/index";
        }
        
        if (DEBUG) {
            echo "url to route: $url.";
        }

        $split_url = explode('/', $url);
        
        if (DEBUG) {
            var_dump($split_url);
        }
        
        $contr_type = ucfirst($split_url[0])."Controller";
        $act = $split_url[1];
        if ($act == ""){
            $act = "index";
        }
        
        $query_parsed = parse_url($split_url[2], PHP_URL_QUERY);
        
        require_once "Controllers/".$contr_type.".php";
        $contr = new $contr_type;
        
        if (DEBUG) {
            echo "<br><br>Invoking $act on $contr_type<br><br>";
        }
        $contr->$act($query_parsed);
    }
}