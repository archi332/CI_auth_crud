<?php
/**@var $this object CI_Loader */
/**@var $message string */
/**@var $email string */
/**@var $password string */

?>
<div class="container-fluid">
    <div class="col-md-3"></div>
    <div class="col-md-3">
        <div class='mainInfo'>

            <div class="pageTitle">Login</div>
            <div class="pageTitleBorder"></div>
            <p>Please login with your email address and password below.</p>

            <div id="infoMessage" style="color: #cc0300"><?php echo $message; ?></div>

            <?= form_open("auth/login"); ?>

            <p>
                <label for="email">Email:</label>
                <?= form_input($email); ?>
            </p>
            <p>
                <label for="password">Password:</label>
                <?= form_input($password); ?>
            </p>
            <p>
                <label for="remember">Remember Me:</label>
                <?= form_checkbox('remember', '1', FALSE); ?>
            </p>
            <p><?= form_submit('submit', 'Login'); ?></p>

            <?= form_close(); ?>

        </div>
    </div>
</div>