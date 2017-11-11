<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 *
 * @author Богдан
 */
require (ROOT_URL."/Models/DbManager.php");
abstract class Controller {
    
    protected $db_manager;
    
    public function __construct() {
        $this->db_manager = DbManager::get_instance();
    }
    
    protected function view($data) {
        $act_name = debug_backtrace()[1]["function"];
        $view_name = ucfirst($act_name)."View";
        
        require("Views/". get_class($this)."/$view_name.php");
        
        if (DEBUG) {
            echo "The view of type $view_name created";
        }
        $view = new $view_name();
        $view->html_view($data);
    }
    
    protected function view2($data, $view_name) {
        
        require("Views/". get_class($this)."/$view_name.php");
        
        if (DEBUG) {
            echo "The view of type $view_name created";
        }
        
        $view = new $view_name();
        $view->html_view($data);
    }
}