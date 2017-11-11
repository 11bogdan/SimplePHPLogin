<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RegisterView
 *
 * @author Богдан
 */
require(ROOT_URL."/Views/View.php");
class RegisterView extends View {
    //put your code here
    
    public function html_view($data) {
        $data["title"] = "Registration Page";
        $data["name"] = "Registration form";
        $data["scripts"] = ["registration"];
        
        $this->insert_header($data);?>
        <form action="register_post" method="post" onsubmit="return onSubmit()">
            <div>
                <label for="email">Email</label>
                <input type="text" name="email">
                <label id="email" class="validation"></label>
            </div>
             <div>
                <label for="login">Login</label>
                <input type="text" name="login">
                <label id="login" class="validation"></label>
            </div>
             <div>
                <label for="real_name">Your name</label>
                <input type="text" name="real_name">
                <label id="real_name" class="validation"></label>
            </div>
             <div>
                <label for="password">Password</label>
                <input type="password" name="password">
                <label id="password" class="validation"></label>
            </div>
            <div>
                <label for="password">Repeat password</label>
                <input type="password" name="password_repeat">
                <label id="password_repeat" class="validation"></label>
            </div>
             <div>
                <label for="birth_date">Birth date</label>
                <input type="date" name="birth_date" min="1900" max="2010">
                <label id="birth_date" class="validation"></label>
            </div>
             <div>
                <label for="country">Country</label>
                <input type="text" name="country">
                <label id="country" class="validation"></label>
            </div>
             <div>
                <label for="agree">I agree with terms and conditions</label>
                <input type="checkbox" name="agree">
                <label id="agree" class="validation"></label>
            </div>
            <input type="submit">
            <input type="hidden" name="return" value="<?=$data["return"]?>">
            <label id="please_wait"></label>
        </form>
        <?php
        $this->insert_footer($data);
    }
}
