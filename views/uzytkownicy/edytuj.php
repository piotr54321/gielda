<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid">

    <?php
    //var_dump($dane_portfela);
    if($dane_uzytkownika==false){
        echo "<div class='alert alert-info' role='alert'>";
        echo "Brak użytkowników";
        echo "</div>";
    }else{
        //var_dump($lista_uzytkownikow);
        ?>
    <h1>Edytuj</h1>
    <?php foreach($dane_uzytkownika as $row): ?>
    <?php echo form_open('uzytkownicy/edytuj_check/'); ?>
    <form class="needs-validation" novalidate>
        <div class="col-md-4 mb-3">
            <label for="FormImie">Imie</label>
            <input type="text" class="form-control" name="FormImie" value="<?php echo set_value('FormImie'); ?>" placeholder="<?php echo $row['imie']; ?>">
            <div class="valid-feedback">Looks good!</div>
        </div>
        <div class="col-md-4 mb-3">
            <label for="FormNazwisko">Nazwisko</label>
            <input type="text" class="form-control" name="FormNazwisko" value="<?php echo set_value('FormNazwisko'); ?>" placeholder="<?php echo $row['nazwisko']; ?>">
            <div class="valid-feedback">Looks good!</div>
        </div>
        <div class="col-md-4 mb-3">
            <label for="FormNazwaUzytkownika">Nazwa użytkownika</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                </div>
                <input type="text" class="form-control" name="FormNazwaUzytkownika" placeholder="<?php echo $row['nazwa_uzytkownika']; ?>" value="<?php echo set_value('FormNazwaUzytkownika'); ?>" aria-describedby="inputGroupPrepend">
                <div class="invalid-feedback">
                    Wybierz nazwe użytkownika.
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <label for="FormEmail">Adress Email</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                </div>
                <input type="text" class="form-control" name="FormEmail" placeholder="Adress Email" placeholder="<?php echo $row['email']; ?>" value="<?php echo set_value('FormEmail'); ?>" aria-describedby="inputGroupPrepend">
                <div class="invalid-feedback">
                    Wpisz adres email.
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <label for="FormDataUrodzenia">Data urodzenia</label>
            <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control" name="FormDataUrodzenia" placeholder="<?php echo $row['data_urodzenia']; ?>">
            <div class="valid-feedback">
                Podaj date urodzenia!
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <label for="FormTypUzytkownika">Typ użytkownika:</label>
            <select class="custom-select" name="FormTypUzytkownika[]" multiple>
                <optgroup label="Użytkownik">
                    <?php /*<option value="" selected><?php echo $row['nazwa_rangi']; ?></option>*/ ?>
                    <?php foreach ($role_uzytkownikow as $rola_nazwa): ?>
                    <option value="<?php echo $rola_nazwa['typ_uzytkownika']; ?>"><?php echo $rola_nazwa['nazwa_typu']; ?></option>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label for="FormPanstwo">Państwo zamieszkania:</label>
            <select class="custom-select" name="FormPanstwo">
                <optgroup label="Państwo">
                    <option value="" selected><?php echo $row['panstwo_zamieszkania']; ?></option>
                    <?php foreach ($panstwa as $panstwo): ?>
                    <option value="<?php echo $panstwo['id']; ?>"><?php echo $panstwo['name']; ?></option>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label for="FormKodPocztowy">Kod pocztowy</label>
            <input type="varchar" class="form-control" name="FormKodPocztowy" placeholder="<?php echo $row['kod_pocztowy']; ?>" value="<?php echo set_value('FormKodPocztowy'); ?>">
            <div class="valid-feedback">
                Podaj kod pocztowy!
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <label for="FormMiejscowosc">Miejscowość</label>
            <input type="text" class="form-control" name="FormMiejscowosc" placeholder="<?php echo $row['miejscowosc']; ?>" value="<?php echo set_value('FormMiejscowosc'); ?>">
            <div class="valid-feedback">
                Podaj miejscowość!
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <label for="FormNrDomu">Numer domu</label>
            <input type="varchar" class="form-control" name="FormNrDomu" placeholder="<?php echo $row['numer_domu']; ?>" value="<?php echo set_value('FormNrDomu'); ?>">
            <div class="valid-feedback">
                Podaj numer domu!
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <label for="FormNrLokalu">Numer lokalu</label>
            <input type="varchar" class="form-control" name="FormNrLokalu" placeholder="<?php echo $row['numer_lokalu']; ?>" value="<?php echo set_value('FormNrLokalu'); ?>">
            <div class="valid-feedback">
                Podaj numer lokalu!
            </div>
        </div>

        <input type="hidden" name="idUzytkownika" value="<?php echo $row['id_uzytkownika'] ?>">
        <?php echo form_submit('submit', 'Zatwierdź','class="btn btn-lg btn-primary"'); ?>
        <?php echo form_close(); ?>
        <?php endforeach; ?>
        <?php
    }
    ?>
    <br/>
    <div class="alert alert-info" role="alert">
        Chcesz wpłacić pieniądze? <a href="<?= base_url('/portfel/wplata'); ?>" class="alert-link">Kliknij tutaj</a>
    </div>


</div>