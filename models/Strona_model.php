<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Strona_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    //SELECT uzytkownicy.id, uzytkownicy.nazwa_uzytkownika, uzytkownicy_portfel.id_waluty, uzytkownicy_portfel.ilosc, waluty.nazwa FROM (((waluty INNER JOIN uzytkownicy_portfel ON waluty.id=uzytkownicy_portfel.id_waluty) INNER JOIN panstwa ON panstwa.id=waluty.id_panstwo_wydajace) INNER JOIN uzytkownicy ON uzytkownicy_portfel.id_uzytkownika=uzytkownicy.id)

    /**
     * Wyświetla listę państw
     * @return array|bool
     */
    public function panstwa(){
        $this->db->select('panstwa.name, panstwa.id');
        $this->db->from('panstwa');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            //return $query->result();
            return $query->result_array();
        } else {
            return false;
        }

    }

    /**
     * Wyświetla listę roli na stronie
     * @return array|bool
     */
    public function role_uzytkownikow(){
        $this->db->select('strona_role.nazwa_typu, strona_role.typ_uzytkownika');
        $this->db->from('strona_role');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }else{
            return false;
        }
    }

}