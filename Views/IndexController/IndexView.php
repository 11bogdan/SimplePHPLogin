<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of index
 *
 * @author Богдан
 */
require(ROOT_URL."/Views/View.php");
class IndexView extends View {
    public function html_view($data) {
        $data = array();
        $data["name"] = "Personal page";
        $this->insert_header($data); ?>
         <div id="user_panel">
            <?php foreach (get_object_vars($_SESSION["user"]) as $key => $val) {
                if ($val != "User") echo "$key: $val<br>";
            } ?>
            <a href="<?= SITE_URL."login/signout" ?>">Выход</a>
        </div>
        <?php $this->insert_footer($data);
    }
}
