<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid">

    <?php
    //var_dump($dane_portfela);

    if(isset($blad_danych)){
        echo "<div class='alert alert-info' role='alert'>";
        echo "Wprowadzono niepoprawne dane";
        echo "</div>";
    }elseif(isset($nie_znaleziono)){
        echo "<div class='alert alert-info' role='alert'>";
        echo "Nie znaleziono żadnego użytkownika";
        echo "</div>";
    }elseif($lista_uzytkownikow==false){
        echo "<div class='alert alert-info' role='alert'>";
        echo "Brak użytkowników";
        echo "</div>";
    }else{
        //var_dump($lista_uzytkownikow);
        ?>
        <div class="table-responsive">
            <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nazwa</th>
                <th scope="col">Typ użytkownika</th>
                <th scope="col">Operacja</th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1; ?>
            <?php foreach($lista_uzytkownikow as $row): ?>
                <tr>
                    <td scope="row"><?php echo $i; ?></td>
                    <td><?php echo $row['nazwa_uzytkownika']; ?></td>
                    <td>
                        <?php
                            $x = $this->acl_library->uzytkownik_role_nazwy($row['id']);
                            foreach ($x as $rola_nazwa): echo ' '.$rola_nazwa['nazwa_typu']; endforeach;
                        ?>
                    </td>
                    <td>
                        <a href="<?= base_url('uzytkownicy/edytuj/'.$row['id']);?>" role="button" class="btn btn-primary btn-sm">Edytuj<i class="material-icons">edit</i></a>
                        <?php if(!$this->uzytkownik_model->uzytkownik_czy_zaakceptowano($row['id'])){?><a href="<?= base_url('uzytkownicy/zaakceptuj/'.$row['id']);?>" role="button" class="btn btn-primary btn-sm">Zaakceptuj<i class="material-icons">how_to_reg</i></a><?php } ?>
                        <a href="<?= base_url('uzytkownicy/uzytkownik/'.$row['id']);?>" role="button" class="btn btn-primary btn-sm">Podgląd<i class="material-icons">account_circle</i></a>
                        <?php
                            if(!$this->uzytkownik_model->czy_ban($row['id'])) {
                                ?>
                                <a href="<?= base_url('uzytkownicy/zablokuj/' . $row['id'].'/1'); ?>" role="button"
                                   class="btn btn-primary btn-sm">Zablokuj dostęp<i class="material-icons">block</i></a>
                                <?php
                            }else{
                                ?>
                                <a href="<?= base_url('uzytkownicy/zablokuj/' . $row['id'].'/2'); ?>" role="button"
                                   class="btn btn-primary btn-sm">Oblokuj dostęp<i class="material-icons">block</i></a>
                        <?php
                            }
                       ?>
                    </td>
                </tr>
                <?php $i++; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>


        <?php
    }
    ?>

    <a href="<?= base_url('uzytkownicy/zalogowani/');?>" role="button" class="btn btn-primary btn-sm">Zalogowani teraz<i class="material-icons">people</i></a>
    <a href="<?= base_url('historia/waluty_wszystkie');?>" role="button" class="btn btn-primary btn-sm">Historia kupna/sprzedaży walut<i class="material-icons">history</i></a>
    <a href="<?= base_url('historia/akcje_wszystkie/');?>" role="button" class="btn btn-primary btn-sm">Historia kupna/sprzedaży akcji<i class="material-icons">history</i></a>
    <a href="<?= base_url('historia/logowania_wszystkie/');?>" role="button" class="btn btn-primary btn-sm">Historia wszystkich logowań<i class="material-icons">history</i></a>
    <br/>
    <br/>
    <style>
        .pagination li a {
            color: #0b2e13;
        }
    </style>
    <?php
        if(isset($links)){
            //var_dump($links);
            foreach ($links as $link) {
                echo $link;
            }
        }

    ?>
    <div class="alert alert-info" role="alert">
        Chcesz wpłacić pieniądze? <a href="<?= base_url('/portfel/wplata'); ?>" class="alert-link">Kliknij tutaj</a>
    </div>


</div>