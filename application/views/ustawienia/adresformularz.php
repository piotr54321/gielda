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
            <?php echo form_open('ustawienia/edytuj_adres/'); ?>

                <div class="col-md-4 mb-3">
                    <label for="panstwo">Kraj zamieszkania:</label>
                    <select class="custom-select" name="panstwo">
                        <option value="" selected><?php echo $wiersz['panstwo_zamieszkania']; ?></option>
                        <optgroup label="Państwo">
                            <?php foreach ($panstwa as $panstwo): ?>
                                <option value="<?php echo $panstwo['id']; ?>"><?php echo $panstwo['name']; ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="kod_pocztowy">Kod pocztowy</label>
                    <input type="varchar" class="form-control" name="kod_pocztowy" placeholder="<?php echo $wiersz['kod_pocztowy']; ?>" required>
                    <div class="valid-feedback">
                        Podaj kod pocztowy!
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="miejscowosc">Miejscowość</label>
                    <input type="text" class="form-control" name="miejscowosc" placeholder="<?php echo $wiersz['miejscowosc'];?>" required>
                    <div class="valid-feedback">
                        Podaj miejscowość!
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="numer_domu">Numer domu</label>
                    <input type="varchar" class="form-control" name="numer_domu" placeholder="<?php echo $wiersz['numer_domu'];?>" required>
                    <div class="valid-feedback">
                        Podaj numer domu!
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="numer_lokalu">Numer lokalu</label>
                    <input type="varchar" class="form-control" name="numer_lokalu" placeholder="<?php echo $wiersz['numer_lokalu'];?>" required>
                    <div class="valid-feedback">
                        Podaj numer lokalu!
                    </div>
                </div>
            <input type="hidden" name="idAdres" value="<?php echo $wiersz['id_adres'] ?>">
            <?php echo form_submit('submit', 'Zatwierdź','class="btn btn-lg btn-primary"'); ?>

            <a href="<?= base_url('ustawienia/dodajadres') ?>" class="btn btn-lg btn-info" role="button">Dodaj adres</a>
            <a href="<?= base_url('ustawienia/mojeadresy') ?>" class="btn btn-lg btn-info" role="button">Moje adresy</a>
            <?php echo form_close(); ?>

        <?php
        endforeach;
    }
        ?>
    </div>
