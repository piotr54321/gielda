<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Uzytkownicy_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Zwraca liczbę użytkowników
     * @param int $czas_dostepu
     * @return int
     */
    public function liczba_uzytkownikow($czas_dostepu=0){
        if($czas_dostepu !=0){
            $this->db->where('czas_dostepu>', 60*$czas_dostepu);
            $this->db->get('uzytkownicy');
            return $this->db->count_all_results();
        }else{
            return $this->db->count_all('uzytkownicy');
        }
    }

    /**
     * zwraca tablicę z danymi użytkowników
     * @param bool $p
     * @param int $page
     * @param int $limit
     * @param int $czas_dostepu
     * @return array|bool
     */
    public function lista_uzytkownikow($p=false, $page=0, $limit=10, $czas_dostepu=0){
        if($page>0) $page--;
        $this->db->select('uzytkownicy.id, uzytkownicy.nazwa_uzytkownika');
        $this->db->from('uzytkownicy');
        if($czas_dostepu != 0){
            $this->db->where('czas_dostepu >', (time()-(60*$czas_dostepu))); //10 minut
        }
        //$this->db->join('strona_role', 'uzytkownicy.typ_uzytkownika=strona_role.typ_uzytkownika');
        $this->db->order_by('uzytkownicy.nazwa_uzytkownika');
        //if($p > 0){
        $this->db->limit($limit, $page*$limit);
        //$this->db->limit($page, $limit);
        //$this->db->limit('', '')
        //}
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            //return $query->result();
            return $query->result_array();
        } else {
            return false;
        }
    }
}