<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid">

    <?php

    if(isset($info)){
        echo "<div class='alert alert-info' role='alert'>";
        echo "Wprowadzono niepoprawne dane";
        echo "</div>";
    }else{
        ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Państwo</th>
                    <th scope="col">Miejscowość</th>
                    <th scope="col">Numer domu</th>
                    <th scope="col">Numer lokalu</th>
                    <th scope="col">Kod pocztowy</th>
                    <th scope="col">Adres główny</th>
                </tr>
                </thead>
                <tbody>
                <?php $i=1; ?>
                <?php foreach($lista_adresow as $row): ?>
                    <tr>
                        <td scope="row"><?php echo $i; ?></td>
                        <td><?= $row['name']; ?></td>
                        <td><?= $row['miejscowosc']; ?></td>
                        <td><?= $row['numer_domu']; ?></td>
                        <td><?= $row['numer_lokalu']; ?></td>
                        <td><?= $row['kod_pocztowy']; ?></td>
                        <td><?php if($row['adres_glowny']==1){echo "tak";}else{?>
                                <?php echo form_open('ustawienia/ustaw_adres_glowny'); ?>
                                    <input type="text" name="id_adresu" value="<?= $row['id_adresu']; ?>" hidden>
                                <?php echo form_submit('submit', 'Ustaw jako główny','class="btn btn-lg btn-primary"'); ?>
                                <?php echo form_close(); ?>

                            <?php  } ?></td>
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