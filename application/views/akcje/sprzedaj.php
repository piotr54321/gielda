<div class="container-fluid">
    <?php
    if(!($spolka_gieldowa)){
        echo "<div class='alert alert-info' role='alert'>";
        echo 'Spółka nie istnieje';
        echo "</div>";
    }elseif(!$dostepne_akcje){
        echo "<div class='alert alert-info' role='alert'>";
        echo 'Brak dostępnych akcji spółki';
        echo "</div>";
    }else{
        foreach ($spolka_gieldowa as $item) {

            //var_dump($wykupione_akcje);
            if($wykupione_akcje==NULL) $wykupione_akcje=0;
            ?>
            <br/>
            <script>

                function calc() {
                    var site_url    = "<?php echo base_url() ;?>" ;
                    var ilosc = eval(document.getElementById('iloscAkcji').value);
                    var id_spolki = eval("<?php echo $id_spolki; ?>");
                    var id_uzytkownika = eval("<?php echo $this->session->id_uzytkownika; ?>");
                    $.ajax({
                        type: "POST",
                        url: site_url + "/akcje/cena_akcji_sprzedaz/" + id_spolki + "/" + ilosc + "/" + id_uzytkownika,
                        dataType:"json",
                        success:function(data){
                            if(data["cena"]==false || data["cena"]==0){
                                document.getElementById("total").value = "wprowadzono niepoprawną wartość" ;
                                document.getElementById("submit").disabled = true;
                            }else {
                                document.getElementById("total").value = data["cena"] +' zł';
                                document.getElementById("submit").disabled = false;
                            }
                        },
                        error: function(data){
                            document.getElementById("total").value = "" ;
                            document.getElementById("submit").disabled = true;
                        }
                    });
                }
            </script>

            <div class="card">
                <div class="card-header">
                    Indeks giełdowy: <?= $item['nazwa_indeksu']; ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Nazwa spółki <?= $item['nazwa_spolki']; ?></h5>
                    <p class="card-text">Ilość Twoich akcji: <?= $dostepne_akcje;?></p>
                    <?php echo form_open('akcje/sprzedaj_check/'. $this->portfel_model->id_transakcji_tworz($this->session->id_uzytkownika, 2)); ?>


                    <label class="sr-only" for="iloscWaluty">Ilość</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Ilość którą chcesz sprzedać</div>
                        </div>
                        <input type="text"name="iloscAkcji" value="<?php echo set_value('iloscAkcji'); ?>" class="form-control" id="iloscAkcji" placeholder="Ilość" onChange="calc()">
                    </div>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Zarobisz na sprzedaży: </div>
                        </div>
                        <input type="text" class="form-control" placeholder="0 Zł" name="total" id="total" disabled>
                    </div>

                    <input type="hidden" name="idSpolki" value="<?= $item['id_spolki'] ?>">
                    <button type="submit" id="submit" disabled class="btn btn-primary mb">Sprzedaj</button>
                    <?php echo form_close(); ?>
                </div>
            </div>

            <?php
        }
    }
    ?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>