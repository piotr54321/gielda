<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Akcje_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('portfel_model');
    }

    /**
     * Sprawdz czy spółka o danym istnieje
     * @param $id_spolki
     * @return bool
     */
    public function czy_spolka_istnieje($id_spolki){
        if(is_numeric($id_spolki)){
            $this->db->select('1');
            $this->db->from('spolki_gieldowe');
            $this->db->where('spolki_gieldowe.id', $id_spolki);
            $q=$this->db->get();
            if ($q->num_rows()>0){
                return TRUE;
            }else{
                return FALSE;
            }

        }else{
            return FALSE;
        }
    }

    /**
     * Sprawdza czy nastąpił wzrosst
     * @param $id_spolki
     * @return bool|string
     */
    public function czy_wzrost($id_spolki){
        $this->db->select('*');
        $this->db->from('((SELECT notowania_gieldowe.cena, notowania_gieldowe.id_spolki, notowania_gieldowe.czas FROM notowania_gieldowe) UNION (SELECT notowania_gieldowe_historia.cena, notowania_gieldowe_historia.id_spolki, notowania_gieldowe_historia.czas FROM notowania_gieldowe_historia)) AS u');
        $this->db->where('u.id_spolki', $id_spolki);
        $this->db->order_by('czas', 'desc');
        $this->db->limit('2');
        $q=$this->db->get();
        if($q->num_rows()==2){
            $rows=$q->result_array();
            $cena_aktualna=$rows[0]['cena'];
            $cena_poprzednia=$rows[1]['cena'];
            ///var_dump($cena_aktualna, $cena_poprzednia);
            if($cena_aktualna == $cena_poprzednia){
                return '=';
            }elseif($cena_aktualna > $cena_poprzednia){
                return '>';
            }else{
                return '<';
            }
        }elseif($q->num_rows()==1){
            return '=';
        } else{
            return FALSE;
        }
    }

    /**
     * Zwraca klasę odpowiedzialną za kolor wiersza tabeli frameworku css bootstrap
     * @param $id_spolki
     * @return string
     */
    public function czy_wzrost_kolor($id_spolki){
        $r=$this->czy_wzrost($id_spolki);
        //var_dump($r);
        //exit;
        $x='';
        if($r=='<'){
            $x='table-danger';
        }elseif($r=='>'){
            $x='table-success';
        }else{
            $x='table-light';
        }
        return $x;
    }

    /**
     * Zwraca tablicę notowania spółek/spółki
     * @param bool $id_spolki
     * @return array|bool
     */
    public function notowania($id_spolki=FALSE){
        //SELECT notowania_gieldowe.ilosc_akcji, notowania_gieldowe.cena, indeks_gieldowy.nazwa as nazwa_indeksu, spolki_gieldowe.nazwa as nazwa_spolki, notowania_gieldowe.czas
        // FROM notowania_gieldowe
        // JOIN spolki_gieldowe ON spolki_gieldowe.id=notowania_gieldowe.id_spolki
        // JOIN indeks_gieldowy ON indeks_gieldowy.id=spolki_gieldowe.id_indeks_gieldowy
        // ORDER BY `nazwa_indeksu` ASC

        $this->db->select('spolki_gieldowe.id as id_spolki, notowania_gieldowe.ilosc_akcji, notowania_gieldowe.cena, kalkulator_ceny_akcji(id_spolki, 1) as aktualna_cena_akcji , indeks_gieldowy.nazwa as nazwa_indeksu, spolki_gieldowe.nazwa as nazwa_spolki, notowania_gieldowe.czas, kalkulator_ceny_spolki(id_spolki) as cena_spolki');
        $this->db->from('notowania_gieldowe');
        $this->db->join('spolki_gieldowe','spolki_gieldowe.id=notowania_gieldowe.id_spolki');
        $this->db->join('indeks_gieldowy','indeks_gieldowy.id=spolki_gieldowe.id_indeks_gieldowy');
        if($id_spolki){
            $this->db->where('id_spolki', $id_spolki);
        }
        $this->db->order_by('nazwa_indeksu, nazwa_spolki');
        $q=$this->db->get();

        if($q->num_rows() > 0){
            return $q->result_array();
        }else{
            return FALSE;
        }
    }

    /**
     * Zwraca tablicę historycznych notowań spółek/spółki
     * @param int $id_spolki
     * @return array|bool
     */
    public function notowania_historia($id_spolki=0){
        $this->db->select('*');
        //$this->db->from('((SELECT notowania_gieldowe.cena, notowania_gieldowe.id_spolki, notowania_gieldowe.czas FROM notowania_gieldowe) UNION (SELECT notowania_gieldowe_historia.cena, notowania_gieldowe_historia.id_spolki, notowania_gieldowe_historia.czas FROM notowania_gieldowe_historia)) AS u');
        $this->db->from('((SELECT notowania_gieldowe.cena, notowania_gieldowe.id_spolki, notowania_gieldowe.czas FROM notowania_gieldowe) UNION (SELECT notowania_gieldowe_historia.cena, notowania_gieldowe_historia.id_spolki, notowania_gieldowe_historia.czas FROM notowania_gieldowe_historia)) AS u');
        if($id_spolki!=0){
            $this->db->where('u.id_spolki', $id_spolki);
        }
        $q=$this->db->get();
        if($q->num_rows()>0){
            return $q->result_array();
        }else{
            return FALSE;
        }
    }

    /**
     * Zwraca historię notowań w formacie json
     * @param int $id_spolki
     * @return array
     */
    public function notowania_historia_json($id_spolki=0){
        $arrays=array();
        $array1=$this->notowania_historia($id_spolki);
        $array[1]=array();
        $array[2]=array();
        $array[3]=array();
        $x=1;
        foreach ($array1 as $item):
            $array[1][]=array($x, (double)$item['cena']);
            $array[2][]=array($x);
            $array[3][]=array((double)$item['cena']);
            $x++;
        endforeach;
        $arrays[1]=json_encode($array[1]);
        $arrays[2]=json_encode($array[2]);
        $arrays[3]=json_encode($array[3]);
        //var_dump($array[1]);
        //;
        return $arrays;
    }

    /**
     * @param $id_uzytkownika
     * @param $id_spolki
     * @return bool
     */
    public function uzytkownikownicy_akcje_wiersz($id_uzytkownika, $id_spolki){
        $this->db->select('1');
        $this->db->from('uzytkownicy_akcje');
        $this->db->where('id_uzytkownika', $id_uzytkownika);
        $this->db->where('id_spolki', $id_spolki);
        $q=$this->db->get();
        if($q->num_rows()>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * Aktualizuje posiadane akcje dla danego użytkownika (Kupno)
     * @param $id_spolki
     * @param int $ilosc
     * @param $id_uzytkownika
     * @return bool
     */
    public function kup($id_spolki, $ilosc=0, $id_uzytkownika){
        //$notowania=$this->notowania($id_spolki);
        //return $notowania;

        if($this->calc($id_spolki, $ilosc) != 0){
            //var_dump((string)$this->calc($id_spolki, $ilosc) <= (string)$this->portfel_model->uzytkownik_waluty_ilosc($id_uzytkownika, 6)[0]['ilosc']);
            ///exit;
            if((float)$this->calc($id_spolki, $ilosc) <= (float)$this->portfel_model->uzytkownik_waluty_ilosc($id_uzytkownika, 6)[0]['ilosc']){
                $cena=$this->calc($id_spolki, $ilosc);
                $this->db->trans_start();
                $this->portfel_model->uzytkownik_waluty_sprzedaj($id_uzytkownika, 6, $cena, 1);

                if(!$this->uzytkownikownicy_akcje_wiersz($id_uzytkownika, $id_spolki)){
                    $data=array(
                        'id_uzytkownika'=> $id_uzytkownika,
                        'id_spolki'=> $id_spolki,
                        'ilosc'=> $ilosc,
                        'cena'=> $cena
                    );
                    $this->db->insert('uzytkownicy_akcje', $data);
                }else{
                    $this->db->set('ilosc', 'ilosc+'.$ilosc, false);
                    $this->db->set('cena', $cena, false);
                    $this->db->where('id_uzytkownika', $id_uzytkownika);
                    $this->db->where('id_spolki', $id_spolki);
                    $this->db->update('uzytkownicy_akcje');
                }

                $this->db->trans_complete();
                if ($this->db->trans_status()) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }

    }

    /**
     * Aktualizuje posiadane akcje dla danego użytkownika (Sprzedąż)
     * @param $id_spolki
     * @param $ilosc
     * @param $id_uzytkownika
     * @return bool
     */
    public function sprzedaj($id_spolki, $ilosc, $id_uzytkownika){
        if($this->kalkulator_ceny_sprzedazy($id_spolki, $ilosc, $id_uzytkownika) != 0){
            $cena=$this->kalkulator_ceny_sprzedazy($id_spolki, $ilosc, $id_uzytkownika);
            $this->db->trans_start();

            $this->db->set('ilosc', 'ilosc-'.$ilosc, false);
            $this->db->set('cena', $cena, false);
            //$this->db->set('cena','cena+'.$cena, false);
            $this->db->where('id_uzytkownika', $id_uzytkownika);
            $this->db->where('id_spolki', $id_spolki);
            $this->db->update('uzytkownicy_akcje');

            $this->portfel_model->uzytkownik_waluty_kup($id_uzytkownika, 6, $cena, 1);

            $this->db->trans_complete();
            if ($this->db->trans_status()) {
                return TRUE;
            } else {
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }

    /**
     * Zwraca ilość dostępnych akcji danej spółki
     * @param $id_spolki
     * @return bool
     */
    public function dostepne_akcje($id_spolki){
        $q=$this->db->query('SELECT dostepne_akcje_spolki('.$id_spolki.') as dostepne_akcje');
        $dostepne_akcje = $q->row_array()['dostepne_akcje'];
        if($dostepne_akcje<=0) return FALSE;
        return $dostepne_akcje;
    }

    /**
     * Zwraca ilość wykupionych akcji danej spółki
     * @param $id_spolki
     * @return bool
     */
    public function wykupione_akcje($id_spolki){
        $q=$this->db->query('SELECT ilosc_kupionych_akcji_spolki('.$id_spolki.') as wykupione_akcje');
        $wykupione_akcje = $q->row_array()['wykupione_akcje'];
        if($wykupione_akcje<=0) return FALSE;
        return $wykupione_akcje;
    }

    /**
     * Zwraca cenę kupna danej ilość akcji spółki
     * @param $id_spolki
     * @param int $ilosc
     * @return bool
     */
    public function calc($id_spolki, $ilosc=0){
        if($ilosc<=0 || !is_numeric($ilosc)){
            return FALSE;
        }else{
            $q=$this->db->query('SELECT kalkulator_ceny_akcji('.$id_spolki.','.$ilosc.') as cena');
            $cena = $q->row_array()['cena'];
            if($cena<=0) return FALSE;
            return $cena;
        }
    }

    /**
     * Zwraca cenę sprzedaży danej ilość akcji spółki
     * @param $id_spolki
     * @param $ilosc
     * @param $id_uzytkownika
     * @return bool
     */
    public function kalkulator_ceny_sprzedazy($id_spolki, $ilosc, $id_uzytkownika){
        $q=$this->db->query('SELECT kalkulator_ceny_sprzedazy_akcji('.$id_spolki.','.$ilosc.', '.$id_uzytkownika.') as cena');
        $cena = $q->row_array()['cena'];
        if($cena<=0) return FALSE;
        return $cena;
    }

    /**
     * Zwraca wartość całej spółki
     * @param $id_spolki
     * @return bool
     */
    public function kalkulator_ceny_spolki($id_spolki){

            $q=$this->db->query('SELECT kalkulator_ceny_spolki('.$id_spolki.') as cena');
            $cena = $q->row_array()['cena'];
            if($cena<=0) return FALSE;
            return $cena;
    }

    /**
     * Zwraca tablicę akcji danego użytkownika
     * @param $id_uzytkownika
     * @param int $id_spolki
     * @return array|bool
     */
    public function uzytkownik_akcje($id_uzytkownika, $id_spolki=0){
        $this->db->select('spolki_gieldowe.nazwa as spolka_gieldowa, spolki_gieldowe.id, indeks_gieldowy.nazwa as spolka_indeks, uzytkownicy_akcje.ilosc, kalkulator_ceny_sprzedazy_akcji(spolki_gieldowe.id, uzytkownicy_akcje.ilosc, uzytkownicy_akcje.id_uzytkownika) as wartosc_akcji');
        $this->db->from('uzytkownicy_akcje');
        $this->db->join('spolki_gieldowe','spolki_gieldowe.id = uzytkownicy_akcje.id_spolki');
        $this->db->join('indeks_gieldowy','indeks_gieldowy.id=spolki_gieldowe.id_indeks_gieldowy');
        $this->db->where('uzytkownicy_akcje.id_uzytkownika', $id_uzytkownika);
        $this->db->where('ilosc>',0);
        if($id_spolki !=0){
            $this->db->where('uzytkownicy_akcje.id_spolki', $id_spolki);
        }
        $q=$this->db->get();

        if($q->num_rows()>0){
            return $q->result_array();
        }else{
            return FALSE;
        }
    }

    /**
     * Sprawdza czy użytkownik posiada akcje danej spółki
     * @param $id_uzytkownika
     * @param $id_spolki
     * @return bool
     */
    public function uzytkownik_akcje_czy_posiada($id_uzytkownika, $id_spolki){
        $ilosc=$this->uzytkownik_akcje($id_uzytkownika, $id_spolki)[0]['ilosc'];
        if($ilosc > 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

}