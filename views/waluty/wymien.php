<div class="container-fluid">
    <?php

    if(isset($info)){
        echo "<div class='alert alert-info' role='alert'>";
        echo $info;
        echo "</div>";
    }elseif(!($kurs_waluty)){
        echo "<div class='alert alert-info' role='alert'>";
        echo 'Taki kurs walut nie istnieje';
        echo "</div>";
    }else{
        foreach ($kurs_waluty as $item) {
            //var_dump($wykupione_akcje);
            ?>
            <br/>
            <script>
                function calc(){
                    var ilosc_waluta1 = eval("<?php echo $posiadana_ilosc; ?>");
                    var ilosc_waluta2 = eval(document.getElementById('iloscWaluty').value);
                    var kurs = eval("<?php echo $kurs_waluty2[0]['cena']; ?>");
                    var waluta_nazwa = "<?php echo $item['wa1']; ?>";

                    if(ilosc_waluta2 < 0){
                        document.getElementById('total').value = 'Wprowadzono zbyt małą ilość';
                        document.getElementById("submit").disabled = true;
                    }else if(eval(ilosc_waluta2*kurs)  > ilosc_waluta1){
                        document.getElementById('total').value = 'Wprowadzono zbyt dużą ilość';
                        document.getElementById("submit").disabled = true;
                    }else{
                        //lert(amount1);
                        //alert(amount2);
                        var amount=kurs*ilosc_waluta2;
                        document.getElementById('total').value = amount+' '+waluta_nazwa;
                        document.getElementById("submit").disabled = false;
                    }
                }
            </script>

            <div class="card">
                <div class="card-header">
                    Wymień gotówkę
                </div>
                <div class="card-body">
                    <h5 class="card-title">Kurs: <?php echo $item['wa1']; ?>/<?php echo $item['wa2']; ?></h5>
                    <p class="card-text">Ilość dostępnej waluty (<?php echo $item['wa1']; ?>): <?php echo $posiadana_ilosc; ?></p>
                    <?php echo form_open('waluty/wymien_check/'. $this->portfel_model->id_transakcji_tworz($this->session->id_uzytkownika, 3)); ?>


                    <label class="sr-only" for="iloscWaluty">Ilość</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Ilość (<?php echo $item['wa2']; ?>) którą chcesz pozyskać:</div>
                        </div>
                        <input type="text"name="iloscWaluty" value="<?php echo set_value('iloscWaluty'); ?>" class="form-control" id="iloscWaluty" placeholder="Ilość" onChange="calc()">
                    </div>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Cena kupna: </div>
                        </div>
                        <input type="text" class="form-control" placeholder="0 <?php echo $item['wa1']; ?>" name="total" id="total" disabled>
                    </div>

                    <input type="hidden" name="idWaluta1" value="<?= $item['id_waluta1'] ?>">
                    <input type="hidden" name="idWaluta2" value="<?= $item['id_waluta2'] ?>">
                    <input type="hidden" name="nazwaWaluta2" value="<?= $item['wa2'] ?>">
                    <button type="submit" id="submit" disabled class="btn btn-primary mb">Wymień</button>
                    <?php echo form_close(); ?>
                </div>
            </div>

            <?php
        }
    }
    ?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>