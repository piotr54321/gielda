<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rejestracja_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    //SELECT uzytkownicy.id, uzytkownicy.nazwa_uzytkownika, uzytkownicy_portfel.id_waluty, uzytkownicy_portfel.ilosc, waluty.nazwa FROM (((waluty INNER JOIN uzytkownicy_portfel ON waluty.id=uzytkownicy_portfel.id_waluty) INNER JOIN panstwa ON panstwa.id=waluty.id_panstwo_wydajace) INNER JOIN uzytkownicy ON uzytkownicy_portfel.id_uzytkownika=uzytkownicy.id)

    /**
     * Generuje hash rejestracji
     * @return string
     */
    public function generuj_hash(){
        return $hash=uniqid(time(), true);
    }

    /**
     * Sprawdza czy podane dane jak hash się zgadzają z adresem mail
     * @param $hash
     * @param int $email
     * @return bool
     */
    public function sprawdz_hash($hash, $email=0){
        //var_dump($email);
        $this->db->select('uzytkownicy.register_hash');
        $this->db->from('uzytkownicy');
        $this->db->where('uzytkownicy.register_hash', $hash);
        if(!empty($email)){
            $this->db->where('uzytkownicy.email', $email);
        }
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            //return $query->result();
            //var_dump($query->result_array());
            return TRUE;
        } else {
            //echo 'xd';
            return FALSE;
        }
    }

    /**
     * Ustawia hasło jeśli wprowadzone dane są poprawne
     * @param $dane
     * @return bool
     */
    public function ustaw_haslo($dane){
        if($this->sprawdz_hash($dane['inputHash'], $dane['inputEmail'])) {
            if ($dane['inputPassword'] == $dane['inputPassword2']) {
                $haslo = password_hash($dane['inputPassword'], PASSWORD_DEFAULT);
                $this->db->set('uzytkownicy.haslo', $haslo);
                $this->db->set('uzytkownicy.register_hash', '');
                $this->db->where('uzytkownicy.email', $dane['inputEmail']);
                $this->db->where('uzytkownicy.register_hash', $dane['inputHash']);
                $this->db->update('uzytkownicy');
                if($this->db->affected_rows()>0){
                    return TRUE;
                }else{
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }

}