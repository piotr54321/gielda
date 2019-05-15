<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Waluty extends MY_Controller {
    function __construct(){
        parent::__construct();
        $this->czy_zalogowano();
        if($this->acl_library->funkcjav2() == false) show_404();
    }

    /**
     *Wyświetla listę kursów walut
     */
    public function index()
    {
        $this->load->view('head');
        $this->load->view('navbar');
        //$data['uzytkownik']=$this->uzytkownik_model->uzytkownik($this->session->id_uzytkownika);
        //var_dump($this->uzytkownik_model->uzytkownik($this->session->id_uzytkownika));
        //$this->load->view('home', $data);
        //$this->load->view('home');
        if($this->waluty_model->waluty_notowania()==false) {
            $this->load->view('waluty/podglad', array('brak_walut' => 'Brak kursu walut'));
        }else{
            $data['dane_portfela']=$this->waluty_model->waluty_notowania();
            $this->load->view('waluty/podglad', $data);
        }
        $this->load->view('footer');
    }

    /**
     * Wyświetla formularz wymiany dla danego kursu waluty
     * @param int $id_waluta1
     * @param int $id_waluta2
     */
    public function wymien($id_waluta1=0, $id_waluta2=0){
        $this->load->view('head');
        $this->load->view('navbar');
        //var_dump($this->waluty_model->aktualny_kurs_waluty($id_waluta1, $id_waluta2));
        $data['kurs_waluty']=$this->waluty_model->aktualny_kurs_waluty($id_waluta1, $id_waluta2);
        $data['kurs_waluty2']=$this->waluty_model->aktualny_kurs_waluty($id_waluta2, $id_waluta1);
        $data['posiadana_ilosc']=$this->portfel_model->uzytkownik_waluty_ilosc($this->session->id_uzytkownika, $id_waluta1)[0]['ilosc'];

        $this->load->view('waluty/wymien', $data);
        $this->load->view('footer');
    }

    /**
     * Jeśli wprowadzone dane w formularzu są poprawne to wymienia walutę
     * @param int $id_transakcji
     */
    public function wymien_check($id_transakcji=0){
        $this->load->view('head');
        $this->load->view('navbar');
        $data=array();
        //var_dump($this->waluty_model->aktualny_kurs_waluty($id_waluta1, $id_waluta2));
        if(!$this->portfel_model->id_transakcji_sprawdz($id_transakcji, $this->session->id_uzytkownika)){
            $data['info'] = 'Brak takiej transakcji..';
        }else{
            $this->form_validation->set_rules('iloscWaluty', 'iloscWaluty', 'required|trim|xss_clean|integer');
            $this->form_validation->set_rules('idWaluta1', 'idWaluta1', 'required|trim|xss_clean|integer');
            $this->form_validation->set_rules('idWaluta2', 'idWaluta2', 'required|trim|xss_clean|integer');
            $this->form_validation->set_rules('nazwaWaluta2', 'nazwaWaluta2', 'required|trim|xss_clean');
            if (!$this->form_validation->run()) {
                $data['info'] = 'Niepoprawna wartość pola';
            } else {
                $iloscWaluty = $this->input->post('iloscWaluty');
                $idWaluta1 = $this->input->post('idWaluta1');
                $idWaluta2 = $this->input->post('idWaluta2');
                $nazwaWaluta2 = $this->input->post('nazwaWaluta2');
                if($this->waluty_model->wymien_walute($this->session->id_uzytkownika, $idWaluta1, $idWaluta2, $iloscWaluty)){
                    $data['info'] = 'Wymiana zakończona powodzeniem, uzyskano '.$iloscWaluty.' '.$nazwaWaluta2;
                }else{
                    $data['info'] = 'Wymiana nie powiodła się';
                }
            }

        }

        $this->load->view('waluty/wymien', $data);
        $this->load->view('footer');
    }

}