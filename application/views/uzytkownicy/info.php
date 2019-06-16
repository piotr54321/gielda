<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

    <div class="container-fluid">

    <?php
    //var_dump($dane_portfela);

    if(isset($info)){
        echo "<div class='alert alert-info' role='alert'>";
        echo $info;
        echo "</div>";
    }

    ?>
        <div class="alert alert-info" role="alert">
            Chcesz wpłacić pieniądze? <a href="<?= base_url('/portfel/wplata'); ?>" class="alert-link">Kliknij tutaj</a>
        </div>
    </div>
