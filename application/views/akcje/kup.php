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
                    function calc2(){
                        var cena = eval("<?php echo $item['cena']; ?>");
                        var ilosc_kupionych= eval("<?php echo $wykupione_akcje; ?>");
                        var ilosc = eval(document.getElementById('iloscAkcji').value);
                        var dostepne_akcje = eval("<?php echo $dostepne_akcje; ?>");

                        var iloscx = eval(ilosc+ilosc_kupionych);
                        if(ilosc < 1){
                            document.getElementById('total').value = 'Wprowadzono zbyt małą ilość';
                            document.getElementById("submit").disabled = true;
                        }else if(ilosc  > dostepne_akcje){
                            document.getElementById('total').value = 'Wprowadzono zbyt dużą ilość';
                            document.getElementById("submit").disabled = true;
                        }else{
                            var procent = 0.01;
                            amount1 = ilosc_kupionych * cena * Math.pow(1.0 + procent, ilosc_kupionych);
                            if (isNaN(amount1)) {
                                amount1 = 0;
                            }
                            amount2 = iloscx * cena * Math.pow(1.0 + procent, iloscx);
                            //lert(amount1);
                            //alert(amount2);
                            var amount = eval(amount2 - amount1);
                            document.getElementById('total').value = amount.toPrecision(20);
                            document.getElementById("submit").disabled = false;
                        }
                    }

                    function calc() {
                        var site_url    = "<?php echo base_url() ;?>" ;
                        var ilosc = eval(document.getElementById('iloscAkcji').value);
                        var id_spolki = eval("<?php echo $id_spolki; ?>");
                        var posiadane_srodki = eval("<?php echo $posiadane_srodki; ?>");
                        $.ajax({
                            type: "POST",
                            url: site_url + "/akcje/cena_akcji/" + id_spolki + "/" + ilosc,
                            dataType:"json",
                            success:function(data){
                                if(data["cena"]==false || data["cena"]==0) {
                                    document.getElementById("total").value = "wprowadzono niepoprawną wartość";
                                    document.getElementById("submit").disabled = true;
                                }else if(data["cena"]>posiadane_srodki){
                                    document.getElementById("total").value = "Nie posiadasz wystarczających środków";
                                    document.getElementById("submit").disabled = true;
                                }else {
                                    document.getElementById("total").value = Number(data["cena"]).toFixed(2) +' zł';
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
                        <p class="card-text">Ilość dostępnych akcji: <?= $dostepne_akcje;?></p>
                        <?php echo form_open('akcje/kup_check/'. $this->portfel_model->id_transakcji_tworz($this->session->id_uzytkownika, 2)); ?>


                            <label class="sr-only" for="iloscWaluty">Ilość</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Ilość którą chcesz kupić</div>
                                </div>
                                <input type="text"name="iloscAkcji" value="<?php echo set_value('iloscAkcji'); ?>" class="form-control" id="iloscAkcji" placeholder="Ilość" onChange="calc()">
                            </div>
                             <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Cena kupna: </div>
                                </div>
                                <input type="text" class="form-control" placeholder="0 Zł" name="total" id="total" disabled>
                            </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Posiadane środki: </div>
                            </div>
                            <input type="text" class="form-control" placeholder="<?= $posiadane_srodki; ?>" name="total2" id="total2" disabled>
                        </div>

                        <input type="hidden" name="idSpolki" value="<?= $item['id_spolki'] ?>">
                        <button type="submit" id="submit" disabled class="btn btn-primary mb">kup</button>
                        <?php echo form_close(); ?>
                    </div>
                </div>

                <?php
            }
        }
    ?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>