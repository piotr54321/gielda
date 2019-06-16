<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['line', 'corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        var chartDiv = document.getElementById('chart_div');
        var notowania_json = <?php echo $notowania_historia_json; ?>;
        //JSON.stringify(notowania_json); //to string
        //alert(notowania_json);
        /*for (var i = 0; i < notowania_json.length; i++){
            var obj = notowania_json[i];
            for (var key in obj){
                var attrName = key;
                var attrValue = obj[key];
            }
        }*/

        //alert(notowania_json);
        var data = new google.visualization.DataTable();
        data.addColumn('number', 'Transakcja');
        data.addColumn('number', "Cena");

        data.addRows(notowania_json);

        var materialOptions = {
            chart: {
                title: 'Notowania '
            },
            //width: 900,
            height: 500,
            series: {
                // Gives each series an axis name that matches the Y-axis below.
                0: {axis: 'Cena'}
            },
            axes: {
                // Adds labels to each axis; they don't have to match the axis names.
                y: {
                    Cena: {label: 'Cena'},
                }
            }
        };

        function drawMaterialChart() {
            var materialChart = new google.charts.Line(chartDiv);
            materialChart.draw(data, materialOptions);
        }

        drawMaterialChart();

    }
</script>

<div class="container-fluid">
    <?php
    if(isset($info)){
        echo "<div class='alert alert-info' role='alert'>";
        echo $info;
        echo "</div>";
    }
    ?>
        <div class="row">
            <div class="col-sm-6">
                <?php
                foreach ($spolka_gieldowa as $item):
                ?>

                <div class="card">
                    <h5 class="card-header">
                        Indeks giełdowy: <?= $item['nazwa_indeksu']; ?></h5>
                    <div class="card-body">
                        <h5 class="card-title">Nazwa spółki <?= $item['nazwa_spolki']; ?></h5>
                        <p class="card-text">Ilość dostępnych akcji: <?= $dostepne_akcje;?></p>
                        <?php if($posiadane_akcje>0){ ?><p class="card-text">Ilość Twoich akcji: <?= $posiadane_akcje;?></p><?php } ?>
                        <?php if($dostepne_akcje>0){ ?><a href="<?= base_url('akcje/kup/'.$item['id_spolki']);?>" class="btn btn-primary">Kup</a><?php } ?>
                        <?php if($posiadane_akcje>0){ ?><a href="<?= base_url('akcje/sprzedaj/'.$item['id_spolki']);?>" class="btn btn-primary">Sprzedaj</a><?php } ?>
                    </div>
                </div>

                <?php
                    endforeach;
                ?>
            </div>
            <div class="col-sm-6">
                <canvas id="myChart" width="600" height="400" class="img-fluid"></canvas>
            </div>
        </div>

</div>

<script>
    var notowania_json_id = <?php echo $notowania_historia_json[2]; ?>;
    var notowania_json_dane = <?php echo $notowania_historia_json[3]; ?>;
    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'line',

        // The data for our dataset
        data: {
            labels: notowania_json_id,
            datasets: [{
                label: "Cena",
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: notowania_json_dane,
            }]
        },

        // Configuration options go here
        options: {
            title: {
                display: true,
                text: 'Notowania spółki'
            },
            responsive: false
        }
    });
</script>