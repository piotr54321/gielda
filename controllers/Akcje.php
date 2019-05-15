<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akcje extends MY_Controller {
    function __construct(){
        parent::__construct();
        //var_dump($this->acl_library->funkcjav2());
        $this->czy_zalogowano();
        if($this->acl_library->funkcjav2() == FALSE) show_404();
        
    }
    //SELECT indeks_gieldowy.id, spolki_gieldowe.nazwa, notowania_gieldowe.ilosc_akcji, notowania_gieldowe.czas, notowania_gieldowe.ilosc_akcji, notowania_gieldowe.cena FROM ((indeks_gieldowy INNER JOIN spolki_gieldowe ON indeks_gieldowy.id = spolki_gieldowe.id_indeks_gieldowy) INNER JOIN notowania_gieldowe ON notowania_gieldowe.id_spolki=spolki_gieldowe.id)


    /**
     *Wyświetla podgląd akcji
     */
    public function index()
    {
        $this->load->view('head');
        $this->load->view('navbar');

        if(!$this->akcje_model->notowania()){
            $data['brak_notowan']='Nie znaleziono notowań giełdowych w bazie';
        }else{
            $data['notowania']=$this->akcje_model->notowania();
        }

        $this->load->view('akcje/podglad', $data);
        $this->load->view('footer');
    }

    /**
     * Wyświetla formularz zakupu akcji danej spółki
     * @param int $id_spolki
     */
    public function kup($id_spolki=0){
        $this->load->view('head');
        $this->load->view('navbar');

        //var_dump($this->akcje_model->wykupione_akcje(10));
        //var_dump($this->akcje_model->kup(10));
        if($id_spolki==0){
            $data['spolka_gieldowa']=FALSE;
        }else{
            $data['spolka_gieldowa']=$this->akcje_model->notowania($id_spolki);
        }
        $data['id_spolki']=$id_spolki;
        if($data['spolka_gieldowa']){
            $data['dostepne_akcje']=$this->akcje_model->dostepne_akcje($id_spolki);
            $data['wykupione_akcje']=$this->akcje_model->wykupione_akcje($id_spolki);
        }
        $data['posiadane_srodki']=round(($this->portfel_model->uzytkownik_waluty_ilosc($this->session->id_uzytkownika, 6)[0]['ilosc']), 2, PHP_ROUND_HALF_DOWN);;
        //var_dump($data['posiadane_srodki']);
        //var_dump($data['dostepne_akcje']);
        //var_dump($data['spolka_gieldowa']);
        $this->load->view('akcje/kup', $data);
        $this->load->view('footer');
    }

    /**
     * Sprawdza czy dane podane w metodzie kup są poprawne
     * @param int $id_transakcji
     */
    public function kup_check($id_transakcji=0){
        $this->load->view('head');
        $this->load->view('navbar');
        if(!$this->akcje_model->notowania()){
            $data['brak_notowan']='Nie znaleziono notowań giełdowych w bazie';
        }else{
            $data['notowania']=$this->akcje_model->notowania();
        }
        if($this->portfel_model->id_transakcji_sprawdz($id_transakcji, $this->session->id_uzytkownika)) {
            $this->form_validation->set_rules('iloscAkcji', 'iloscAkcji', 'required|trim|xss_clean');
            $this->form_validation->set_rules('idSpolki', 'idSpolki', 'required|trim|xss_clean');
            if ($this->form_validation->run() == FALSE) {
                redirect('/akcje', 'location');
            }

            if ($this->akcje_model->kup($this->input->post('idSpolki'), $this->input->post('iloscAkcji'), $this->session->id_uzytkownika)) {
                $data['info'] = 'Kupiono akcje';
            } else {
                $data['info'] = 'Kupno akcji zakończone niepowodzeniem';
            }
        }else{
            $data['info'] = 'Taka transakcja nie istnieje';
        }

        $this->load->view('akcje/podglad', $data);
        $this->load->view('footer');
        //var_dump($this->input->post('idSpolki'), $this->input->post('iloscAkcji'));
        //var_dump($this->akcje_model->calc($this->input->post('idSpolki'), $this->input->post('iloscAkcji')));
        //var_dump($this->input->post('total'));


    }

    /**
     * Wyświetla informacje na temat spółki
     * @param int $id_spolki
     */
    public function spolka($id_spolki=0){
        $this->load->view('head');
        $this->load->view('navbar');
        $data=array();
        //var_dump($this->db->call_function('kalkulator_ceny_akcji', 1, 10));
        if($this->akcje_model->czy_spolka_istnieje($id_spolki)){
            $data['notowania_spolki']=$this->akcje_model->notowania_historia($id_spolki);
            $data['notowania_historia_json']=$this->akcje_model->notowania_historia_json($id_spolki);
            $data['spolka_gieldowa']=$this->akcje_model->notowania($id_spolki);
            $data['dostepne_akcje']=$this->akcje_model->dostepne_akcje($id_spolki);
            $data['posiadane_akcje']=$this->akcje_model->uzytkownik_akcje($this->session->id_uzytkownika, $id_spolki)[0]['ilosc'];
        }else{
            $data['info']='Nieprawidłowy link';
        }
        //var_dump($data);
        $this->load->view('akcje/spolka', $data);
        $this->load->view('footer');
    }

    /**
     * Wyśietla formularz sprzedaży akcji danej spółki
     * @param int $id_spolki
     */
    public function sprzedaj($id_spolki=0){
        //var_dump($this->akcje_model->calc(10,1));
        $this->load->view('head');
        $this->load->view('navbar');

        if($id_spolki==0){
            $data['spolka_gieldowa']=FALSE;
        }else{
            $data['spolka_gieldowa']=$this->akcje_model->notowania($id_spolki);
        }
        $data['id_spolki']=$id_spolki;
        if($data['spolka_gieldowa']){
            $data['dostepne_akcje']=$this->akcje_model->uzytkownik_akcje($this->session->id_uzytkownika, $id_spolki)[0]['ilosc'];
            $data['wykupione_akcje']=$this->akcje_model->wykupione_akcje($id_spolki);
        }

        $this->load->view('akcje/sprzedaj', $data);
        $this->load->view('footer');
    }

    /**
     * Sprawdza czy dane podane w metodzie sprzedaj są poprawne
     * @param int $id_transakcji
     */
    public function sprzedaj_check($id_transakcji=0){
        $this->load->view('head');
        $this->load->view('navbar');
        if(!$this->akcje_model->notowania()){
            $data['brak_notowan']='Nie znaleziono notowań giełdowych w bazie';
        }else{
            $data['notowania']=$this->akcje_model->notowania();
        }
        if($this->portfel_model->id_transakcji_sprawdz($id_transakcji, $this->session->id_uzytkownika)) {
            $this->form_validation->set_rules('iloscAkcji', 'iloscAkcji', 'required|trim|xss_clean');
            $this->form_validation->set_rules('idSpolki', 'idSpolki', 'required|trim|xss_clean');
            if ($this->form_validation->run() == FALSE) {
                redirect('/akcje', 'location');
            }

            if ($this->akcje_model->sprzedaj($this->input->post('idSpolki'), $this->input->post('iloscAkcji'), $this->session->id_uzytkownika)) {
                $data['info'] = 'Sprzedano akcje';
            } else {
                $data['info'] = 'Sprzedaż akcji zakończone niepowodzeniem';
            }
        }else{
            $data['info'] = 'Taka transakcja nie istnieje';
        }

        $this->load->view('akcje/podglad', $data);
        $this->load->view('footer');
    }

    /**
     * Wyświetla cenę akcji danej spólki w formacie json
     * @param int $id_spolki
     * @param int $ilosc
     */
    public function cena_akcji($id_spolki=0, $ilosc=0){
        $cena=$this->akcje_model->calc($id_spolki, $ilosc);
        echo json_encode(array('cena'=>$cena));
    }

    /**
     * Wyświetla cenę sprzedaży akcji danej spółki dla danego użytkownika oraz ilości akcji
     * @param int $id_spolki
     * @param int $ilosc
     * @param $id_uzytkownika
     */
    public function cena_akcji_sprzedaz($id_spolki=0, $ilosc=0, $id_uzytkownika){
        $cena=$this->akcje_model->kalkulator_ceny_sprzedazy($id_spolki, $ilosc, $id_uzytkownika);
        echo json_encode(array('cena'=>$cena));
    }

    /**
     *Wyśietla użytkownikowi listę posiadanych przez niego akcji
     */
    public function moje_akcje(){
        $this->load->view('head');
        $this->load->view('navbar');

        if(!$this->akcje_model->uzytkownik_akcje($this->session->id_uzytkownika)){
            $data['brak_notowan']='Nie kupiłeś żadnych akcji';
        }else{
            $data['notowania']=$this->akcje_model->uzytkownik_akcje($this->session->id_uzytkownika);
        }

        $this->load->view('akcje/mojeakcje', $data);
        $this->load->view('footer');
    }
}
