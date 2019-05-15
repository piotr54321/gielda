<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Uzytkownik_historia extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();

    }

    /**
     * Zwraca tablicę z historią akcji użytkownika/użytkowników
     * @param int $id_uzytkownika
     * @param bool $p
     * @param int $page
     * @param int $limit
     * @return array|bool
     */
    public function uzytkownik_akcje($id_uzytkownika=0, $p=false, $page=0, $limit=10){
        if($page>0) $page--;
        $dane=array();

        $this->db->select('historia_transakcji_akcje.id_uzytkownika, historia_transakcji_akcje.id_spolki, historia_transakcji_akcje.cena_kupna, historia_transakcji_akcje.czas, historia_transakcji_akcje.ilosc, indeks_gieldowy.nazwa as nazwa_indeksu, spolki_gieldowe.nazwa as nazwa_spolki, uzytkownicy.nazwa_uzytkownika');
        $this->db->from('historia_transakcji_akcje');
        $this->db->join('spolki_gieldowe','spolki_gieldowe.id=historia_transakcji_akcje.id_spolki');
        $this->db->join('indeks_gieldowy','indeks_gieldowy.id=spolki_gieldowe.id_indeks_gieldowy');
        $this->db->join('uzytkownicy', 'uzytkownicy.id=historia_transakcji_akcje.id_uzytkownika');
        if($id_uzytkownika != 0){
            $this->db->where('historia_transakcji_akcje.id_uzytkownika', $id_uzytkownika);
        }
        $dane['liczba']=$this->db->count_all_results('',false);
        if($p==true){
            $this->db->limit($limit, $page*$limit);
        }
        $q=$this->db->get();
        if($q->num_rows()>0){
            $dane['akcje']=$q->result_array();
            return $dane;
        }else{
            return FALSE;
        }
    }

    /**
     * Zwraca tablicę z historią logowań użytkownika/użytkowników
     * @param $id_uzytkownika
     * @param bool $p
     * @param int $page
     * @param int $limit
     * @return array|bool
     */
    public function uzytkownik_logowania($id_uzytkownika, $p=false, $page=0, $limit=10){
        if($page>0) $page--;
        $dane=array();

        //
        //$this->db->count_all_results('historia_logowan');
        $this->db->select('historia_logowan.czas, historia_logowan.adres_ip, historia_logowan.przegladarka, uzytkownicy.nazwa_uzytkownika');
        $this->db->from('historia_logowan');
        $this->db->join('uzytkownicy','historia_logowan.id_uzytkownika=uzytkownicy.id');
        if($id_uzytkownika !=0 )$this->db->where('historia_logowan.id_uzytkownika', $id_uzytkownika);
        //;$dane['liczba']=$this->db->count_all_results('historia_logowan', false);
        $dane['liczba']=$this->db->count_all_results('',false);
        //var_dump($dane['liczba']);
        $this->db->order_by('historia_logowan.id','desc');
        if($p==true){
            $this->db->limit($limit, $page*$limit);
        }
        $q=$this->db->get();

        if($q->num_rows() > 0){
            //$dane['liczba']=$q->num_rows();
            $dane['query']=$q->result_array();
            return $dane;
        }else{
            return FALSE;
        }
    }

    /**
     * Zwraca tablicę z walut akcji użytkownika/użytkowników
     * @param $id_uzytkownika
     * @param bool $p
     * @param int $page
     * @param int $limit
     * @return array|bool
     */
    public function uzytkownik_waluty($id_uzytkownika, $p=false, $page=0, $limit=10){
        if($page>0) $page--;
        $dane=array();
        $this->db->select('uzytkownicy.nazwa_uzytkownika, historia_transakcji_waluty.id_uzytkownika, historia_transakcji_waluty.id_waluty, historia_transakcji_waluty.czas, historia_transakcji_waluty.ilosc, waluty.nazwa');
        $this->db->from('historia_transakcji_waluty');
        $this->db->join('waluty', 'historia_transakcji_waluty.id_waluty=waluty.id');
        $this->db->join('uzytkownicy','historia_transakcji_waluty.id_uzytkownika=uzytkownicy.id');
        if($id_uzytkownika !=0 )$this->db->where('historia_transakcji_waluty.id_uzytkownika', $id_uzytkownika);
        $dane['liczba']=$this->db->count_all_results('',false);
        $this->db->order_by('historia_transakcji_waluty.id','desc');
        if($p==true){
            $this->db->limit($limit, $page*$limit);
        }
        $q=$this->db->get();
        //var_dump($p);
        if($q->num_rows() > 0){
            $dane['waluty']=$q->result_array();
            return $dane;
        }else{
            return FALSE;
        }
    }
}