<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of Sign-outView
 *
 * @author Богдан
 */
require(ROOT_URL."/Views/View.php");
class SignoutView extends View {

    public function html_view($data) {
        $view_data = array();
        $view_data["name"] = "You succesfully logged out";
        $this->insert_header($view_data); ?>
        
        <a href ='<?=SITE_URL."login/register"?>'>Create account</a>
        <a href ='<?=SITE_URL."login/signup"?>'>Enter account</a>
        
        <?php
        $this->insert_footer($data);
    }
}
