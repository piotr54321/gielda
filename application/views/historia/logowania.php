<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid">
<?php
if(isset($info)){
    echo '<div class="alert alert-danger" role="alert">'.$info.'</div>';
}else {
?>


<?php if(isset($uzytkownik)){?><h3>Historia logowań dla użytkownika: <?php echo $uzytkownik[0]['nazwa_uzytkownika']; ?></h3> <?php }else{
    ?><h3>Historia logowań</h3><?php
}

?>

    <div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <?php if(!isset($uzytkownik)){ ?><th scope="col">Nick</th><?php } ?>
            <th scope="col">Czas</th>
            <th scope="col">Adres IP</th>
            <th scope="col">Przeglądarka</th>
        </tr>
        </thead>
        <tbody>
        <?php
        //var_dump($page);
        $a=(($page-1)*15)+1;
        foreach ($logowania as $item): ?>
            <tr>
                <td scope="row"><?php echo $a; ?></td>
                <?php if(!isset($uzytkownik)){ ?><th scope="col"><?= $item['nazwa_uzytkownika']; ?></th><?php } ?>
                <td><?php echo $item['czas']; ?></td>
                <td><?php echo $item['adres_ip']; ?></td>
                <td><?php echo $item['przegladarka']; ?></td>
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

    }
    ?>
    <div class="alert alert-info" role="alert">
        Chcesz wpłacić pieniądze? <a href="<?= base_url('/portfel/wplata'); ?>" class="alert-link">Kliknij tutaj</a>
    </div>
</div>