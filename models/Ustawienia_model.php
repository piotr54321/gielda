<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ustawienia_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * @return int
     */
    public function ustawienia_adresy(){
        return $this->db->count_all('uzytkownicy_adres');
    }

    /**
     * @return int
     */
    public function ustawienia_danelogoso(){
    return $this->db->count_all('uzytkownicy');
}

    /**
     * Zwraca tablicę z listą adresów dla danego użytkownika
     * @param int $id_uzytkownika
     * @return array|bool
     */
    public function lista_adresow($id_uzytkownika=0){
        $this->db->select('uzytkownicy_adres.panstwo_zamieszkania, uzytkownicy_adres.kod_pocztowy, uzytkownicy_adres.miejscowosc, uzytkownicy_adres.numer_domu, uzytkownicy_adres.numer_lokalu ');
        $this->db->from('uzytkownicy_adres');
        if($id_uzytkownika !=0){
            $this->db->where('uzytkownicy_adres.uzytkownicy_id',$id_uzytkownika);
        }
        $this->db->order_by('uzytkownicy_adres.panstwo_zamieszkania');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            //return $query->result();
            return $query->result_array();
        } else {
            return false;
        }
    }

    /**
     * Aktualizuje dany adres dla danego użytkownika
     * @param $id_uzytkownika
     * @param $id_adresu
     * @param $danezformularza
     * @return bool
     */
    public function aktualizuj_adres($id_uzytkownika, $id_adresu, $danezformularza)
    {

        $array = array(
            'panstwo_zamieszkania' => $danezformularza['panstwo'],
            'kod_pocztowy' => $danezformularza['kod'],
            'miejscowosc' => $danezformularza['miasto'],
            'numer_domu' => $danezformularza['numer_domu'],
            'numer_lokalu' => $danezformularza['numer_lokalu']

        );


        $this->db->set($array);
        $this->db->where('uzytkownicy_adres.id', $id_adresu);
        $this->db->where('uzytkownicy_adres.uzytkownicy_id', $id_uzytkownika);
        $this->db->update('uzytkownicy_adres');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Zwraca tablicę z danymi logowania dla danego użytkownika
     * @param int $id
     * @return array|bool
     */
    public function danelogo($id=0){
            $this->db->select('uzytkownicy.typ_uzytkownika, uzytkownicy.nazwa_uzytkownika, uzytkownicy.email');
            $this->db->from('uzytkownicy');
            if($id !=0){
                $this->db->where('uzytkownicy.id',$id);
            }
            $this->db->order_by('uzytkownicy.id');

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return false;
            }
    }

    /**
     * Aktualizuje dane logowania dla danego użytkownika
     * @param $id
     * @param $dane
     * @return bool
     */
    public function aktalizuj_danelogo($id, $dane){

        //var_dump($dane);
        $array = array(
            'nazwa_uzytkownika' => $dane['nazwa_uzytkownika'],
            'email' => $dane['email']
        );

        $this->db->set($array);
        $this->db->where('uzytkownicy.id', $id);
        $this->db->update('uzytkownicy');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Zwraca tablicę danych osobowych danego użytkownika
     * @param int $id
     * @return array|bool
     */
    public function ustawienie_daneosob($id=0){
        $this->db->select('uzytkownicy.imie, uzytkownicy.nazwisko');
        $this->db->from('uzytkownicy');
        if($id !=0){
            $this->db->where('uzytkownicy.id',$id);
        }
        $this->db->order_by('uzytkownicy.imie');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    /**
     * Aktualizuje dane osobowe danego użytkownika
     * @param $id
     * @param $dane
     * @return bool
     */
    public function aktualizuj_daneosob($id, $dane){

        $array = array(
            'imie' => $dane['imie'],
            'nazwisko' => $dane['nazwisko']
        );


        $this->db->set($array);
        $this->db->where('uzytkownicy.id', $id);
        $this->db->update('uzytkownicy');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Dodaje nowy adres ]
     * @param $dane
     * @return bool
     */
    public function dodaj_adres($dane){
        if($dane['adres_glowny']==1){
            $this->db->set('adres_glowny', 0);
        }
        $this->db->insert('uzytkownicy_adres',$dane);
        if($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Zwraca tablicę z listą adresów danego użytkownika
     * @param $id_uzytkownika
     * @return array|bool
     */
    public function adresy_uzytkownika($id_uzytkownika, $id_adresu=0){
        $this->db->select('uzytkownicy_adres.id as id_adresu, uzytkownicy_adres.adres_glowny, panstwa.name, uzytkownicy_adres.kod_pocztowy, uzytkownicy_adres.miejscowosc, uzytkownicy_adres.numer_domu, uzytkownicy_adres.numer_lokalu');
        $this->db->from('uzytkownicy_adres');
        $this->db->join('panstwa', 'panstwa.id=uzytkownicy_adres.panstwo_zamieszkania');
        $this->db->where('uzytkownicy_adres.uzytkownicy_id', $id_uzytkownika);
        if($id_adresu != 0) $this->db->where('id_adresu', $id_adresu);
        $q = $this->db->get();
        if($q->num_rows()>0){
            return $q->result_array();
        }else{
            return false;
        }
    }

    public function ustaw_adres_glowny($id_uzytkownika, $id_adresu){
        $this->db->trans_begin();
        $this->db->set('adres_glowny', 0);
        $this->db->where('uzytkownicy_id', $id_uzytkownika);
        $this->db->update('uzytkownicy_adres');

        $this->db->set('adres_glowny', 1);
        $this->db->where('uzytkownicy_id', $id_uzytkownika);
        $this->db->where('id', $id_adresu);
        $this->db->update('uzytkownicy_adres');
        $this->db->trans_complete();
        if($this->db->trans_status()>0){
            return true;
        }else{
            return false;
        }
    }
}