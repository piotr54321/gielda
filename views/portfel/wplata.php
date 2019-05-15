<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid">

    <?php
    if(isset($brak_walut)){
        echo "<div class='alert alert-info' role='alert'>";
        echo $brak_walut;
        echo "</div>";
    }else{
        ?>

        <?php echo form_open('portfel/wplata_check/'. $this->portfel_model->id_transakcji_tworz($this->session->id_uzytkownika, 2)); ?>
            <div class="form-row align-items-center">
                <div class="col-auto">
                    <label class="sr-only" for="inlineFormInputGroup">Waluta</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Waluta</div>
                        </div>
                        <!--<input type="text" class="form-control" id="inlineFormInputGroup" placeholder="Username">-->
                        <select name="id_waluty" class="custom-select mr-sm-2" id="id_waluty">

                            <?php foreach($dane_portfela as $row): ?>
                                <option value="<?= $row['id'] ?>"><?= $row['nazwa'].' - '.$row['nazwa_kod'].' - '.$row['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-auto">
                    <label class="sr-only" for="iloscWaluty">Ilość</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Ilość</div>
                        </div>
                        <input type="text"name="iloscWaluty" value="<?php echo set_value('iloscWaluty'); ?>" class="form-control" id="iloscWaluty" placeholder="Ilość">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary mb-2">Wpłać</button>
                </div>
            </div>
        <?php echo form_close(); ?>

        <?php
    }
    ?>
</div>