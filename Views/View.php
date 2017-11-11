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
        echo "<br>Hey! I'am a view<br>";
        if (DEBUG) {
            echo "<pre>";
            var_dump(debug_backtrace());
            echo "</pre>";
        }
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
            ?>
            
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
