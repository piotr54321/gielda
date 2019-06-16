<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = new PDO('mysql:host=localhost;dbname=gielda', 'test', 'Jozef987654321');

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function kurs_waluty($waluta_symbol1, $waluta_symbol2)
{
    global $db;
    //$jsondata = file_get_contents('https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency='.$waluta_symbol1.'&to_currency='.$waluta_symbol2.'&apikey=sad');
    //$jsondata = file_get_contents('https://www.amdoren.com/api/currency.php?api_key=eQFYDsYiT9DtpGi3rAMmUXqfgE7T9j&from='.$waluta_symbol1.'&to='.$waluta_symbol2.'');
    $jsondata = file_get_contents('https://globalcurrencies.xignite.com/xGlobalCurrencies.json/ConvertRealTimeValue?From='.$waluta_symbol1.'&To='.$waluta_symbol2.'&Amount=1&_fields=FromCurrencySymbol,ToCurrencySymbol,Rate&_token=FBD1AD179BE5412DBCFAE5BF7A2F8FF2');
    //echo $jsondata;
    $dataarray = json_decode($jsondata, true);
    //var_dump($dataarray);

    //print_r($dataarray["Realtime Currency Exchange Rate"]["1. From_Currency Code"]);
    //print_r($dataarray["Realtime Currency Exchange Rate"]["2. From_Currency Name"]);
    //print_r($dataarray["Realtime Currency Exchange Rate"]["3. To_Currency Code"]);
    //print_r($dataarray["Realtime Currency Exchange Rate"]["4. To_Currency Name"]);
    //print_r($dataarray["Realtime Currency Exchange Rate"]["5. Exchange Rate"]);
    //print_r($dataarray["Realtime Currency Exchange Rate"]["6. Last Refreshed"]);
    //print_r($dataarray["Realtime Currency Exchange Rate"]["7. Time Zone"]);

    /*$db=new mysqli('localhost', 'root','Jozef987654321', 'gielda');
    //$db->query('(SELECT id FROM waluty WHERE nazwa='.$dataarray["Realtime Currency Exchange Rate"]["1. From_Currency Code"].')');
    //$q=$db->query("INSERT INTO waluty_notowania (id_waluta_pierwsza, id_waluta_druga, cena) VALUES ((SELECT id FROM waluty WHERE nazwa='{$dataarray["Realtime Currency Exchange Rate"]["1. From_Currency Code"]}';),(SELECT id FROM waluty WHERE nazwa='{$dataarray["Realtime Currency Exchange Rate"]["3. To_Currency Code"]}';),'{$dataarray["Realtime Currency Exchange Rate"]["5. Exchange Rate"]}');");
    $q=$db->query("INSERT INTO waluty_notowania (id_waluta_pierwsza, id_waluta_druga, cena) VALUES ((SELECT id FROM waluty WHERE nazwa='EUR'),(SELECT id FROM waluty WHERE nazwa='PLN'),'{$dataarray["Realtime Currency Exchange Rate"]["5. Exchange Rate"]}');");
    if(!$q){
        echo"nie";
    }*/


    //$statement = $db->prepare("INSERT INTO tabela (imie, nazwisko) VALUES (:imie, :nazwisko)");
    $statement = $db->prepare("INSERT INTO waluty_notowania (id_waluta_pierwsza, id_waluta_druga, cena) VALUES ((SELECT id FROM waluty WHERE nazwa=:waluta1),(SELECT id FROM waluty WHERE nazwa=:waluta2),:kurs)");
    //$statement = $db->prepare("INSERT INTO waluty_notowania (cena) VALUES (:kurs)");
    if (!$statement) {
        var_dump($db->errorInfo());
        die();
    }

    $statement->bindValue(':waluta1', $waluta_symbol1, PDO::PARAM_STR);
    $statement->bindValue(':waluta2', $waluta_symbol2, PDO::PARAM_STR);
    //$statement->bindValue(':kurs', $dataarray["Realtime Currency Exchange Rate"]["5. Exchange Rate"], PDO::PARAM_STR);
    $statement->bindValue(':kurs', $dataarray["Rate"], PDO::PARAM_STR);
    $statement->execute();
    var_dump($dataarray);
}

$tablica=array('PLN', 'EUR', 'CHF', 'USD', 'RUB');

for($i=0;$i<=4;$i++){
    for($j=0;$j<=4;$j++){
        if($tablica[$i] != $tablica[$j]){
            kurs_waluty($tablica[$i], $tablica[$j]);
        }
    }
}

//kurs_waluty('PLN', 'USD');