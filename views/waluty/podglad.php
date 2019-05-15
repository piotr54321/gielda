<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid">

    <?php
    //var_dump($dane_portfela);
    if(isset($brak_walut)){
        echo "<div class='alert alert-info' role='alert'>";
        echo $brak_walut;
        echo "</div>";
    }else{
        if(isset($info)){
            echo "<div class='alert alert-info' role='alert'>";
            echo $info;
            echo "</div>";
        }
        ?>

        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Waluta</th>
                <th scope="col">Kurs</th>
                <th scope="col">Operacja</th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1; ?>
            <?php foreach($dane_portfela as $row): ?>
                <tr>
                    <td scope="row"><?php echo $i; ?></td>
                    <td><?php echo $row['wa1']; ?>/<?php echo $row['wa2']; ?></td>
                    <td><?php echo round( $row['cena'], 2, PHP_ROUND_HALF_DOWN); ?></td>
                    <td><?php if($this->portfel_model->uzytkownik_waluta($this->session->id_uzytkownika, $row['id_waluta1'])){  ?><a href="<?= base_url('waluty/wymien/'.$row['id_waluta1'].'/'.$row['id_waluta2']);?>" role="button" class="btn btn-primary btn-sm">Wymień<i class="material-icons">swap_calls</i></a><?php } ?> <?php /* if($this->portfel_model->uzytkownik_waluta($this->session->id_uzytkownika, $row['id_waluta2'])){  ?><a href="<?= base_url('portfel/kup/'.$row['id']);?>" role="button" class="btn btn-primary btn-xs">Kup<i class="material-icons">call_received</i></a><?php } */ ?></td>
                </tr>
                <?php $i++; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }
    ?>
    <div class="alert alert-info" role="alert">
        Chcesz wpłacić pieniądze? <a href="<?= base_url('/portfel/wplata'); ?>" class="alert-link">Kliknij tutaj</a>
    </div>
</div>