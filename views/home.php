<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            Zalogowano jako
        </div>
        <div class="card-body">
            <h5 class="card-title"><?php echo $this->session->nazwa_uzytkownika; ?></h5> role na stronie: <?php foreach ($role_nazwy as $rola_nazwa): echo ' '.$rola_nazwa['nazwa_typu']; endforeach; ?>
        </div>
    </div>
</div>
