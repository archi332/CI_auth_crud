<?php
/**@var CI_Loader $this */

$is_logged = $this->ion_auth->logged_in();
?>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a class="navbar-brand" href="/">CI test project</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php if ($is_logged) : ?>
                        <li><a href="<?= base_url('auth/logout') ?>">Logout</a></li>
                    <?php else: ?>
                        <li><a href="<?= base_url('auth/login') ?>">Login</a></li>
                        <li><a href="<?= base_url('auth/register') ?>">Register</a></li>
                    <?php endif; ?>
                    <li><a href="<?= base_url('/userList') ?>">Table</a></li>
                </ul>

            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
