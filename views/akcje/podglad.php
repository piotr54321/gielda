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
    
        if(isset($brak_notowan)){
            echo "<div class='alert alert-info' role='alert'>";
            echo $brak_notowan;
            echo "</div>";
        }else{
    ?>
<div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Indeks giełdowy</th>
                <th scope="col">Nazwa spółki</th>
                <th scope="col">Ilość akcji spółki</th>
                <th scope="col">Cena za 1 akcję</th>
                <th scope="col">Wartość spółki</th>
                <!--<th scope="col">Czas aktualizacji</th>-->
                <th scope="col">Operacja</th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1; ?>
            <?php foreach($notowania as $row): ?>
                <tr class="<?= $this->akcje_model->czy_wzrost_kolor($row['id_spolki']); ?>">
                    <td scope="row"><?php echo $i; ?></td>
                    <td><?php echo $row['nazwa_indeksu']; ?></td>
                    <td><?php echo $row['nazwa_spolki']; ?></td>
                    <td><?php echo $row['ilosc_akcji']; ?></td>
                    <td><?php echo $row['aktualna_cena_akcji']; ?></td>
                    <td><?php echo $row['cena_spolki']; ?></td>
                    <!--<th><?php //echo $row['czas']; ?></th>-->
                    <td>
                        <a href="<?= base_url('akcje/kup/'.$row['id_spolki']);?>" role="button" class="btn btn-primary btn-sm">Kup<i class="material-icons">call_received</i></a>
                        <?php if($this->akcje_model->uzytkownik_akcje_czy_posiada($this->session->id_uzytkownika, $row['id_spolki'])){ ?><a href="<?= base_url('akcje/sprzedaj/'.$row['id_spolki']);?>" role="button" class="btn btn-primary btn-sm">Sprzedaj<i class="material-icons">call_made</i></a><?php } ?>
                        <a href="<?= base_url('akcje/spolka/'.$row['id_spolki']);?>" role="button" class="btn btn-primary btn-sm">Notowania spółki<i class="material-icons">show_chart</i></a>

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
    <div class="alert alert-info" role="alert">
        Chcesz wpłacić pieniądze? <a href="<?= base_url('/portfel/wplata'); ?>" class="alert-link">Kliknij tutaj</a>
    </div>

</div>