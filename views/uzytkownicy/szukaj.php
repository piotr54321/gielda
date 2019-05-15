<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid">


    <h1>Szukaj</h1>

    <?php echo form_open('uzytkownicy/szukaj_check/'); ?>
    <form class="needs-validation" novalidate>
        <div class="col-md-4 mb-3">
            <label for="FormImie">Imie</label>
            <input type="text" class="form-control" name="FormImie" value="<?php echo set_value('FormImie'); ?>" placeholder="imie">
            <div class="valid-feedback">Looks good!</div>
        </div>
        <div class="col-md-4 mb-3">
            <label for="FormNazwisko">Nazwisko</label>
            <input type="text" class="form-control" name="FormNazwisko" value="<?php echo set_value('FormNazwisko'); ?>" placeholder="nazwisko">
            <div class="valid-feedback">Looks good!</div>
        </div>
        <div class="col-md-4 mb-3">
            <label for="FormNazwaUzytkownika">Nazwa użytkownika</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                </div>
                <input type="text" class="form-control" name="FormNazwaUzytkownika" placeholder="nazwa_uzytkownika" value="<?php echo set_value('FormNazwaUzytkownika'); ?>" aria-describedby="inputGroupPrepend">
                <div class="invalid-feedback">
                    Wybierz nazwe użytkownika.
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <label for="FormDataUrodzenia">Data urodzenia</label>
            <input type="date" class="form-control" name="FormDataUrodzenia" placeholder="data_urodzenia" value="<?php echo set_value('FormDataUrodzenia'); ?>">
            <div class="valid-feedback">
                Podaj date urodzenia!
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <label for="FormTypUzytkownika">Typ użytkownika:</label>
            <select class="custom-select" name="FormTypUzytkownika">
                <optgroup label="Użytkownik">
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
                    <option value="0" selected>Brak wyboru</option>
                    <?php foreach ($panstwa as $panstwo): ?>
                        <option value="<?php echo $panstwo['id']; ?>"><?php echo $panstwo['name']; ?></option>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label for="FormKodPocztowy">Kod pocztowy</label>
            <input type="varchar" class="form-control" name="FormKodPocztowy" placeholder="kod_pocztowy" value="<?php echo set_value('FormKodPocztowy'); ?>">
            <div class="valid-feedback">
                Podaj kod pocztowy!
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <label for="FormMiejscowosc">Miejscowość</label>
            <input type="text" class="form-control" name="FormMiejscowosc" placeholder="miejscowość" value="<?php echo set_value('FormMiejscowosc'); ?>">
            <div class="valid-feedback">
                Podaj miejscowość!
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <label for="FormNrDomu">Numer domu</label>
            <input type="varchar" class="form-control" name="FormNrDomu" placeholder="numer_domu" value="<?php echo set_value('FormNrDomu'); ?>">
            <div class="valid-feedback">
                Podaj numer domu!
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <label for="FormNrLokalu">Numer lokalu</label>
            <input type="varchar" class="form-control" name="FormNrLokalu" placeholder="numer_lokalu" value="<?php echo set_value('FormNrLokalu'); ?>">
            <div class="valid-feedback">
                Podaj numer lokalu!
            </div>
        </div>


        <?php echo form_submit('submit', 'Wyszukaj','class="btn btn-lg btn-primary"'); ?>
        <?php echo form_close(); ?>


        <br/>
        <div class="alert alert-info" role="alert">
            Chcesz wpłacić pieniądze? <a href="<?= base_url('/portfel/wplata'); ?>" class="alert-link">Kliknij tutaj</a>
        </div>


</div>