<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MainController
 *
 * @author Богдан
 */
require("Controller.php");

class IndexController extends Controller {
    //put your code here
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index($data) {
        session_start();
        $res = $this->db_manager->is_authorized();
        if (DEBUG) {
            echo "returned: $res";
        }
        
        if ($res === FALSE) {
            if (DEBUG) {
                echo "Doing redirect";
            }
            header("Location: ".SITE_URL."login/signup");
        }
        $this->view($data);
    }
}
