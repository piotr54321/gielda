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

        foreach ($dane_uzytkownika as $wiersz1):
            ?>

            <?php echo form_open('ustawienia/edytuj_danelog/','needs-validation'); ?>



            <div class="col-md-4 mb-3">
                <label for="nazwa_uzytkownika">Nazwa użytkownika</label>
                <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                </div>
                    <input type="text" class="form-control" name="nazwa_uzytkownika" placeholder="<?php echo $wiersz1['nazwa_uzytkownika']; ?>" required>
                    <div class="invalid-feedback">
                        Wybierz nazwe użytkownika.
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <label for="email">Adress Email</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                    </div>
                    <input type="text" class="form-control" name="email"  placeholder="<?php echo $wiersz1['email']; ?>" required>
                    <div class="invalid-feedback">
                        Wpisz adres email.
                    </div>
                </div>
            </div>

            <?php echo form_submit('submit', 'Zatwierdź','class="btn btn-lg btn-primary"'); ?>
            <?php echo form_close(); ?>


        <?php
        endforeach;
     }
        ?>


</div>