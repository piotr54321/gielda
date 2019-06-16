<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid">

<?php
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
<div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Waluta</th>
                <th scope="col">Ilość</th>
                <th scope="col">Operacja</th>
            </tr>
            </thead>
            <tbody>
                <?php $i=1; ?>
                <?php foreach($dane_portfela as $row): ?>
                    <tr>
                        <td scope="row"><?php echo $i; ?></td>
                        <td><?php echo $row['nazwa']; ?></td>
                        <td><?php echo round( $row['ilosc'], 2, PHP_ROUND_HALF_DOWN); ?></td>
                        <td><?php if($row['wplata']==1){ ?><a href="<?= base_url('portfel/wyplata/'.$row['id']);?>" role="button" class="btn btn-primary btn-xs">Wypłać<i class="material-icons">call_made</i></a><?php } ?></td>
                    </tr>
                    <?php $i++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
</div>
<br/>
        <?php
    }
?>
    <div class="alert alert-info" role="alert">
        Chcesz wpłacić pieniądze? <a href="<?= base_url('/portfel/wplata'); ?>" class="alert-link">Kliknij tutaj</a>
    </div>
</div>