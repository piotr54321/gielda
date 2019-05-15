<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Portfel_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function uzytkownik_walutyv2($id_uzytkownika, $waluta_id=0, $waluta_ilosc=0){
        $this->db->select('waluty.id, waluty.nazwa, waluty.nazwa_kod, panstwa.name, waluty.wplata, uzytkownicy_portfel.ilosc');
        $this->db->from('waluty');
        $this->db->join('panstwa','waluty.id_panstwo_wydajace=panstwa.id');
        $this->db->join('uzytkownicy_portfel','waluty.id=uzytkownicy_portfel.id_waluty');
        $this->db->where('uzytkownicy_portfel.id_uzytkownika', $id_uzytkownika);
        $this->db->where('uzytkownicy_portfel.ilosc>', 0);
        if($waluta_ilosc!=0){
            $this->db->where('uzytkownicy_portfel.ilosc>=', $waluta_ilosc);
        }
        if($waluta_id != 0){
            $this->db->where('waluty.id', $waluta_id);
            $this->db->limit(1);
        }
        $this->db->order_by('waluty.nazwa');
        $q=$this->db->get();
        if($q->num_rows()>0){
            return $q->result_array();
        }else{
            return FALSE;
        }
    }

    /**
     * Aktualizuje ilość danej waluty dla danego użytkownika (kupno)
     * @param $id_uzytkownika
     * @param int $waluta_id
     * @param int $waluta_ilosc
     * @param int $wymiana
     * @return bool
     */
    public function uzytkownik_waluty_kup($id_uzytkownika, $waluta_id=0, $waluta_ilosc=0, $wymiana=0){
        //var_dump($this->waluta_info($waluta_id));
        //var_dump($wymiana);
        /*if(($wymiana==0 && ($waluta_id ==0 || $waluta_ilosc < 1 || ($this->waluta_info($waluta_id)[0]['wplata']==0))) || !is_numeric($waluta_id)){
            return FALSE;
        }else {
            if($wymiana==0){*/
                $this->db->trans_start();
            //}*/
            $this->db->select('id_waluty');
            $this->db->from('uzytkownicy_portfel');
            $this->db->where('id_waluty', $waluta_id);
            $this->db->where('id_uzytkownika', $id_uzytkownika);
            $q = $this->db->get();
            if ($q->num_rows() == 0) {
                $data = array(
                    'id_uzytkownika' => $id_uzytkownika,
                    'id_waluty' => $waluta_id,
                    'ilosc' => 0
                );
                $this->db->insert('uzytkownicy_portfel', $data);
                //exit;
            }
            $this->db->set('uzytkownicy_portfel.ilosc', 'uzytkownicy_portfel.ilosc+' . $waluta_ilosc . '', false);
            $this->db->where('uzytkownicy_portfel.id_waluty', $waluta_id);
            $this->db->where('uzytkownicy_portfel.id_uzytkownika', $id_uzytkownika);
            $this->db->limit(1);
            $this->db->update('uzytkownicy_portfel');

            /*if($wymiana==0) {*/
                $this->db->trans_complete();
                if ($this->db->trans_status()) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            //}*/
            //return true;
        //}
    }

    /**
     * Aktualizuje ilość danej waluty dla danego użytkownika (sprzedaż)
     * @param $id_uzytkownika
     * @param int $waluta_id
     * @param int $waluta_ilosc
     * @param int $wymiana
     * @return bool
     */
    public function uzytkownik_waluty_sprzedaj($id_uzytkownika, $waluta_id=0, $waluta_ilosc=0, $wymiana=0){

        /*if(($wymiana==0 && ($waluta_ilosc==0 || !$this->uzytkownik_walutyv2($id_uzytkownika, $waluta_id, $waluta_ilosc) || ($this->waluta_info($waluta_id)[0]['wplata']==0))) || !is_numeric($waluta_id) ){
            //var_dump($wymiana);
            return FALSE;
        }else{*/
            //var_dump($wymiana);
            //if ($wymiana == 0)
            $this->db->trans_start();
            $this->db->set('uzytkownicy_portfel.ilosc', 'uzytkownicy_portfel.ilosc-'.$waluta_ilosc.'', false);
            $this->db->where('uzytkownicy_portfel.id_waluty', $waluta_id);
            $this->db->where('uzytkownicy_portfel.id_uzytkownika', $id_uzytkownika);
            $this->db->limit(1);
            $this->db->update('uzytkownicy_portfel');
            /*if($wymiana==0){*/
                $this->db->trans_complete();
                if($this->db->trans_status()){
                    return TRUE;
                }else{
                    return FALSE;
                }
           // }*/

        //}
    }

    //SELECT uzytkownicy.id, uzytkownicy.nazwa_uzytkownika, uzytkownicy_portfel.id_waluty, uzytkownicy_portfel.ilosc, waluty.nazwa FROM (((waluty INNER JOIN uzytkownicy_portfel ON
    // waluty.id=uzytkownicy_portfel.id_waluty) INNER JOIN panstwa ON panstwa.id=waluty.id_panstwo_wydajace) INNER JOIN uzytkownicy ON uzytkownicy_portfel.id_uzytkownika=uzytkownicy.id)

    /**
     * Nie używane
     * @param $id_uzytkownika
     * @param int $id_portfela
     * @param int $ilosc_waluty
     * @return array|bool
     */
    public function uzytkownik_waluty($id_uzytkownika, $id_portfela=0, $ilosc_waluty=0){
        $this->db->select('uzytkownicy.nazwa_uzytkownika, uzytkownicy_portfel.id, uzytkownicy_portfel.id_waluty, uzytkownicy_portfel.ilosc, waluty.nazwa');
        $this->db->from('waluty');
        $this->db->join('uzytkownicy_portfel', 'waluty.id = uzytkownicy_portfel.id_waluty');
        $this->db->join('panstwa', 'panstwa.id = waluty.id_panstwo_wydajace');
        $this->db->join('uzytkownicy', 'uzytkownicy_portfel.id_uzytkownika = uzytkownicy.id');
        if($ilosc_waluty == 0)
            $this->db->where('uzytkownicy_portfel.ilosc >', $ilosc_waluty);
        else
            $this->db->where('uzytkownicy_portfel.ilosc >=', $ilosc_waluty);
        $this->db->where('uzytkownicy.id', $id_uzytkownika);
        if($id_portfela != 0 && is_numeric($id_portfela)){
            $this->db->where('uzytkownicy_portfel.id', $id_portfela);
        }
        $q = $this->db->get();


        if ($q->num_rows() > 0) {
            //return $q->result();
            return $q->result_array();
            //return true;

        } else {
            return false;
        }
    }

    /**
     * Zwraca ilość danej waluty dla danego użytkownika
     * @param $id_uzytkownika
     * @param $id_waluty
     * @return array|bool
     */
    public function uzytkownik_waluty_ilosc($id_uzytkownika, $id_waluty){
        $this->db->select('ilosc');
        $this->db->from('uzytkownicy_portfel');
        $this->db->where('id_uzytkownika', $id_uzytkownika);
        $this->db->where('id_waluty', $id_waluty);
        $q=$this->db->get();

        if($q->num_rows()>0){
            return $q->result_array();
        }else{
            return FALSE;
        }
    }

    /**
     * Nie używane
     * @param bool $dodaj
     * @param $id_uzytkownika
     * @param int $ilosc_waluty
     * @param int $id_portfela
     * @param int $id_waluty
     * @return bool
     */
    public function uzytkownik_waluty_update($dodaj=false, $id_uzytkownika, $ilosc_waluty=0, $id_portfela=0, $id_waluty=0){
        if($this->uzytkownik_waluty($id_uzytkownika, $id_portfela, $ilosc_waluty) == true && $dodaj==false && is_numeric($ilosc_waluty)){
            $this->db->set('uzytkownicy_portfel.ilosc', 'uzytkownicy_portfel.ilosc-'.$ilosc_waluty.'', false);
            $this->db->where('uzytkownicy_portfel.id', $id_portfela);
            $this->db->where('uzytkownicy_portfel.id_uzytkownika', $id_uzytkownika);
            $this->db->limit(1);
            $q=$this->db->update('uzytkownicy_portfel');
            if($q)
                return true;
            else
                return false;
        }elseif($dodaj == true){
            if($this->waluty($id_waluty)) {
                $this->db->select('id_waluty');
                $this->db->from('uzytkownicy_portfel');
                //$this->db->join('waluty', 'uzytkownicy_portfel.id_waluty=waluty.id');
                $this->db->where('id_waluty',$id_waluty);
                $this->db->where('id_uzytkownika',$id_uzytkownika);
                //$this->db->limit(1);
                $q=$this->db->get();
                //echo $q->num_rows;
                if($q->num_rows() == 0){
                    //$id_transakcji=$s = uniqid(time(), true);
                    $data= array(
                        'id_uzytkownika' => $id_uzytkownika,
                        'id_waluty' => $id_waluty,
                        'ilosc' => 0
                    );
                    $this->db->insert('uzytkownicy_portfel', $data);
                    //exit;
                }

                $this->db->set('uzytkownicy_portfel.ilosc', 'uzytkownicy_portfel.ilosc+'.$ilosc_waluty.'', false);
                $this->db->where('uzytkownicy_portfel.id_waluty', $id_waluty);
                $this->db->where('uzytkownicy_portfel.id_uzytkownika', $id_uzytkownika);
                $this->db->limit(1);
                $q=$this->db->update('uzytkownicy_portfel');
                if($q)
                    return true;
                else
                    return false;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }

    public function  uzytkownik_waluta($id_uzytkownika, $id_waluty){
        $this->db->select('id_waluty');
        $this->db->from('uzytkownicy_portfel');
        //$this->db->join('waluty', 'uzytkownicy_portfel.id_waluty=waluty.id');
        $this->db->where('id_waluty',$id_waluty);
        $this->db->where('id_uzytkownika',$id_uzytkownika);
        $this->db->where('ilosc >',0);
        //$this->db->limit(1);
        $q=$this->db->get();
        //echo $q->num_rows;
        if($q->num_rows() == 0){
            //$id_transakcji=$s = uniqid(time(), true);
            return false;
            //exit;
        }else{
            return true;
        }
    }

    /**
     * Zapytanie zwracające informacje na temat walut/waluty
     * @param int $id_waluty - jeśli puste to zwraca informację dla wszystkich walut
     * @param int $wplata
     * @return bool
     */
    public function waluta_info($id_waluta=0, $wplata=0){
        $this->db->select('waluty.id, waluty.nazwa, waluty.nazwa_kod, panstwa.name, waluty.wplata');
        $this->db->from('waluty');
        $this->db->join('panstwa','waluty.id_panstwo_wydajace=panstwa.id');
        if($wplata != 0){
            $this->db->where('waluty.wplata', 1);
        }
        if($id_waluta != 0){
            $this->db->where('waluty.id', $id_waluta);
            $this->db->limit(1);
        }
        $q=$this->db->get();
        if($q->num_rows()>0){
            return $q->result_array();
        }else{
            return false;
        }
    }

    /**
     * Zapytanie zwracające informacje na temat walut/waluty
     * @param int $id_waluty - jeśli puste to zwraca informację dla wszystkich walut
     * @param int $wplata
     * @return bool
     */
    public function waluty($id_waluty=0, $wplata=0){
        if($id_waluty != 0){
            $this->db->select('waluty.id, waluty.nazwa, waluty.nazwa_kod, panstwa.name');
            $this->db->from('waluty');
            $this->db->join('panstwa','waluty.id_panstwo_wydajace=panstwa.id');
            $this->db->where('waluty.id', $id_waluty);
            $this->db->limit(1);
            $q=$this->db->get();
            if($q->num_rows()>0){
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * Tworzy id transackji
     * @param $id_uzytkownika
     * @param $typ_transakcji ->
     *      1. wyplata
     *      2. wplata
     * @return string
     */
    public function id_transakcji_tworz($id_uzytkownika, $typ_transakcji){
        $this->db->trans_start();
        $this->db->where('id_uzytkownika', $id_uzytkownika);
        $this->db->where('czy_zaakceptowano',0);
        $this->db->delete('historia_transakcji');
        $id_transakcji=$s = uniqid(time(), true);
        $data= array(
            'id_uzytkownika' => $id_uzytkownika,
            'typ_transakcji' => $typ_transakcji,
            'id_transakcji' => $id_transakcji
        );
        $this->db->insert('historia_transakcji', $data);
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            return $id_transakcji;
        } else {
            return FALSE; //??
        }
    }

    /**
     * Sprawdz czy id transakcji jest poprawne
     * @param $id_transakcji
     * @param $id_uzytkownika
     * @return bool
     */
    public function id_transakcji_sprawdz($id_transakcji, $id_uzytkownika){
        $this->db->select('czy_zaakceptowano');
        $this->db->from('historia_transakcji');
        $this->db->where('id_transakcji', $id_transakcji);
        $this->db->where('id_uzytkownika', $id_uzytkownika);
        $q=$this->db->get();
        if($q->num_rows()>0){
            $row=$q->result_array();
            if($row[0]['czy_zaakceptowano']==1){
                return FALSE;
            }else{
                $this->db->trans_start();
                $this->db->set('czy_zaakceptowano', 1);
                $this->db->where('id_transakcji', $id_transakcji);
                $this->db->where('id_uzytkownika', $id_uzytkownika);
                $this->db->limit(1);
                $this->db->update('historia_transakcji');
                $this->db->trans_complete();
                //var_dump($this->db->trans_status());
                //exit;
                if ($this->db->trans_status()) {
                    return TRUE;
                } else {
                    return FALSE; //??
                }
            }
        }else{
            return FALSE;
        }
    }

}