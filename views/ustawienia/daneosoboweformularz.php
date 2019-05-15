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

        foreach ($dane_uzytkownika as $wiersz2):
            ?>

            <?php echo form_open('ustawienia/edytuj_daneosob/'); ?>

            <div class="col-md-4 mb-3">
                <label for="imie">Imie</label>
                <input type="text" class="form-control" name="imie" placeholder="<?php echo $wiersz2['imie'];?>" required>
                <div class="valid-feedback">
                    Podaj imie!</div>
            </div>


            <div class="col-md-4 mb-3">
                <label for="nazwisko">Nazwisko</label>
                <input type="text" class="form-control" name="nazwisko" placeholder="<?php echo $wiersz2['nazwisko'];?>" required>
                <div class="valid-feedback">
                    Podaj nazwisko!
                </div>
            </div>




            <?php echo form_submit('submit', 'Zatwierdź','class="btn btn-lg btn-primary"'); ?>
            <?php echo form_close(); ?>


         <?php
          endforeach;
     }
         ?>
    </div>
