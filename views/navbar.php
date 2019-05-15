<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="<?= base_url('home');?>">Gielda</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">

        <?php
            if($this->acl_library->uzytkownik_funkcja_zezwol('portfel')) {
                ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Portfel
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php if($this->acl_library->uzytkownik_funkcja_zezwol('portfel/wplata')) { ?><a class="dropdown-item" href="<?= base_url('portfel/wplata') ?>">Wpłata środków</a><?php } ?>
                        <!-- <a class="dropdown-item" href="<?= base_url('portfel/wyplata') ?>">Wypłata środków</a> -->
                        <!--<div class="dropdown-divider"></div>-->
                        <?php if($this->acl_library->uzytkownik_funkcja_zezwol('portfel/index')) { ?><a class="dropdown-item" href="<?= base_url('portfel/') ?>">Podgląd portfela</a><?php } ?>
                    </div>
                </li>
                <?php
            }
        ?>

        <?php
        if($this->acl_library->uzytkownik_funkcja_zezwol('waluty')) {
            ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Waluty
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php if($this->acl_library->uzytkownik_funkcja_zezwol('portfel/wplata')) { ?><a class="dropdown-item" href="<?= base_url('waluty/kup') ?>">Kup walutę</a><?php } ?>
                    <!--<div class="dropdown-divider"></div>-->
                    <?php if($this->acl_library->uzytkownik_funkcja_zezwol('waluty/index')) { ?><a class="dropdown-item" href="<?= base_url('waluty/podglad') ?>">Podgląd kursów walut</a><?php } ?>
                </div>
            </li>
            <?php
        }
        ?>

        <?php
            if($this->acl_library->uzytkownik_funkcja_zezwol('akcje')) {
                ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Akcje
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php /* if($this->acl_library->uzytkownik_funkcja_zezwol('akcje/kup')) { ?><a class="dropdown-item" href="<?= base_url('akcje/kup') ?>">Kup akcje</a><?php } */?>
                        <?php if($this->acl_library->uzytkownik_funkcja_zezwol('akcje/moje_akcje')) { ?><a class="dropdown-item" href="<?= base_url('akcje/moje_akcje') ?>">Moje akcje</a><?php } ?>
                        <!--<div class="dropdown-divider"></div>-->
                        <?php if($this->acl_library->uzytkownik_funkcja_zezwol('akcje/podglad')) { ?><a class="dropdown-item" href="<?= base_url('akcje/podglad') ?>">Podgląd kursu</a><?php } ?>
                    </div>
                </li>
                <?php
            }
            ?>

        <?php
        if($this->acl_library->uzytkownik_funkcja_zezwol('uzytkownicy')) {
            ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Użytkownicy
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php if($this->acl_library->uzytkownik_funkcja_zezwol('uzytkownicy/dodaj')) { ?><a class="dropdown-item" href="<?= base_url('uzytkownicy/dodaj') ?>">Dodaj użytkownika</a><?php } ?>
                    <?php if($this->acl_library->uzytkownik_funkcja_zezwol('uzytkownicy/szukaj')) { ?><a class="dropdown-item" href="<?= base_url('uzytkownicy/szukaj') ?>">Wyszukaj użytkownika</a><?php } ?>
                    <!--<div class="dropdown-divider"></div>-->
                    <?php if($this->acl_library->uzytkownik_funkcja_zezwol('uzytkownicy/index')) { ?><a class="dropdown-item" href="<?= base_url('uzytkownicy/podglad') ?>">Podgląd użytkowników</a><?php } ?>
                </div>
            </li>
            <?php
        }
        ?>

        <?php
        if($this->acl_library->uzytkownik_funkcja_zezwol('ustawienia')) {
            ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Ustawienia
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php if($this->acl_library->uzytkownik_funkcja_zezwol('ustawienia/adresy')) { ?><a class="dropdown-item" href="<?= base_url('ustawienia/adresy') ?>">Adresy</a><?php } ?>
                    <?php if($this->acl_library->uzytkownik_funkcja_zezwol('ustawienia/dane_logowania')) { ?><a class="dropdown-item" href="<?= base_url('ustawienia/dane_logowania') ?>">Dane logowania</a><?php } ?>
                    <!--<div class="dropdown-divider"></div>-->
                    <?php if($this->acl_library->uzytkownik_funkcja_zezwol('ustawienia/dane_osobowe')) { ?><a class="dropdown-item" href="<?= base_url('ustawienia/dane_osobowe') ?>">Dane osobowe</a><?php } ?>
                </div>
            </li>
            <?php
        }
        ?>

        <?php
        if($this->acl_library->uzytkownik_funkcja_zezwol('historia')) {
            ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Historia
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php if($this->acl_library->uzytkownik_funkcja_zezwol('historia/akcje')) { ?><a class="dropdown-item" href="<?= base_url('historia/akcje/0/1') ?>">Moja historia kupna/sprzedaży akcji</a><?php } ?>
                    <?php if($this->acl_library->uzytkownik_funkcja_zezwol('historia/waluty')) { ?><a class="dropdown-item" href="<?= base_url('historia/waluty/0/1') ?>">Moja historia kupna/wymiany walut</a><?php } ?>
                    <!--<div class="dropdown-divider"></div>-->
                    <?php if($this->acl_library->uzytkownik_funkcja_zezwol('historia/logowania')) { ?><a class="dropdown-item" href="<?= base_url('historia/logowania/0/1') ?>">Moja historia logowań</a><?php } ?>
                </div>
            </li>
            <?php
        }
        ?>
    </ul>
    <a href="<?= base_url('logowanie/wyloguj_uzytkownika');?>" role="button" class="btn btn-dark my-2 my-sm-0">Wyloguj się</a>
    </div>
</nav>
<br />