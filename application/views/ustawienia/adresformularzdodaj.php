<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid">

    <?php

    if(isset($info)){
        echo "<div class='alert alert-info' role='alert'>";
        echo $info;
        echo "</div>";
    }


    if(!$dane_uzytkownika){
        echo 'błąd';
    }else {

        foreach ($dane_uzytkownika as $wiersz):
            ?>
            <?php echo form_open('ustawienia/dodaj_adres/'); ?>

            <div class="col-md-4 mb-3">
                <label for="panstwo">Kraj zamieszkania:</label>
                <select class="custom-select" name="panstwo">
                    <option value="" selected>Państwo</option>
                    <optgroup label="Państwo">
                        <?php foreach ($panstwa as $panstwo): ?>
                            <option value="<?php echo $panstwo['id']; ?>"><?php echo $panstwo['name']; ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label for="kod_pocztowy">Kod pocztowy</label>
                <input type="varchar" class="form-control" name="kod_pocztowy" placeholder="Kod pocztowy" required>
                <div class="valid-feedback">
                    Podaj kod pocztowy!
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <label for="miejscowosc">Miejscowość</label>
                <input type="text" class="form-control" name="miejscowosc" placeholder="Miejscowość" required>
                <div class="valid-feedback">
                    Podaj miejscowość!
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <label for="numer_domu">Numer domu</label>
                <input type="varchar" class="form-control" name="numer_domu" placeholder="Numer domu" required>
                <div class="valid-feedback">
                    Podaj numer domu!
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <label for="numer_lokalu">Numer lokalu</label>
                <input type="varchar" class="form-control" name="numer_lokalu" placeholder="Numer lokalu" required>
                <div class="valid-feedback">
                    Podaj numer lokalu!
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="czy_glowny">Adres główny</label>
                <label class="radio-inline"><input type="radio" value="1" name="czy_glowny">Tak</label>
                <label class="radio-inline"><input type="radio" value="0" name="czy_glowny" checked>Nie</label>
            </div>
            <?php echo form_submit('submit', 'Zatwierdź','class="btn btn-lg btn-primary"'); ?>

            <a href="<?= base_url('ustawienia/dodajadres') ?>" class="btn btn-lg btn-info" role="button">Dodaj adres</a>
            <?php echo form_close(); ?>

        <?php
        endforeach;
    }
    ?>
</div>
