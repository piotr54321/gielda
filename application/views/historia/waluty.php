<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid">
    <?php
    if(isset($info)){
        echo "<div class='alert alert-info' role='alert'>";
        echo $info;
        echo "</div>";
    }else{
    ?>
    <?php if(isset($uzytkownik)){?><h3>Historia walut dla użytkownika: <?php echo $uzytkownik[0]['nazwa_uzytkownika']; ?></h3> <?php }else{
        ?><h3>Historia akcji</h3><?php
    }
    ?>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <?php if(!isset($uzytkownik)){ ?><th scope="col">Nick</th><?php } ?>
                <th scope="col">Czas</th>
                <th scope="col">Waluta</th>
                <th scope="col">Ilość kupna/sprzedaży</th>
                <th scope="col">Rodzaj transakcji</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $a = (($page - 1) * 15) + 1;
            foreach ($waluty as $item): ?>
                <tr class="<?php echo(($item['ilosc'] > 0) ? 'table-success' : ($item['ilosc']==0?'table-info':'table-danger')); ?>">
                    <td scope="row"><?php echo $a; ?></td>
                    <?php if(!isset($uzytkownik)){ ?><th scope="col"><?= $item['nazwa_uzytkownika']; ?></th><?php } ?>
                    <td><?php echo $item['czas']; ?></td>
                    <td><?php echo $item['nazwa']; ?></td>
                    <td><?php echo $item['ilosc']; ?></td>
                    <td><?php echo($item['ilosc'] > 0 ? 'Kupno' : ($item['ilosc']==0?'Utworzono portfel':'Sprzedaż')); ?></td>
                </tr>
                <?php
                $a++;
            endforeach;
            ?>
            </tbody>
        </table>
        <br/>
        <style>
            .pagination li a {
                color: #0b2e13;
            }
        </style>
        <?php
        if (isset($links)) {
            foreach ($links as $link) {
                echo $link;
            }
        }
        ?>
        <?php
        }
        ?>
    </div>
