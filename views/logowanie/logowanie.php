<link href="<?php echo base_url('assets/css/adds/signin.css');?>" rel="stylesheet">
<div class="container">

    <?php echo form_open('logowanie/zaloguj_uzytkownika','class="form-signin"'); ?>
    <?php
    if(isset($logout_message)){
        echo "<div class='alert alert-info' role='alert'>";
        echo $logout_message;
        echo "</div>";
    }
    if(isset($message_display)){
        echo "<div class='message'>";
        echo $message_display;
        echo "</div>";
    }
    if(isset($error_message)){
        echo "<div class='alert alert-danger' role='alert'>";
        echo $error_message;
        echo validation_errors();
        echo "</div>";
    }

    ?>
    <h2 class="form-signin-heading">Logowanie do systemu</h2>
    <label for="inputUser_ID" class="sr-only">Nazwa użytkownika</label>
    <input type="text" name="inputUser_ID" value="<?php echo set_value('inputUser_ID'); ?>" class="form-control" placeholder="Nazwa użytkownika lub e-mail" required="" autofocus="">
    <label for="inputPassword" class="sr-only">Hasło</label>
    <input type="password" value="<?php echo set_value('inputPassword'); ?>" name="inputPassword" class="form-control" placeholder="Hasło" required="">
    <div class="checkbox">
        <label>
            <input type="checkbox" value="remember-me"> Zapamiętaj mnie
        </label>
    </div>
    <?php echo form_submit('submit', 'Zaloguj się','class="btn btn-lg btn-primary btn-block"'); ?>
    <br/>

    <div class="alert alert-info" role="alert">
        Nie masz konta? <a href="<?= base_url('/rejestracja/'); ?>" class="btn btn-secondary btn-small active" role="button" aria-pressed="true">Zarejestruj się</a>
    </div>
    <?php echo form_close(); ?>
</div>
