<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rejestracja</title>
    <?php /*<link href="<?php echo base_url('assets/css/bootstrap.min.css');?>" rel="stylesheet"> */?>
    <link href="https://bootswatch.com/4/flatly/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/adds/signin.css');?>" rel="stylesheet">
</head>
<body>
<div class="container">

    <?php
        if(isset($blad)){
            echo "<div class='alert alert-info' role='alert'>";
            echo $blad;
            echo "</div>";
        }else {
            ?>

            <?php echo form_open('rejestracja/ustaw_haslo_check', 'class="form-signin"'); ?>

            <h2 class="form-signin-heading">Ustaw swoje hasło</h2>
            <label for="inputEmail" class="sr-only">Twój adres email</label>
            <input type="email" name="inputEmail" value="<?php echo set_value('inputEmail'); ?>" class="form-control" placeholder="Adres email" required="" autofocus="">
            <label for="inputPassword" class="sr-only">Hasło</label>
            <input type="password" value="<?php echo set_value('inputPassword'); ?>" name="inputPassword" class="form-control" placeholder="Hasło" required="">
            <label for="inputPassword2" class="sr-only">Hasło</label>
            <input type="password" value="<?php echo set_value('inputPassword2'); ?>" name="inputPassword2" class="form-control" placeholder="Powtórz hasło" required="">
            <input type="hidden" name="inputHash" value="<?php echo $this->uri->segment(3, 0); ?>">
            <div class="checkbox">
                <label>
                    <input type="checkbox" value="remember-me"> Zapamiętaj mnie
                </label>
            </div>
            <?php echo form_submit('submit', 'Zarejestruj się', 'class="btn btn-lg btn-primary btn-block"'); ?>
            <?php echo form_close(); ?>
            <?php
        }
    ?>
</div>

</body>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>
</html>