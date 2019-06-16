<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * @param int $dlugosc
     * @return bool|string
     */
    public function losowyciag($dlugosc=1){
        return substr(md5(uniqid(rand(), true)),0,$dlugosc);
    }

    public function losowynumer($dlugosc=1){
        $dlugoscrand = 9;
        for($i=1;$i<$dlugosc;$i++){
            $dlugoscrand = ($dlugoscrand*10)+9;
        }
        //var_dump($dlugoscrand);
        return rand(0, $dlugoscrand);
    }

    public function losowytekst($dlugosc=1){
        $text = '';
        for($i=0;$i<$dlugosc;$i++){
            $text .= chr(rand(65,90));
        }

        return trim($text);
    }

    public function losowadata(){
        $time = rand(0, time());
        return date("Y-m-d H:i:s",$time);
    }

    public function losoweidpanstwo(){
        $this->db->order_by('rand()');
        $this->db->limit(1);
        $query = $this->db->get('panstwa');

        $q=$query->result_array();
        return $q[0]['id'];
    }

    public function dodajlosowegouzytkownika($ilosc=1){
        for($i=0;$i<$ilosc;$i++) {
            $dane = array(
                'FormImie' => $this->losowytekst(7),
                'FormNazwisko' => $this->losowytekst(6),
                'FormNazwaUzytkownika' => $this->losowyciag(7),
                'FormDataUrodzenia' => $this->losowadata(),
                'FormEmail' => $this->losowyciag(4) . '@' . $this->losowytekst(3) . '.' . $this->losowytekst(2),
                'adres_glowny' => 1,
                'FormPanstwo' => $this->losoweidpanstwo(),
                'FormKodPocztowy' => $this->losowynumer(5),
                'FormMiejscowosc' => $this->losowytekst(7),
                'FormNrDomu' => $this->losowyciag(3),
                'FormNrLokalu' => $this->losowynumer(2),
                'FormTypUzytkownika' => rand(1, 3)
            );

            $this->uzytkownik_model->uzytkownik_dodaj($dane, 0, 1);

        }
        return TRUE;
    }

    public function iduzytkownikow($limit=0){
        $this->db->select('id');
        $this->db->from('uzytkownicy');
        $this->db->order_by('id','RANDOM');
        if($limit != 0){
            $this->db->limit($limit);
        }
        $q = $this->db->get();
        $q =  $q->result_array();
        return $q;
    }

    /**
     * @param int $limit
     * @return array|CI_DB_result
     */
    public function idwalut($limit=0){
        /*$this->db->select('id');
        $this->db->from*/


        $this->db->select('id');
        $this->db->from('waluty');
        //$this->db->where('id !=', NULL);
        $this->db->order_by('id','RANDOM');
        if($limit != 0){
            $this->db->limit($limit);
        }
        $q = $this->db->get();
        $q =  $q->result_array();
        return $q;
    }


    public function idwalutUzytkownika($id_uzytkownika,$bezid_waluty,$limit=0){
        $this->db->select('id_waluty');
        $this->db->from('uzytkownicy_portfel');
        $this->db->where('id_uzytkownika', $id_uzytkownika);
        $this->db->where('ilosc!=', 0);
        $this->db->where('id_waluty !=', $bezid_waluty);
        $this->db->order_by('id_waluty','RANDOM');
        if($limit != 0){
            $this->db->limit($limit);
        }
        $q = $this->db->get();
        if($q->num_rows() > 0) {
            $q = $q->result_array();
            return $q;
        }else{
            return false;
        }
    }

    /**
     * @param int $ilosc
     * @return bool
     */
    public function doladujkonta($ilosc=10000){
        foreach ($this->iduzytkownikow() as $item) {
            $this->portfel_model->uzytkownik_waluty_kup($item['id'], 6, $ilosc);
        }
        return TRUE;
    }


    /**
     * @param int $ilosc_uzytkownikow
     * @param int $iloscwalut
     * @return bool
     */
    public function wymienwaluty($ilosc_uzytkownikow=100, $iloscwalut=100){

        $ilosc = 0;
        foreach ($this->iduzytkownikow($ilosc_uzytkownikow) as $idUzytkownika){
            $idWalut2=$this->idwalut(1)[0]['id'];
            //var_dump($idWalut2);
            //var_dump($idUzytkownika['id']);
            $idWaluty1=$this->idwalutUzytkownika($idUzytkownika['id'],$idWalut2, 1);

            //var_dump($idWaluty1[0]['id_waluty']);
           // if()
            //var_dump($idWaluty1[0]['id_waluty']);
            if($this->waluty_model->wymien_walute($idUzytkownika['id'], $idWaluty1[0]['id_waluty'], $idWalut2, $iloscwalut)){
                $ilosc++;
            }
        }
        echo 'Wymieniono: '.$ilosc;
        return true;
    }

    /**
     * @param int $limit
     * @return array|CI_DB_result
     */
    public function idSpolki($limit=0){
        $this->db->select('id');
        $this->db->from('spolki_gieldowe');
        //$this->db->where('id !=', NULL);
        $this->db->order_by('rand(1)');
        if($limit != 0){
            $this->db->limit($limit);
        }
        $q = $this->db->get();
        $q =  $q->result_array();
        return $q;
    }

    public function idAkcjaUzytkownik($limit=0, $id_spolki=0){
        $this->db->select('id, id_uzytkownika, id_spolki, ilosc');
        $this->db->from('uzytkownicy_akcje');
        if($id_spolki != 0){
           $this->db->where('id_spolki',$id_spolki);
        }
        $this->db->where('ilosc>', 1);
        //$this->db->where('id !=', NULL);
        $this->db->order_by('rand(1)');
        if($limit != 0){
            $this->db->limit($limit);
        }
        $q = $this->db->get();
        $q =  $q->result_array();
        return $q;
    }

    public function idPortfelUzytkownika(){
        $this->db->select('id, id_uzytkownika, ilosc, id_waluty');
        $this->db->from('uzytkownicy_portfel');
        $this->db->where('id_waluty',6 );
        $this->db->where('ilosc!=', 0);
        //$this->db->where('id !=', NULL);
        $this->db->order_by('rand()');
        $this->db->limit(1);
        $q = $this->db->get();
        $q =  $q->result_array();
        //var_dump($q);
        return $q;
    }

    public function kupsprzedaj($ilosc){
        $iloscKupione = 0;
        $iloscSprzedane = 0;

        while($iloscKupione+$iloscSprzedane<=100) {

            foreach ($this->idSpolki($ilosc) as $id_spolki) {
                if (!$this->akcje_model->dostepne_akcje($id_spolki['id'])) {
                    $akcja = 1; // sprzedaj
                } elseif (!$this->akcje_model->wykupione_akcje($id_spolki['id'])) {
                    $akcja = 2; //kup
                } else {
                    $akcja = rand(1, 2);
                }

                if ($akcja == 1) {
                    $idAkcjaUzytkownik = $this->idAkcjaUzytkownik(1, $id_spolki['id']);
                    //var_dump($idAkcjaUzytkownik);
                    $iloscakcji = ((rand(1, 1000) / 1000) * $idAkcjaUzytkownik[0]['ilosc'])+1;
                    //var_dump($idAkcjaUzytkownik);
                    if ($this->akcje_model->sprzedaj($id_spolki['id'], $iloscakcji, $idAkcjaUzytkownik[0]['id_uzytkownika'])) {
                        $iloscSprzedane++;
                    }
                } else {
                    $randPortfel = $this->idPortfelUzytkownika();
                    //var_dump($randPortfel);
                    $iloscakcji = ((rand(1, 10000) / 10000) * $this->akcje_model->dostepne_akcje($id_spolki['id']));
                    //var_dump($iloscakcji);
                    while ($this->akcje_model->calc($id_spolki['id'], $iloscakcji) > $randPortfel[0]['ilosc']) {
                        $iloscakcji /= 2;
                        //var_dump($iloscakcji);
                    }

                    if ($this->akcje_model->kup($id_spolki['id'], $iloscakcji, $randPortfel[0]['id_uzytkownika'])) {
                        $iloscKupione++;
                    }

                }


            }
        }


        echo 'Ilość kupione: '.$iloscKupione.' Ilość sprzedane: '.$iloscSprzedane.' ';
        return true;
    }

    public function akcjeczysc(){
        $this->db->truncate('uzytkownicy_akcje');
        $this->db->truncate('historia_transakcji_akcje');
        $this->db->truncate('notowania_gieldowe_historia');
        return true;
    }

    public function walutyczysc(){
        $this->db->truncate('uzytkownicy_portfel');
        $this->db->truncate('historia_transakcji_waluty');
        return true;
    }
}