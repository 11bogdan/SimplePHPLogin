<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of View
 *
 * @author Богдан
 */
abstract class View {
    //put your code here
    public function html_view($data) {

    }
    
    protected function insert_header($data) {?>
    <html>
        <head>
            <meta charset="UTF-8">
            <title><?= $data["title"]?></title>
            
            <?php 
            foreach($data["scripts"] as $script) {
                echo '<script type="text/javascript">';
                echo file_get_contents(ROOT_URL."/Scripts/$script.js");
                echo "</script>";
            }
            
            foreach($data["styles"] as $style) { ?>
            <style>
                <?=file_get_contents(ROOT_URL."/Styles/$style.css");?>
            </style>
            <?php } ?>
            
        </head>
        <body>
            <h1><?= $data["name"]?></h1>
           
    <?php
    }
    
    protected function insert_footer($data) {
        ?>
        </body>
    </html>
    <?php
    }
}
