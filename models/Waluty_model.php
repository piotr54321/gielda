<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Waluty_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('portfel_model');
    }
    //SELECT uzytkownicy.id, uzytkownicy.nazwa_uzytkownika, uzytkownicy_portfel.id_waluty, uzytkownicy_portfel.ilosc, waluty.nazwa FROM (((waluty INNER JOIN uzytkownicy_portfel ON waluty.id=uzytkownicy_portfel.id_waluty) INNER JOIN panstwa ON panstwa.id=waluty.id_panstwo_wydajace) INNER JOIN uzytkownicy ON uzytkownicy_portfel.id_uzytkownika=uzytkownicy.id)

    /**
     * Zwraca tablicę notowań walut
     * @return array|bool
     */
    public function waluty_notowania(){
        $this->db->select('waluty_notowania.id as id, w1.nazwa as wa1, w2.nazwa as wa2, waluty_notowania.cena, waluty_notowania.czas, waluty_notowania.id_waluta_pierwsza as id_waluta1, waluty_notowania.id_waluta_druga as id_waluta2');
        $this->db->from('waluty_notowania');
        $this->db->join('waluty as w1', 'w1.id=waluty_notowania.id_waluta_pierwsza');
        $this->db->join('waluty as w2', 'w2.id=waluty_notowania.id_waluta_druga');
        $this->db->where('waluty_notowania.id IN (SELECT max(waluty_notowania.id) AS id FROM waluty_notowania JOIN waluty AS w1 ON waluty_notowania.id_waluta_pierwsza=w1.id JOIN waluty AS w2 ON waluty_notowania.id_waluta_druga=w2.id GROUP BY w1.nazwa, w2.nazwa)');
        
        $this->db->group_by('wa1, wa2');
        $this->db->order_by('wa1, wa2');
        //$this->db->limit(1);
        //$query = $this->db->get();

        $q=$this->db->get();
        //var_dump($q);
        if ($q->num_rows() > 0) {
            //return $q->result();
            //var_dump($q->result());
            return $q->result_array();
            //return true;
        } else {
            return false;
        }

    }

    /**
     * Oblicza aktualny kurs dla danej pary walut
     * @param $id_waluta1
     * @param $id_waluta2
     * @return array|bool
     */
    public function aktualny_kurs_waluty($id_waluta1, $id_waluta2){
        $this->db->select('waluty_notowania.id as id, w1.nazwa as wa1, w2.nazwa as wa2, waluty_notowania.cena, waluty_notowania.czas, waluty_notowania.id_waluta_pierwsza as id_waluta1, waluty_notowania.id_waluta_druga as id_waluta2');
        $this->db->from('waluty_notowania');
        $this->db->join('waluty as w1', 'w1.id=waluty_notowania.id_waluta_pierwsza');
        $this->db->join('waluty as w2', 'w2.id=waluty_notowania.id_waluta_druga');
        $this->db->where('waluty_notowania.id IN (SELECT max(waluty_notowania.id) AS id FROM waluty_notowania JOIN waluty AS w1 ON waluty_notowania.id_waluta_pierwsza=w1.id JOIN waluty AS w2 ON waluty_notowania.id_waluta_druga=w2.id GROUP BY w1.nazwa, w2.nazwa)');
        $this->db->where('waluty_notowania.id_waluta_pierwsza', $id_waluta1);
        $this->db->where('waluty_notowania.id_waluta_druga',$id_waluta2);
        $this->db->group_by('wa1, wa2');
        $q=$this->db->get();
        if($q->num_rows()>0){
            return $q->result_array();
        }else{
            return FALSE;
        }
    }

    /**
     * Wymienia walutę użytkownikowi jeśli wprowadzone dane są poprawne
     * @param $id_uzytkownika
     * @param $id_waluta1
     * @param $id_waluta2
     * @param $ilosc_do_pozyskania
     * @return bool
     */
    public function wymien_walute($id_uzytkownika, $id_waluta1, $id_waluta2, $ilosc_do_pozyskania){

        //$kurs_waluty=$this->waluty_model->aktualny_kurs_waluty($id_waluta1, $id_waluta2);
        $kurs_waluty2=$this->waluty_model->aktualny_kurs_waluty($id_waluta2, $id_waluta1);
        $posiadana_ilosc=$this->portfel_model->uzytkownik_waluty_ilosc($id_uzytkownika, $id_waluta1)[0]['ilosc'];
        if($ilosc_do_pozyskania<0 || ($ilosc_do_pozyskania*$kurs_waluty2[0]['cena'] > $posiadana_ilosc) || !is_numeric($id_waluta1) || !is_numeric($id_waluta2)){
            return FALSE;
        }else{
            $cena= $kurs_waluty2[0]['cena']*$ilosc_do_pozyskania;

            //$this->db->trans_begin();

            if($this->portfel_model->uzytkownik_waluty_sprzedaj($id_uzytkownika, $id_waluta1, $cena, 0)) {
                if(!$this->portfel_model->uzytkownik_waluty_kup($id_uzytkownika, $id_waluta2, $ilosc_do_pozyskania, 0)){
                    return false;
                }
            }


            //$this->db->trans_complete();
            //var_dump($this->db->trans_status());
            //exit;
            return true;
        }
    }

}