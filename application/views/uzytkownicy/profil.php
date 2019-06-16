<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid">

    <?php

    if(isset($info)){
        echo '<div class="alert alert-danger" role="alert">'.$info.'</div>';
    }else {

        ?>

        <div class="card">
            <div class="card-header">
                Profil
            </div>
            <div class="card-body">
                <?php
                    foreach ($uzytkownik_info as $row):
                ?>
                <h5 class="card-title">Nazwa użytkownika: <b><?= $row['nazwa_uzytkownika']; ?></b></h5>
                <p class="card-text">Adres e-mail: <b><?= $row['email']; ?></b></p>
                <p class="card-text">Imie: <b><?= ucfirst(strtolower($row['imie'])); ?></b></p>
                <p class="card-text">Nazwisko: <b><?= ucfirst(strtolower($row['nazwisko'])); ?></b></p>
                <p class="card-text">Data urodzenia: <b><?= date('Y-m-d', strtotime($row['data_urodzenia'])); ?></b></p>
                <a href="<?= base_url('uzytkownicy/edytuj/'.$row['id_uzytkownika']);?>" role="button" class="btn btn-primary btn-sm">Edytuj<i class="material-icons">edit</i></a>
                <a href="<?= base_url('uzytkownicy/resetuj_haslo/'.base64_encode($row['email']));?>" role="button" class="btn btn-primary btn-sm">Resetuj hasło<i class="material-icons">vpn_key</i></a>
                <a href="<?= base_url('historia/logowania/'.$row['id_uzytkownika'].'/1');?>" role="button" class="btn btn-primary btn-sm">Historia logowań<i class="material-icons">history</i></a>

                <a href="<?= base_url('historia/waluty/'.$row['id_uzytkownika'].'/1');?>" role="button" class="btn btn-primary btn-sm">Historia kupna/sprzedaży walut<i class="material-icons">history</i></a>
                <a href="<?= base_url('historia/akcje/'.$row['id_uzytkownika'].'/1');?>" role="button" class="btn btn-primary btn-sm">Historia kupna/sprzedaży akcji<i class="material-icons">history</i></a>
                        <?php
                        if(!$this->uzytkownik_model->czy_ban($row['id_uzytkownika'])) {
                            ?>
                            <a href="<?= base_url('uzytkownicy/zablokuj/' . $row['id_uzytkownika'].'/1'); ?>" role="button"
                               class="btn btn-primary btn-sm">Zablokuj dostęp<i class="material-icons">block</i></a>
                            <?php
                        }else{
                            ?>
                            <a href="<?= base_url('uzytkownicy/zablokuj/' . $row['id_uzytkownika'].'/2'); ?>" role="button"
                               class="btn btn-primary btn-sm">Oblokuj dostęp<i class="material-icons">block</i></a>
                            <?php
                        }
                        ?>


                <?php
                    endforeach;
                ?>
            </div>
        </div>

        <?php
    }
    ?>
</div>