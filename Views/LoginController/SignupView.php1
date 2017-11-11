<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SignupView
 *
 * @author Богдан
 */
require(ROOT_URL."/Views/View.php");
class SignupView extends View {
    //put your code here
    
    public function html_view($data) {
        $data["title"] = "Signing Up";
        $data["name"] = "Sign Up page";
        $this->insert_header($data);?>

        <form action="signup_post" method="post">
            <div>
                <label for="login_or_email">Login or email</label>
                <input type="text" name="login_or_email">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password">
                <input type="hidden" name="return" value="<?=$data["return"]?>">
            </div>
            <input type="submit">
        </form> 
            
        <a href="register?return=<?= urlencode($data["return"])?>">Create account</a>     
        
        <?php
        $this->insert_footer($data);
    }
}
