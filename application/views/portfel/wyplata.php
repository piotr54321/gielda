<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid">

    <?php
    if(isset($info)){
        echo "<div class='alert alert-info' role='alert'>";
        echo $info;
        echo "</div>";
    }else{
        ?>
        <div class="container-fluid">
            <?php echo form_open('portfel/wyplata_check/'. $this->portfel_model->id_transakcji_tworz($this->session->id_uzytkownika, 1)); ?>
                <div class="form-group">
                <?php foreach($dane_portfela as $row): ?>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?= $row['nazwa']; ?></span>
                        <span class="input-group-text">Posiadasz: <?= round($row['ilosc'], 2, PHP_ROUND_HALF_DOWN); ?></span>
                    </div>
                    <input type="text" name="iloscWaluty" value="<?php echo set_value('iloscWaluty'); ?>" class="form-control" aria-label="Amount (to the nearest dollar)">
                    <input type="hidden" name="idWaluty" value="<?php echo $id_waluty ?>">
                </div>
                <?php endforeach; ?>
                </div>
            <?php ?>
            <?php echo form_submit('submit', 'Wypłać','class="btn btn-lg btn-primary btn-block"'); ?>
            <?php echo form_close(); ?>
        </div>
    <?php
    }
    ?>
</div>