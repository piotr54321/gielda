<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Uzytkownik_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('mail_model');

        $this->load->model('rejestracja_model');
    }
    //SELECT uzytkownicy.id, uzytkownicy.nazwa_uzytkownika, uzytkownicy_portfel.id_waluty, uzytkownicy_portfel.ilosc, waluty.nazwa FROM (((waluty INNER JOIN uzytkownicy_portfel ON waluty.id=uzytkownicy_portfel.id_waluty) INNER JOIN panstwa ON panstwa.id=waluty.id_panstwo_wydajace) INNER JOIN uzytkownicy ON uzytkownicy_portfel.id_uzytkownika=uzytkownicy.id)

    /**
     * Zwraca tablicę z danymi użytkownika
     * @param $nazwa_uzytkownika
     * @return array|bool
     */
    public function uzytkownik_nazwa($nazwa_uzytkownika){
        //$q = "nazwa_uzytkownika =" . "'" . $nazwa_uzytkownika . "'";
        $this->db->select('*');
        $this->db->from('uzytkownicy');
        $this->db->where('nazwa_uzytkownika', $nazwa_uzytkownika);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            //return $query->result();
            return $query->result_array();
        } else {
            return false;
        }

    }

    /**
     * Zwraca tablicę z danymi użytkownika
     * @param $id_uzytkownika
     * @return array|bool
     */
    public function uzytkownik_id($id_uzytkownika){
        $this->db->select('uzytkownicy.id as id_uzytkownika, uzytkownicy.imie, uzytkownicy.nazwisko, uzytkownicy.nazwa_uzytkownika, uzytkownicy.email, uzytkownicy.data_urodzenia, panstwa.name as panstwo_zamieszkania, uzytkownicy_adres.kod_pocztowy, uzytkownicy_adres.miejscowosc, uzytkownicy_adres.numer_domu, uzytkownicy_adres.numer_lokalu, uzytkownicy_adres.id as id_adres');
        $this->db->from('uzytkownicy');
        //$this->db->join('strona_role','uzytkownicy.typ_uzytkownika=strona_role.typ_uzytkownika');
        $this->db->join('uzytkownicy_adres', 'uzytkownicy.id=uzytkownicy_adres.uzytkownicy_id');
        $this->db->join('panstwa', 'uzytkownicy_adres.panstwo_zamieszkania=panstwa.id');
        $this->db->where('uzytkownicy.id', $id_uzytkownika);
        $this->db->where('uzytkownicy_adres.adres_glowny', 1);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            //return $query->result();
            return $query->result_array();
        } else {
            return false;
        }

    }

    /**
     * Sprawdza czy użytkownik jest zaakceptowany
     * @param $id_uzytkownika
     * @return bool
     */
    public function uzytkownik_czy_zaakceptowano($id_uzytkownika){
        $this->db->select('uzytkownicy.zaakceptowany');
        $this->db->from('uzytkownicy');
        $this->db->where('uzytkownicy.id', $id_uzytkownika);
        $this->db->where('uzytkownicy.zaakceptowany', 1);
        $q=$this->db->get();
        if($q->num_rows()>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * Akceptuje użytkownika
     * @param $id_uzytkownika
     * @return bool
     */
    public function uzytkownik_akceptuj($id_uzytkownika){
        $this->db->set('zaakceptowany', 1);
        $this->db->where('id', $id_uzytkownika);
        $this->db->update('uzytkownicy');
        if($this->db->affected_rows()>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function uzytkownik_aktualizuj_kolumne($id_uzytkownika, $nazwa_kolumny, $wartosc){
        $this->db->set($nazwa_kolumny, $wartosc);
        $this->db->where('id', $id_uzytkownika);
        $this->db->update('uzytkownicy');
        if($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Aktualizuje dane użytkownika
     * @param $dane
     * @return bool
     */
    public function uzytkownik_aktualizuj($dane){

        //var_dump($dane);
        if(!is_numeric($dane['idUzytkownika'])) return FALSE;
        if(!empty($dane['FormImie'])) $this->db->set('uzytkownicy.imie', $dane['FormImie']);
        if(!empty($dane['FormNazwisko'])) $this->db->set('uzytkownicy.nazwisko', $dane['FormNazwisko']);
        if(!empty($dane['FormNazwaUzytkownika'])) $this->db->set('uzytkownicy.nazwa_uzytkownika', $dane['FormNazwaUzytkownika']);
        if(!empty($dane['FormDataUrodzenia'])) $this->db->set('uzytkownicy.data_urodzenia', $dane['FormDataUrodzenia']);
        //if(isset($dane['FormTypUzytkownika'])) $this->db->set('uzytkownicy.typ_uzytkownika', $dane['FormTypUzytkownika']);
        //var_dump($dane['FormTypUzytkownika']);
        if(!empty($dane['FormDataUrodzenia']) || !empty($dane['FormNazwaUzytkownika']) || !empty($dane['FormNazwisko']) || !empty($dane['FormImie'])){
            $this->db->where('uzytkownicy.id', $dane['idUzytkownika']);
            $this->db->update('uzytkownicy');
            //var_dump($this->db->error());
        }
        if(isset($dane['FormTypUzytkownika'])) {
            $this->db->delete('uzytkownicy_role', array('id_uzytkownika' => $dane['idUzytkownika']));
            foreach ($this->strona_role_to_uzytkownicy_role($dane['FormTypUzytkownika'], $dane['idUzytkownika']) as $item) {
                if($item['id_roli'] != 13) {
                    $data = array(
                        'id_uzytkownika' => $item['id'],
                        'id_roli' => $item['id_roli']
                    );
                    $this->db->insert('uzytkownicy_role', $data);
                }
            }
        }

        $this->db->reset_query();

        if(!empty($dane['FormPanstwo'])) $this->db->set('uzytkownicy_adres.panstwo_zamieszkania', $dane['FormPanstwo']);
        if(!empty($dane['FormKodPocztowy'])) $this->db->set('uzytkownicy_adres.kod_pocztowy', $dane['FormKodPocztowy']);
        if(!empty($dane['FormMiejscowosc'])) $this->db->set('uzytkownicy_adres.miejscowosc', $dane['FormMiejscowosc']);
        if(!empty($dane['FormNrDomu'])) $this->db->set('uzytkownicy_adres.numer_domu', $dane['FormNrDomu']);
        if(!empty($dane['FormNrLokalu'])) $this->db->set('uzytkownicy_adres.numer_lokalu', $dane['FormNrLokalu']);
        if(!empty($dane['FormPanstwo']) || !empty($dane['FormKodPocztowy']) || !empty($dane['FormMiejscowosc']) || !empty($dane['FormNrDomu']) || !empty($dane['FormNrLokalu'])){
            $this->db->where('uzytkownicy_adres.uzytkownicy_id', $dane['idUzytkownika']);
            $this->db->update('uzytkownicy_adres');
        }


        return true;
    }

    public function strona_role_to_uzytkownicy_role($tablicatypow, $id){
        $this->db->select('strona_role.id as id_roli, concat('.$id.') as id');
        $this->db->from('strona_role');
        $this->db->where_in('strona_role.typ_uzytkownika', $tablicatypow);
        $q=$this->db->get();
        if($q->num_rows()>0){
            $x = $q->result_array();
            //var_dump($x);
            //return array_column($x, 'id');
            return $x;
        }else{
            return FALSE;
        }
    }

    /**
     * Dodaje użytkownika do sytemu
     * @param $dane
     * @param int $rejestracja
     * @param int $trybtestu
     * @return bool
     */
    public function uzytkownik_dodaj($dane, $rejestracja=0, $trybtestu=0){


        $this->db->trans_start();
        $hash=uniqid(time(), true);

        $data = array(
            'imie'=> $dane['FormImie'],
            'nazwisko'=> $dane['FormNazwisko'],
            'nazwa_uzytkownika'=> $dane['FormNazwaUzytkownika'],
            'data_urodzenia'=> $dane['FormDataUrodzenia'],
            //'typ_uzytkownika'=> $dane['FormTypUzytkownika'],
            'email'=> $dane['FormEmail'],
            'register_hash'=>$hash,
            'zaakceptowany'=> ($rejestracja==0?1:0)
        );

        $this->db->insert('uzytkownicy', $data);

        /*if($this->db->affected_rows()==0){
            return FALSE;
        }*/
        $last_id = $this->db->insert_id();
        if($rejestracja==1){
            $data = array(
                'id_uzytkownika' => $last_id,
                'id_roli' => 16 //Gość
            );
            $this->db->insert('uzytkownicy_role', $data);
        }else{
            foreach ($this->strona_role_to_uzytkownicy_role($dane['FormTypUzytkownika'], $last_id) as $item) {
                $data = array(
                    'id_uzytkownika' => $item['id'],
                    'id_roli' => $item['id_roli']
                );
                $this->db->insert('uzytkownicy_role', $data);
            }
        }

        $data1= array(
            'uzytkownicy_id'=>$last_id,
            'adres_glowny'=>1,
            'panstwo_zamieszkania'=> $dane['FormPanstwo'],
            'kod_pocztowy'=> $dane['FormKodPocztowy'],
            'miejscowosc'=> $dane['FormMiejscowosc'],
            'numer_domu'=> $dane['FormNrDomu'],
            'numer_lokalu'=> $dane['FormNrLokalu']

        );

        $this->db->insert('uzytkownicy_adres', $data1);
        /*if($this->db->affected_rows()==0){
            $this->db->delete('uzytkownicy', array('id' => $last_id));
            return FALSE;
        }*/
        if($trybtestu == 0)
            $this->mail_model->rejestracja($dane['FormEmail'], $hash);
        $this->db->trans_complete();
        if($this->db->trans_status()){
            return TRUE;
        }else{
            return FALSE;
        }
        //return true;
    }

    /**
     * Zwraca listę wyszukanych użytkowników
     * @param $dane
     * @return array|bool
     */
    public function uzytkownik_szukaj($dane){
        //SELECT * FROM uzytkownicy
        // JOIN uzytkownicy_adres ON uzytkownicy.id=uzytkownicy_adres.uzytkownicy_id
        // JOIN panstwa ON uzytkownicy_adres.panstwo_zamieszkania=panstwa.id
        // WHERE uzytkownicy_adres.adres_glowny='1'
        // OR uzytkownicy.imie LIKE '%llll%'
        // OR uzytkownicy.nazwisko LIKE '%aas%'
        // OR uzytkownicy.nazwa_uzytkownika LIKE '%test%'
        // OR uzytkownicy.data_urodzenia LIKE '%1993-05-12%'
        // OR uzytkownicy_adres.kod_pocztowy LIKE'%j%'
        // OR uzytkownicy_adres.miejscowosc LIKE '%j%'
        // OR uzytkownicy_adres.numer_domu LIKE'%3%'
        // OR uzytkownicy_adres.numer_lokalu LIKE'%6%'
        // OR panstwa.name LIKE'%sdwsd%'

        $this->db->select('uzytkownicy.id, uzytkownicy.nazwa_uzytkownika, strona_role.nazwa_typu');
        $this->db->from('uzytkownicy');
        $this->db->join('uzytkownicy_adres','uzytkownicy.id=uzytkownicy_adres.uzytkownicy_id');
        //$this->db->join('panstwa','uzytkownicy_adres.panstwo_zamieszkania=panstwa.id');
        $this->db->join('strona_role','uzytkownicy.typ_uzytkownika=strona_role.typ_uzytkownika');
        $this->db->where('uzytkownicy_adres.adres_glowny', 1);
        //var_dump(($dane['FormImie']));
        //var_dump((!empty($dane['FormImie'])));
        //var_dump((!empty($dane['FormNazwisko'])));
        //var_dump((!empty($dane['FormPanstwo'])));
        //var_dump($dane['FormPanstwo']);
        if(!empty($dane['FormImie'])) $this->db->like('uzytkownicy.imie', $dane['FormImie']);
        if(!empty($dane['FormNazwisko'])) $this->db->like('uzytkownicy.nazwisko', $dane['FormNazwisko']);
        if(!empty($dane['FormNazwaUzytkownika'])) $this->db->like('uzytkownicy.nazwa_uzytkownika', $dane['FormNazwaUzytkownika']);
        if(!empty($dane['FormDataUrodzenia'])) $this->db->like('uzytkownicy.data_urodzenia', $dane['FormDataUrodzenia']);
        if(!empty($dane['FormKodPocztowy'])) $this->db->like('uzytkownicy_adres.kod_pocztowy', $dane['FormKodPocztowy']);
        if(!empty($dane['FormMiejscowosc'])) $this->db->like('uzytkownicy_adres.miejscowosc', $dane['FormMiejscowosc']);
        if(!empty($dane['FormNrDomu'])) $this->db->like('uzytkownicy_adres.numer_domu', $dane['FormNrDomu']);
        if(!empty($dane['FormNrLokalu'])) $this->db->like('uzytkownicy_adres.numer_lokalu', $dane['FormNrLokalu']);
        //if(!empty($dane['FormPanstwo'])) $this->db->like('panstwa.id', $dane['FormPanstwo']);
        if(!empty($dane['FormPanstwo'])) $this->db->like('uzytkownicy_adres.panstwo_zamieszkania', $dane['FormPanstwo']);

        $q=$this->db->get();

        if($q->num_rows()>0){
            return $q->result_array();
        }else{
            //echo":c";
            return FALSE;
        }
    }

    /**
     * Generuje hash oraz wysyła użytkownikowi mail do resetowania hasłą
     * @param $email64
     * @param $username
     * @return bool
     */
    public function resetuj_haslo($email64, $username){
        $email = base64_decode($email64);
        //var_dump($email);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $hash = $this->rejestracja_model->generuj_hash();
            $this->db->set('uzytkownicy.register_hash', $hash);
            $this->db->where('email', $email);
            $this->db->update('uzytkownicy');

            $this->mail_model->rejestracja($email, $hash);
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * Aktualizuje blokadę korzystania z serwisu dla danego użytkownika
     * @param $id_uzytkownika
     * @param int $blokuj
     * @return bool
     */
    public function zablokuj($id_uzytkownika, $blokuj=1){
        $this->db->trans_start();
        if($blokuj==1){
            $delete = array('id_uzytkownika' => $id_uzytkownika);
            $insert = array('id_uzytkownika' => $id_uzytkownika, 'id_roli'=>16);
            $this->db->delete('uzytkownicy_role', $delete);
            $this->db->insert('uzytkownicy_role', $insert);

            $this->db->trans_complete();
            if($this->db->trans_status()){
                return true;
            }else{
                return false;
            }
        }else{
            $delete = array('id_uzytkownika' => $id_uzytkownika);
            //$insert = array('id_uzytkownika' => $id_uzytkownika, 'id_roli'=>13);
            $this->db->delete('uzytkownicy_role', $delete);
            //$this->db->insert('uzytkownicy_role', $insert);
            $this->db->trans_complete();
            if($this->db->trans_status()){
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * Zwraca wartość true jeśli dany użytkownik ma zablokowany dostęp
     * @param $id_uzytkownika
     * @return bool
     */
    public function czy_ban($id_uzytkownika){
        $this->db->select('1');
        $this->db->from('uzytkownicy_role');
        $this->db->where('id_uzytkownika', $id_uzytkownika);
        $this->db->where('id_roli', 16);
        $q = $this->db->get();
        if($q->num_rows()>0){
            return true;
        }else{
            return false;
        }
    }
}