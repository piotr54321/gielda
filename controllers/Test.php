<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends MY_Controller {
    function __construct(){
        parent::__construct();
        //$this->czy_zalogowano();
        //if($this->acl_library->funkcjav2() == false) show_404();
        $this->load->model('test_model');
    }

    /**
     *Wyświetla listę testowych akcji
     */
    public function index()
    {
        echo'Test - strona główna</br>';
        echo anchor('test/dodajuzytkownikow/100', 'Dodaj 100 losowych użytkowników');
        echo '</br>';
        echo anchor('test/doladujkonta/10000', 'Doladuj konta użytkownikom');
        echo '</br>';
        echo anchor('test/wymienwaluty/1000/1000', 'Wymień waluty użytkownikom');
        echo '</br>';
        echo anchor('test/kupsprzedajakcje/100/', 'Kup/Sprzedaj akcje użytkownikom');
        echo '</br>';
        echo anchor('test/akcjeczysc/', 'Czyszczenie akcji');
        echo '</br>';
        echo anchor('test/walutyczysc/', 'Czyszczenie walut');
    }

    /**
     * Dodaje losowych użytkowników
     * @param int $ilosc - ilość użytkowników
     */
    public function dodajuzytkownikow($ilosc=1){
        var_dump($this->test_model->dodajlosowegouzytkownika($ilosc));
    }

    /**
     * Doładowywuje środki każdemu użytkownikowi
     * @param int $ilosc - ilość środków która trafi do każdego użytkownika
     */
    public function doladujkonta($ilosc=10000){
        if($this->test_model->doladujkonta($ilosc)){
            echo 'Powiodło się';
        }else{
            echo 'Błąd';
        }
    }


    /**
     *Losowo wymienia waluty użytkownikom
     * @param int $ilosc_uzytkownikow - ilość użytkowników którym chcemy wymienić waluty
     * @param int $iloscwalut - ilość środków którą chcemy wymienić
     */
    public function wymienwaluty($ilosc_uzytkownikow=100, $iloscwalut=100){
        if($this->test_model->wymienwaluty($ilosc_uzytkownikow,$iloscwalut)){
            echo 'Powiodło się';
        }else{
            echo 'Błąd';
        }
    }

    /**
     * Losowo kupuje/sprzedaje akcje losowym użytkownikom
     * @param int $ilosc - przybliżona ilość kupna + sprzedaży akcji
     */
    public function kupsprzedajakcje($ilosc=100){
        if($this->test_model->kupsprzedaj($ilosc)){
            echo 'Powiodło się';
        }else{
            echo 'Błąd';
        }
    }

    /**
     *Czyści tabele z akcjami
     */
    public function akcjeczysc(){
        if($this->test_model->akcjeczysc()){
            echo 'Powiodło się';
        }else{
            echo 'Błąd';
        }
    }

    /**
     *Czyści tabele z walutami
     */
    public function walutyczysc(){
        if($this->test_model->walutyczysc()){
            echo 'Powiodło się';
        }else{
            echo 'Błąd';
        }
    }


}
